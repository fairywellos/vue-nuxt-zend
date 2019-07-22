<?php

namespace Application\Form;

use Application\Entity\SavedSearch;
use Zend\Form\Form;
use Zend\Form\Element;

class SetSavedSearchForm extends Form
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
            'name'   =>  'query'
        ]);
        $this->add([
            'type' => Element\Radio::class,
            'name' => 'status',
            'options' => [
                'value_options' => [
                    SavedSearch::REMOVE_SAVED => 'Remove',
                    SavedSearch::ADD_SAVED => 'Add',
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
        ];
    }
}