<?php

namespace Application\Form;


use Application\Entity\Listing;
use Zend\Db\Sql\Select;
use Zend\Filter\StripNewlines;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\InArray;

class ListingForm extends Form
{

    public function __construct()
    {
        parent::__construct("user-form");

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        $this->add([
            'type' => 'text',
            'name' => 'id'
        ]);

        $this->add([
            'type' => Element\Select::class,
            'name' => 'main_category_id',
            'options' => [
                'value_options' => [
                    //@todo de populat din DB
                    1 => 'Cat 1',
                    2 => 'Cat 2',
                    3 => 'Cat 3',
                    4 => 'Cat 4',
                    5 => 'Cat 5',
                    6 => 'Cat 5',
                    7 => 'Cat 5',
                    8 => 'Cat 5',
                    9 => 'Cat 5',
                    10 => 'Cat 5',
                ],
            ],
        ]);

        $this->add([
            'type' => Element\Select::class,
            'name' => 'listingType',
            'options' => [
                'value_options' => [
                    Listing::TYPE_GENERAL => 'General',
                    Listing::TYPE_ITEM => 'Item',
                    Listing::TYPE_SERVICE => 'Service',
                    Listing::TYPE_DEAL => 'Deal',
                ],
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'title'
        ]);

        $this->add([
            'type' => Element\Textarea::class,
            'name' => 'description'
        ]);

        $this->add([
            'type' => Element\Textarea::class,
            'name' => 'notes'
        ]);

        $this->add([
            'type' => "text",
            'name' => 'year'
        ]);

        $this->add([
            'type' => Element\Textarea::class,
            'name' => 'available_date'
        ]);

        $this->add([
            'type' => "text",
            'name' => 'meta_tags'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'price'
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'qty'
        ]);

        $this->add([
            'type' => Element\Select::class,
            'name' => 'condition',
            'options' => [
                'value_options' => [
                    0 => "",
                    Listing::USED => 'Used',
                    Listing::NEW => 'New',
                ],
                'empty_option' => '',
            ],
        ]);

        $this->add([
            'name' => 'localTradesOnly',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ]);

        $this->add([
            'type' => Element\MultiCheckbox::class,
            'name' => 'tradeOrCash',
            'options' => [
                'value_options' => [
                    Listing::TRADE_OFFER => 'Trade',
                    Listing::CASH_OFFER => 'Cash',
                    Listing::TRADE_AND_CASH_OFFER => 'Trade and cash'
                ],
            ]
        ]);

        $this->add([
            'name' => 'shipping',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'location',
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'listing_tags',
            'options' => [
                'isArray' => true,
            ]
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'brand',
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'color',
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'material',
        ]);

        $images = new Element\Collection('photos');
        $images->setTargetElement(new ListingImagesFieldSet('photos'));
        $images->setShouldCreateTemplate(true);
        $images->setAllowAdd(true);
        $this->add($images);

        $images = new Element\Collection('unlink_photos');
        $images->setTargetElement(new ListingImagesFieldSet('unlink_photos'));
        $images->setShouldCreateTemplate(true);
        $images->setAllowAdd(true);
        $this->add($images);
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'id',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'Digits',
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'main_category_id',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'Digits',
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'listingType',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'Digits',
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'title',
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
                        'min' => 3,
                        'max' => 150,
                    ]
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'year',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 0,
                        'max' => 150,
                    ]
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'meta_tags',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 0,
                        'max' => 150,
                    ]
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'available_date',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 0,
                        'max' => 150,
                    ]
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'description',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 3,
                        'max' => 600,
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'notes',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 0,
                        'max' => 600,
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'price',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'IsFloat',
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'qty',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'Digits',
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'condition',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'localTradesOnly',
            'required' => false,
            'continue_if_empty' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'InArray',
                    'options' => [
                        'haystack' => [Listing::GLOBAL_TRADE, Listing::LOCAL_TRADE],
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'tradeOrCash',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'shipping',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'InArray',
                    'options' => [
                        'haystack' => [Listing::NOT_SHIPPING, Listing::SHIPPING],
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'location',
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
                        'max' => 150,
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'listing_tags',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 3,
                        'max' => 150,
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'material',
            'required' => false,
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
                        'max' => 150,
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'color',
            'required' => false,
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
                        'max' => 150,
                    ]
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'brand',
            'required' => false,
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
                        'max' => 150,
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'photos',
            'required' => false,
        ]);

        $inputFilter->add([
            'name' => 'unlink_photos',
            'required' => false,
        ]);


    }

}