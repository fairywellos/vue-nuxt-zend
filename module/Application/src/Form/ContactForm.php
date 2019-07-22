<?php

namespace Application\Form;

use Zend\Filter\StripNewlines;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element;


class ContactForm extends Form
{

    private $config;

    public function __construct($config)
    {
        parent::__construct("contact-form");
        $this->setAttribute('method', 'post');
        $this->config = $config;

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
            'name' => 'email'
        ]);


        $this->add([
            'type' => 'text',
            'name' => 'phone_number'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'company'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'message'
        ]);

        $this->add([
            'type' => Element\Select::class,
            'name' => 'form_type',
            'options' => [
                'value_options' =>$this->config["contact_email_titles"]
            ],
        ]);


    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

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
            ]
        ]);

        $inputFilter->add([
            'name' => 'company',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'phone_number',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'message',
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
                        'max' => 500,
                    ]
                ]
            ]
        ]);

    }

}