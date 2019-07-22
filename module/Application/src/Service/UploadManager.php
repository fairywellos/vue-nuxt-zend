<?php

namespace Application\Service;

use Zend\Filter\File\Rename;
use Zend\Http\PhpEnvironment\Request;
use Zend\Stdlib\ErrorHandler;

class UploadManager
{
    /**
     * Main folders
     */
    const UPLOAD_FOLDER = 'uploads';
    const LISTING = 'listing';
    const USER = 'user';

    static $mainFolders = [
        self::LISTING,
        self::USER,
    ];

    static $fileUploadErrors = [
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    ];

    /**
     * @param string $mainFolder
     * @param array $tempFile
     * @throws \Exception
     * @return array
     */
    public function uploadFile($mainFolder, $tempFile)
    {
        $path = $this->getFilePath($mainFolder, basename($tempFile['name']));

        //Check if the directory already exists.
        if(!is_dir($path)){
            //Directory does not exist, so lets create it.
            mkdir($path, 0755, true);
        }

        $ext = pathinfo(basename($tempFile['name']), PATHINFO_EXTENSION);
        $filtredFile = $this->nameFilter(basename($tempFile['name'])) . '.' . $ext;

        ErrorHandler::start();
        $filter = new Rename([
            'target' => $path . $filtredFile,
            'randomize' => true,
            'use_upload_name' => true,
            'use_upload_extension' => true
        ]);

        $newFile = $filter->filter($tempFile);

        chmod($this->getFilePath($mainFolder, basename($newFile['tmp_name'])).basename($newFile['tmp_name']), 0644);
        ErrorHandler::stop(true);

        if($newFile['error']){
            throw new \Exception(self::$fileUploadErrors[$newFile['error']]??null);
        }

        $baseUrl = sprintf(
            "%s://%s/",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME']
        );

        $result = [
            'name' => basename($newFile['tmp_name']),
            'url' => $baseUrl . self::UPLOAD_FOLDER . '/' . $mainFolder . $this->generatePathFromFile(basename($newFile['tmp_name'])) . basename($newFile['tmp_name']),
            'type' => $newFile['type'],
            'size' => $newFile['size'],
        ];

        return $result;
    }

    /**
     * @param string $mainFolder
     * @param string $fileName
     * @throws \Exception
     * @return bool
     */
    public function deleteFile($mainFolder, $fileName){
        $fullPath = $this->getFilePath($mainFolder, $fileName) . basename($fileName);

        if (file_exists($fullPath)) {
            if(@unlink($fullPath) !== true)
                throw new \Exception('Could not delete file: ' . $fullPath . ' Please close all applications that are using it.');
        }

        return true;
    }

    /**
     * @param string $mainFolder
     * @param string $fileName
     * @return string
     */
    public function getFilePath($mainFolder, $fileName){
        return APPLICATION_PATH . '/' . self::UPLOAD_FOLDER . '/' . $mainFolder . $this->generatePathFromFile(basename($fileName));
    }

    /**
     * @param $mainFolder
     * @param $fileName
     * @return string
     */
    public function getFileUrl($mainFolder, $fileName)
    {
        $hostname = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'];

        return $hostname . '/' . self::UPLOAD_FOLDER . '/' . $mainFolder . $this->generatePathFromFile(basename($fileName)) . $fileName;
    }

    /**
     * @param $fileName
     * @param int $level
     * @param string $divider
     * @return string
     */
    public function generatePathFromFile($fileName, $level = 2, $divider = '/'){
        $pathArr = str_split($this->nameFilter($fileName, $level));
        $pathStr = implode($divider, $pathArr);
        return $divider.($pathStr ? $pathStr.$divider : '');
    }

    /**
     * @param $fileName
     * @param int $minLength
     * @return string
     */
    public function nameFilter($fileName, $minLength = 2){
        $filterPattern = "/[^a-z0-9\_\-]/i";
        $fileNameArr = explode('.', $fileName);
        $fileNameArr = array_shift($fileNameArr);
        $filtredName = preg_replace($filterPattern, '', $fileNameArr);
        $filtredName = substr($filtredName, 0, $minLength);

        if(strlen($filtredName) < $minLength){
            $restChrs = $minLength - strlen($filtredName);
            $filtredName = $filtredName . str_repeat('0', $restChrs);
        }

        return $filtredName;
    }

}