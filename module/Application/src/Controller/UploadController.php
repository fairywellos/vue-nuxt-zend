<?php

namespace Application\Controller;

use Application\Form\UploadForm;
use Application\Service\UploadManager;
use RestApi\Controller\ApiController;
use Zend\ServiceManager\ServiceManager;

/**
 * Class UploadController
 *
 * @package Application\Controller
 */
class UploadController extends ApiController
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function indexAction()
    {
        $this->httpStatusCode = 200;

        // Set the response
        $this->apiResponse = [];

        return $this->createResponse();
    }

    public function uploadAction()
    {
        $form = new UploadForm();
        $mainFolder = $this->params()->fromRoute('main_folder');

        if ($this->getRequest()->isPost() and in_array($mainFolder, UploadManager::$mainFolders)) {
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();
                $resultFiles = [];

                if(!empty($data['file'])){
                    $tempFile = $data['file'];
                    //foreach ($data['file'] as $tempFile) {
                        try {
                            /**
                             * @var $uploadManager UploadManager
                             */
                            $uploadManager = $this->getServiceManager()->get(UploadManager::class);

                            $resultFiles = $uploadManager->uploadFile($mainFolder, $tempFile);
                            //$resultFiles[] = $uploadManager->uploadFile(UploadManager::LISTING, $tempFile);
                        } catch (\Exception $e) {
                            $resultFiles['error'] = $e->getMessage();
                            //$resultFiles[]['error'] = $e->getMessage();
                        }
                    //}
                }

                $this->httpStatusCode = 200;
                $this->apiResponse = $resultFiles;
            } else {
                $this->httpStatusCode = 400;
                $this->apiResponse = $form->getMessages()['file'] ?? [];
            }
        }else{
            $this->httpStatusCode = 400;
            $this->apiResponse = [];
        }

        return $this->createResponse();
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param mixed $serviceManager
     * @return $this
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}


