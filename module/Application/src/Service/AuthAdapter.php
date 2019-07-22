<?php

namespace Application\Service;


use Application\Entity\User;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;

class AuthAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var
     */
    private $activationCode;

    /**
     * @var EntityManager
     */
    private $entityManager;


    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param mixed $activationCode
     */
    public function setActivationCode($activationCode): void
    {
        $this->activationCode = $activationCode;
    }

    public function activate()
    {
        $isActivationError = false;
        $codeMatched = false;
        /**
         * @var User
         */
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail($this->email);

        if ($user == null) {
            $isActivationError = true;
        }

        $currentDate =  new \DateTime(date('Y-m-d H:i:s'));
        $activationExpires = $user->getActivationExpires()->format('Y-m-d H:i:s');
        if ($user->getActivationCode() == $this->activationCode && $activationExpires >= $currentDate->format('Y-m-d H:i:s')){
            $codeMatched = true;
            $user->setStatus(User::ACTIVE);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        if(!$isActivationError && $codeMatched){
            return new Result(
                Result::SUCCESS,
                $user,
                ['Activation successfully.']);
        }

        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid code.']);
    }

    public function authenticate()
    {
        $isLoginError = false;
        $passwordMached = false;
        $not_activated = false;
        /**
         * @var User
         */
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail($this->email);

        if ($user == null) {
            $isLoginError = true;
        } else if ($user->getStatus() == User::INACTIVE ){
            $isLoginError = true;
        } else if ($user->getStatus() == User::NOT_ACTIVATED){
            $not_activated = true;
        }

        $bcrypt = new Bcrypt();
        $passwordHash = "no_user_hash";
        if($user){
            $passwordHash = $user->getPassword();
        }

        if ($bcrypt->verify($this->password,$passwordHash)){
            $passwordMached = true;
        }

        if($not_activated){
            return new Result(
                Result::FAILURE,
                null,
                ['Account not activated']);

        } else if (!$isLoginError && $passwordMached){

            return new Result(
                Result::SUCCESS,
                $user,
                ['Authenticated successfully.']);
        }

        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']);
    }

}