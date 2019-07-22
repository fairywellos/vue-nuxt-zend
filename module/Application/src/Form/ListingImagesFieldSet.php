<?php

namespace Application\Form;

use Application\Service\UploadManager;
use Application\Validator\FileExistsValidator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\InArray;

class ListingImagesFieldSet extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($name)
    {
        parent::__construct($name);

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'order',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'main',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'id' => [
                'required' => false,
                'continue_if_empty' => true,
            ],
            'name' => [
                'required' => true,
                'filters'    =>  [
                    ['name'  =>  'StringTrim'],
                    ['name'  =>  'StripTags'],
                ],
                'validators' => [
                    [
                        'name' => FileExistsValidator::class,
                        'options' => [
                            'main_folder' => UploadManager::LISTING
                        ]
                    ],
                ],
            ],
            'order' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'Digits',
                    ],
                ],
            ],
            'main' => [
                'required' => false,
                'continue_if_empty' => true,
            ],
        ];
    }
}