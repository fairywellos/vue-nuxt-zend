<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

function deg($text, $exit = false) {
    echo '<pre>';
    print_r($text);
    echo '</pre>';
    if ($exit) {
        exit;
    }
}

return [
    'doctrine' => [
        // migrations configuration
        'migrations_configuration' => [
            'orm_default' => [
                'directory' => 'data/Migrations',
                'name'      => 'Doctrine Database Migrations',
                'namespace' => 'Migrations',
                'table'     => 'migrations',
            ],
        ],
    ],
    "contact_email_titles" =>[
        "customer_service"  => "Contact: Customer Service",
        "product_feedback"  => "Contact: Product Feedback",
        "partner_relations" => "Contact: Partner Relations",
    ]
];
