<?php

namespace Application\Service;

use Application\Entity\Email;
use Doctrine\ORM\EntityManager;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\View\Model\ViewModel;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
Use Zend\Mime;
use Zend\View\Renderer\RendererInterface;

class MailSmtp
{

    private $config;
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var RendererInterface
     */
    private $renderer;

    public function __construct($entityManager, $config, $renderer)
    {
        $this->entityManager = $entityManager;
        $this->config = $config;
        $this->renderer = $renderer;
    }

    public function sendMail(Email $email, $attachs = false)
    {

        // Template content
        $viewContent = new ViewModel(json_decode($email->getData(),true));
        $viewContent->setTemplate($email->getTemplate()); // set in module.config.php
        $content = $this->renderer->render($viewContent);

        //TODO: email layout
//        $viewLayout = new ViewModel(array('content' => $content, 'link' => $footer_link));
//        $viewLayout->setTemplate('email/layout'); // set in module.config.php

        $html = new MimePart($content);
        $html->type = "text/html";
        $body = new MimeMessage();
        if ($attachs && is_array($attachs)) {
            foreach ($attachs as $attach) {
                if ($attach['file']) {
                    $attachment = new MimePart(file_get_contents($attach['file']));
                    $attachment->type = $attach['file_type'];
                    $attachment->filename = $attach['filename'];
                    $attachment->encoding = Mime\Mime::ENCODING_BASE64;
                    $attachment->disposition = Mime\Mime::DISPOSITION_ATTACHMENT;
                    $body->addPart($attachment);
                }
            }

        }
        $body->addPart($html);


        $transport = new Smtp();
        $config = $this->getSettings();
        $options = new SmtpOptions($config);
        $transport->setOptions($options);

        $message = new Message();
        $message->setBody($body);


        $message->addTo($email->getTo())
            ->setBody($body)
            ->setSubject($email->getSubject())
            ->setFrom($email->getFrom(), $email->getFromName());

        try {
            $transport->send($message);
            if ($email->getId() && $email->getId() > 0) {
                $email->setDateSuccess( new \DateTime(date('Y-m-d H:i:s')));
                $email->setSent(Email::SENT);
                $this->entityManager->persist($email);
                $this->entityManager->flush();
            }
        } catch (\Exception $e) {
            $this->entityManager->persist($email);
            $this->entityManager->flush();

        }

    }

    private function getSettings()
    {

        return $this->config["smtp_options"];
    }

    private function getFrom($config)
    {
        return $config["connection_config"]["username"];
    }

}