<?php

namespace Application\Controller;

use Application\Entity\Email;
use Application\Form\ContactForm;
use Application\Service\MailSmtp;
use RestApi\Controller\ApiController;
use Zend\View\Model\ViewModel;

class IndexController extends ApiController
{
    /**
     * @var MailSmtp
     */
    private $mailSmtp;

    /**
     * @var array
     */
    private $config;

    public function indexAction()
    {

        $this->httpStatusCode = 200;

        // Set the response
        $this->apiResponse['you_response'] = 'your response data';

        return $this->createResponse();
    }

    public function contactAction()
    {
        $data = $this->params()->fromPost();
        $form = new ContactForm($this->config);
        $form->setData($data);

        if($form->isValid()){
            $data = $form->getData();
            $data["fullName"] = $data["first_name"] . " " . $data["name"];

            $email = new Email();
            $email->setSent(Email::NOT_SENT);
            $email->setTemplate('email/contact');
            $email->setDateCreated( new \DateTime(date('Y-m-d H:i:s')));
            $email->setPriority(Email::PRIORITY_NORMAL);
            $email->setDateUpdated( new \DateTime(date('Y-m-d H:i:s')));
            $email->setSubject($this->config["contact_email_titles"][$data["form_type"]]);
            $email->setTo($this->config["server_emails"][$data["form_type"]]);
            $email->setTries(0);
            $email->setFrom($data["email"]);
            $email->setFromName($data["fullName"]);
            $email->setData(json_encode($data));

            $this->mailSmtp->sendMail($email);

            $this->apiResponse["message"] = "Mail sent";
        }else{
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Validation failed";
            $this->apiResponse["validation_messages"] = $form->getMessages();
        }


        return $this->createResponse();

    }

    public function setMailSmtp($mailSmtp)
    {
        $this->mailSmtp = $mailSmtp;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

}
