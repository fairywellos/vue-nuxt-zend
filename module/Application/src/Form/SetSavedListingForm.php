<?php

namespace Application\Form;

use Application\Entity\Listing;
use Zend\Form\Form;
use Zend\Form\Element;

class SetSavedListingForm extends Form
{

    public function __construct()
    {
        parent::__construct();

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
    }

    public function addElements()
    {
        $this->add([
            'type'   =>  'text',
            'name'   =>  'listing_id'
        ]);
        $this->add([
            'type' => Element\Radio::class,
            'name' => 'status',
            'options' => [
                'value_options' => [
                    Listing::REMOVE_SAVED => 'Remove',
                    Listing::ADD_SAVED => 'Add',
                ],
            ]
        ]);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'listing_id' => [
                'required'   =>  true,
                'validators' => [
                    [
                        'name' => 'Digits',
                    ],
                ],
            ],
        ];
    }
}