<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class Email
 * @package Application\Entity
 * @ORM\Entity
 * @ORM\Table(name="email",
 *     indexes={ @ORM\Index(name="sent_index",columns={"sent"}),
 *          @ORM\Index(name="priority_index",columns={"priority"}),
 *          @ORM\Index(name="date_created_index",columns={"date_created"}),
 *     }
 * )
 */
class Email
{
    const SENT = 2;
    const NOT_SENT = 1;

    const PRIORITY_LOW = 1;
    const PRIORITY_NORMAL = 2;
    const PRIORITY_HIGH = 3;
    const PRIORITY_URGENT = 99;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id",type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="template", type="string", length=50)
     */
    protected $template;

    /**
     * @ORM\Column(name="subject", type="string")
     */
    protected $subject;

    /**
     * @ORM\Column(name="to", type="string")
     */
    protected $to;


    /**
     * @ORM\Column(name="from", type="string")
     */
    protected $from;

    /**
     * @ORM\Column(name="from_name", type="string")
     */
    protected $fromName;

    /**
     * @ORM\Column(name="priority", type="integer")
     */
    protected $priority;

    /**
     * @ORM\Column(name="sent", type="boolean")
     */
    protected $sent;

    /**
     * @ORM\Column(name="tries", type="integer")
     */
    protected $tries;

    /**
     * @ORM\Column(name="date_created", type="datetimetz", options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateCreated;

    /**
     * @ORM\Column(name="date_success", type="datetimetz", nullable=true)
     */
    protected $dateSuccess;

    /**
     * @ORM\Column(name="date_updated", type="datetimetz",options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $dateUpdated;

    /**
     * @ORM\Column(name="data", type="text")
     */
    protected $data;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template): void
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo($to): void
    {
        $this->to = $to;
    }

    /**
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param integer $priority
     */
    public function setPriority($priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return integer
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * @param integer $sent
     */
    public function setSent($sent): void
    {
        $this->sent = $sent;
    }

    /**
     * @return integer
     */
    public function getTries()
    {
        return $this->tries;
    }

    /**
     * @param integer $tries
     */
    public function setTries($tries): void
    {
        $this->tries = $tries;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getDateSuccess()
    {
        return $this->dateSuccess;
    }

    /**
     * @param mixed $dateSuccess
     */
    public function setDateSuccess($dateSuccess): void
    {
        $this->dateSuccess = $dateSuccess;
    }

    /**
     * @return mixed
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @param mixed $dateUpdated
     */
    public function setDateUpdated($dateUpdated): void
    {
        $this->dateUpdated = $dateUpdated;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom($from): void
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param string $fromName
     */
    public function setFromName($fromName): void
    {
        $this->fromName = $fromName;
    }




}