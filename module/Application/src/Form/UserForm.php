<?php

namespace Application\Form;


use Zend\Db\Adapter\Adapter;
use Zend\Filter\StripNewlines;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Db\NoRecordExists;

class UserForm extends Form
{
    private $config;

    public function __construct($config)
    {
        parent::__construct("user-form");
        $this->config = $config;
        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'type' => 'text',
            'name' => 'first_name'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'name'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'username'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'email'
        ]);

        $this->add([
            'type' => 'password',
            'name' => 'password'
        ]);
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        $adapter = new Adapter($this->config["db_settings"]);

        $inputFilter->add([
            'name' => 'first_name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 145,
                    ]
                ]
            ]
        ]);

        $inputFilter->add([
            'name' => 'username',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 145,
                    ]
                ]
            ]
        ]);

        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 145,
                    ]
                ]
            ]
        ]);

        $inputFilter->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
            ],
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ]
                ],
                [
                    'name' =>  NoRecordExists::class,
                    'options' => [
                        'table' => 'user',
                        'field' => 'email',
                        'adapter' => $adapter
                    ]
                ]
            ]
        ]);

        $inputFilter->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 255,
                    ]
                ]
            ]
        ]);
    }
}