<?php

namespace Application\Controller;



use Application\Entity\Email;
use Application\Entity\User;
use Application\Form\LoginForm;
use Application\Form\UserForm;
use Application\Service\AuthAdapter;
use Application\Service\MailSmtp;
use Application\Service\UserManager;
use Doctrine\ORM\EntityManager;
use RestApi\Controller\ApiController;
use Zend\Authentication\Result;

class AuthController extends ApiController
{

    /**
     * Entity manager
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var MailSmtp
     */
    private $mailSmtp;

    private $config;

    public function __construct($entityManager,$userManager,$config,$mailSmtp)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->config = $config;
        $this->mailSmtp = $mailSmtp;
        $str = "Hello\n";
 
        printf($str);
    }

    public function loginAction()
    {

        $form = new LoginForm();
        $data = $this->params()->fromPost();

        $form->setData($data);
        if($form->isValid()){
            $data = $form->getData();
            $authAdapter = new AuthAdapter($this->entityManager);
            $authAdapter->setPassword($data["password"]);
            $authAdapter->setEmail($data["email"]);
            $result =$authAdapter->authenticate();

            if ($result->getCode()==Result::SUCCESS){
                /** @var User $user */
                $user = $result->getIdentity();
                // generate token if valid user
                $payload = $user->getAuthInfo();

                $this->httpStatusCode = 200;
                $this->apiResponse['token'] = $this->generateJwtToken($payload);
                $this->apiResponse['message'] = 'Logged in successfully.';
            }elseif ($result->getCode()==Result::FAILURE){
                $this->httpStatusCode = 403;
                $this->apiResponse["message"] = $result->getMessages();
            }else{
                $this->httpStatusCode = 400;
                $this->apiResponse["message"] = $result->getMessages();
            }
        }else{
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Validation failed";
            $this->apiResponse["validation_messages"] = $form->getMessages();
        }

        return $this->createResponse();

    }

    public function registerAction()
    {
        $this->httpStatusCode = 200;
        
        
        $form = new UserForm($this->config);
        $data = $this->params()->fromPost();
        
        
        
        $form->setData($data);
        // $str = "register --------------\n";

        // try {
        //     var_dump("++++++++++++++++++");
        //     var_dump($form->isValid());
        // } catch (\Exception $e) {
        //     error_log("Caught ---------------- $e");
        // }
        if($form->isValid()){
            $data = $form->getData();
            try{
                $user =  $this->userManager->addNewUser($data);
            }catch (\Exception $exception){
                $this->httpStatusCode = 500;
                $this->apiResponse["message"] = "Something went wrong";
                if($_SERVER['APPLICATION_ENV'] === 'development' || $this->isDevClient()){
                    $this->apiResponse["error_message"] = $exception->getMessage();
                }
                return $this->createResponse();
            }
            $this->sendActivationEmail($user);
            $this->httpStatusCode = 201;
            $this->apiResponse["message"] = "User added successfully";
        }else{
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Validation failed";
            $this->apiResponse["validation_messages"] = $form->getMessages();
        }


        return $this->createResponse();
    }

    public function activateAction()
    {
        $this->httpStatusCode = 200;
        $data = $this->params()->fromPost();

        if(isset($data["code"]) && isset($data["email"])){
            $authAdapter = new AuthAdapter($this->entityManager);
            $authAdapter->setEmail($data["email"]);
            $authAdapter->setActivationCode($data["code"]);
            $result =$authAdapter->activate();

            if ($result->getCode()==Result::SUCCESS){
                $this->httpStatusCode = 200;
                $this->apiResponse['message'] = 'Account activated.';
            }else{
                $this->httpStatusCode = 400;
                $this->apiResponse["message"] = $result->getMessages();
            }
        }else{
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "No activation code received";
        }

        return $this->createResponse();
    }

    public function resendActivationCodeAction()
    {
        $this->httpStatusCode = 200;
        $data = $this->params()->fromPost();

        if(isset($data["email"])){
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($data["email"]);
            if($user){
                $user = $this->userManager->regenerateUserActivationCode($user);
                $this->sendActivationEmail($user);
            }
            $this->apiResponse["message"] = "If any matching record was found a new activation code was sent to " . $data["email"];
        }else{
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "No user email received";
        }

        return $this->createResponse();
    }

    public function logoutAction()
    {
        $this->httpStatusCode = 200;
        $this->apiResponse["message"] = "User logged out";
        return $this->createResponse();
    }

    public function betaLoginAction()
    {
        $data = $this->params()->fromPost();
        if(!empty($data["username"]) && !empty($data["password"])){
            if($data["username"] == "Tradelist" && $data["password"] == "Beta2019!"){
                $this->httpStatusCode = 200;
                $this->apiResponse["message"] = "Logged in";
            }else{
                $this->httpStatusCode = 403;
                $this->apiResponse["message"] = "Invalid credentials";
            }

        }else{
            $this->httpStatusCode = 400;
            $this->apiResponse["message"] = "Please fill both inputs in order to login";
        }

        return $this->createResponse();

    }

    private function sendActivationEmail(User $user)
    {
        $email = new Email();
        $email->setSent(Email::NOT_SENT);
        $email->setTemplate('email/activate-account');
        $email->setDateCreated( new \DateTime(date('Y-m-d H:i:s')));
        $email->setPriority(Email::PRIORITY_URGENT);
        $email->setDateUpdated( new \DateTime(date('Y-m-d H:i:s')));
        $email->setSubject("Activate Account");
        $email->setTo($user->getEmail());
        $email->setFrom($this->config["smtp_options"]["connection_config"]["username"]);
        $email->setFromName($this->config["smtp_options"]["name"]);
        $email->setTries(0);
        $data = [
            "fullName" => $user->getFirstName() ?? "User" . $user->getId(),
            "code" => $user->getActivationCode()
        ];
        $email->setData(json_encode($data));

        $this->mailSmtp->sendMail($email);
    }

}