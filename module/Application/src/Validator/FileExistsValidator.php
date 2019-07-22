<?php

namespace Application\Validator;

use Application\Service\UploadManager;
use Zend\Validator\AbstractValidator;

class FileExistsValidator extends AbstractValidator {

    /**
     * @const string Error constants
     */
    const DOES_NOT_EXIST = 'fileExistsDoesNotExist';
    const MISSING_MAIN_FOLDER = 'missingMainFolder';

    /**
     * @var array Error message templates
     */
    protected $messageTemplates = [
        self::DOES_NOT_EXIST => "File does not exist",
        self::MISSING_MAIN_FOLDER => "Missing main folder",
    ];

    public function isValid($value)
    {
        try {
            $mainFolder = $this->getOption('main_folder');
        }catch (\Exception $e){
            $this->error(self::MISSING_MAIN_FOLDER);
            return false;
        }

        $uploadManager = new UploadManager();
        $path = $uploadManager->getFilePath($mainFolder, basename($value));

        if (!file_exists($path.basename($value))) {
            $this->error(self::DOES_NOT_EXIST);
            return false;
        }

        return true;
    }
}