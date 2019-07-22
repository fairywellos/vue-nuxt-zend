<?php

namespace Application\Form;


use Application\Entity\Listing;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class UploadForm extends Form
{

    public function __construct()
    {
        parent::__construct("upload-form");

        // Set POST method for this form
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'type'  => Element\File::class,
            'name' => 'file',
            'options' => [
                'multiple' => true,
            ],
        ]);
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name'       =>  'file',
            'required'   =>  true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
                //['name' => 'filelowercase'],
            ],
            'validators' => [
                [
                    'name' => 'filemimetype',
                    'options' => [
                        'mimeType' => 'image/jpeg,image/png,image/x-png'
                    ]
                ],
                [
                    'name' => 'filesize',
                    'options' => [
                        //@todo 'min' => '',
                        'max' => '204800',
                    ]
                ]
            ]
        ]);

    }
}