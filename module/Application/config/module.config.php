<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\Factory\AuthControllerFactory;
use Application\Controller\Factory\ControllerFactory;
use Application\Controller\Factory\ListingControllerFactory;
use Application\Controller\Factory\PluginFactory;
use Application\Controller\Factory\TradeControllerFactory;
use Application\Controller\Factory\UserControllerFactory;
use Application\Service\Factory\ControllerManagerFactory;
use Application\Service\Factory\MailSmtpFactory;
use Application\Service\Factory\ServiceFactory;
use Application\Service\Factory\PusherManagerFactory;
use Application\Service\Factory\RatingManagerFactory;
use Application\Service\Factory\TradeManagerFactory;
use Application\Service\UploadManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Method;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                        // 'isAuthorizationRequired' => true // set true if this api Required JWT Authorization.
                    ],
                ],
            ],
            'me' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/me',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'get-user',
                        'isAuthorizationRequired' => true
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'default' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'get',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'get-user',
                                'isAuthorizationRequired' => true
                            ]
                        ]
                    ]
                ]
            ],
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'login'
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'default' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'login'
                            ]
                        ]
                    ]
                ]
            ],
            'betaLogin' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/beta-login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'beta-login'
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'default' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'beta-login'
                            ]
                        ]
                    ]
                ]
            ],
            'logout' => [
                'type'  =>  Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'logout'
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'default' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'logout'
                            ]
                        ]
                    ]
                ]
            ],
            'register' => [
                'type'  =>  Literal::class,
                'options' => [
                    'route' => '/register',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'register'
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'default' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'register'
                            ]
                        ]
                    ]
                ]
            ],
            'activate' => [
                'type'  =>  Literal::class,
                'options' => [
                    'route' => '/activate',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'activate'
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'default' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'activate'
                            ]
                        ]
                    ]
                ]
            ],
            'reactivate' => [
                'type'  =>  Literal::class,
                'options' => [
                    'route' => '/resend-activation-code',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'resend-activation-code'
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'default' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\AuthController::class,
                                'action' => 'resend-activation-code'
                            ]
                        ]
                    ]
                ]
            ],
            'saved_search' => [
                'type'  =>  Literal::class,
                'options' => [
                    'route' => '/saved-search',
                    'defaults' => [
                        'controller' => Controller\SavedSearchController::class,
                        'action' => 'index',
                        'isAuthorizationRequired' => true,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'postSetSaved' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\SavedSearchController::class,
                                'action'     => 'set-saved'
                            ],
                        ],
                    ],
                    'getSaved' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'get',
                            'defaults' => [
                                'controller' => Controller\SavedSearchController::class,
                                'action'     => 'index'
                            ],
                        ],
                    ],
                ],
            ],
            'listing' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/listing',
                    'defaults' => [
                        'controller' => Controller\ListingController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'getListing' => [// This child route will match GET request
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'get',
                            'defaults' => [
                                'controller' => Controller\ListingController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'addListing' => [// This child route will match POST request
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\ListingController::class,
                                'action'     => 'add',
                                'isAuthorizationRequired' => true,
                            ],
                        ],
                    ],
                    'deleteListing' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/delete',
                            'defaults' => [
                                'controller' => Controller\ListingController::class,
                                'action'     => 'delete-listing',
                                'isAuthorizationRequired' => true,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'postRoute' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post',
                                    'defaults' => [
                                        'controller' => Controller\ListingController::class,
                                        'action'     => 'delete-listing',
                                        'isAuthorizationRequired' => true,
                                    ],
                                ],
                            ],
                        ]
                    ],
                    'setSaved' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/save',
                            'defaults' => [
                                'controller' => Controller\ListingController::class,
                                'action'     => 'set-saved',
                                'isAuthorizationRequired' => true,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'postSetSaved' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post',
                                    'defaults' => [
                                        'controller' => Controller\ListingController::class,
                                        'action'     => 'set-saved'
                                    ],
                                ],
                            ],
                        ]
                    ],
                    'repostListing' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/repost',
                            'defaults' => [
                                'controller' => Controller\ListingController::class,
                                'action'     => 'repost-listing',
                                'isAuthorizationRequired' => true,
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'postRoute' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post',
                                    'defaults' => [
                                        'controller' => Controller\ListingController::class,
                                        'action'     => 'repost-listing',
                                        'isAuthorizationRequired' => true,
                                    ],
                                ],
                            ],
                        ]
                    ],
                    'feed' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/feed',
                            'defaults' => [
                                'controller' => Controller\ListingController::class,
                                'action'     => 'feed',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'getListingFeed' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\ListingController::class,
                                        'action'     => 'feed'
                                    ],
                                ],
                            ],
                            'currentUser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/user',
                                    'defaults' => [
                                        'controller' => Controller\ListingController::class,
                                        'action'     => 'feed',
                                        'isAuthorizationRequired' => true
                                    ],
                                ],
                                'may_terminate' => false,
                                'child_routes' => [
                                    'getUserListings' => [
                                        'type' => Method::class,
                                        'options' => [
                                            'verb' => 'get',
                                            'defaults' => [
                                                'controller' => Controller\ListingController::class,
                                                'action'     => 'feed',
                                            ],
                                        ],
                                    ]
                                ]
                            ],
                            'saved' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/saved',
                                    'defaults' => [
                                        'controller' => Controller\ListingController::class,
                                        'action'     => 'feed',
                                        'isAuthorizationRequired' => true
                                    ],
                                ],
                                'may_terminate' => false,
                                'child_routes' => [
                                    'getSaved' => [
                                        'type' => Method::class,
                                        'options' => [
                                            'verb' => 'get',
                                            'defaults' => [
                                                'controller' => Controller\ListingController::class,
                                                'action'     => 'feed',
                                            ],
                                        ],
                                    ]
                                ]
                            ],
                        ]
                    ],
                    'tradeOffer' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/trade-offer',
                            'defaults' => [
                                'controller' => Controller\ListingController::class,
                                'action'     => 'trade-offer',
                                'isAuthorizationRequired' => true
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'addTradeOffer' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post',
                                    'defaults' => [
                                        'controller' => Controller\ListingController::class,
                                        'action'     => 'trade-offer'
                                    ],
                                ],
                            ],
                            'change-status' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/change-status',
                                    'defaults' => [
                                        'controller' => Controller\TradeController::class,
                                        'action'     => 'change-status'
                                    ],
                                ],
                                'may_terminate' => false,
                                'child_routes' => [
                                    'postRoute' => [// This child route will match POST request
                                        'type' => Method::class,
                                        'options' => [
                                            'verb' => 'post',
                                            'defaults' => [
                                                'controller' => Controller\TradeController::class,
                                                'action'     => 'change-status'
                                            ],
                                        ],
                                    ]
                                ]
                            ],
                        ]
                    ],
                    'trades' => [// This child route will match POST request
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/get-trades',
                            'defaults' => [
                                'controller' => Controller\TradeController::class,
                                'action'     => 'get-listing-trades',
                                'isAuthorizationRequired' => true
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'myroute1get' => [// This child route will match POST request
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\TradeController::class,
                                        'action'     => 'get-listing-trades'
                                    ],
                                ],
                            ]
                        ]
                    ],
                ],
            ],
            'find-trade-partners' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/find-trade-partners',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'find-trade-partners',
                        'isAuthorizationRequired' => true
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'get-trade-partners' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'find-trade-partners'
                            ],
                        ],
                    ]
                ]
            ],
            'main_category' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/main-category',
                    'defaults' => [
                        'controller' => Controller\MainCategoryController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'getList' => [// This child route will match GET request
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'get',
                            'defaults' => [
                                'controller' => Controller\MainCategoryController::class,
                                'action'     => 'index'
                            ],
                        ],
                    ],
                ],
            ],
            'follow' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/follow',
                    'defaults' => [
                        'isAuthorizationRequired' => true
                    ]
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'following' => [// This child route will match POST request
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/following',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'following'
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'get' => [// This child route will match POST request
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post',
                                    'defaults' => [
                                        'controller' => Controller\UserController::class,
                                        'action'     => 'following',
                                    ],
                                ],
                            ]
                        ]
                    ],
                    'unfollow' => [// This child route will match POST request
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/unfollow',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'unfollow'
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'get' => [// This child route will match POST request
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post',
                                    'defaults' => [
                                        'controller' => Controller\UserController::class,
                                        'action'     => 'unfollow',
                                    ],
                                ],
                            ]
                        ]
                    ],
                    'getUserTradePartners' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/get-trade-partners',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action'     => 'get-trade-partners'
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'get' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\UserController::class,
                                        'action'     => 'get-trade-partners',
                                    ],
                                ],
                            ]
                        ]
                    ]
                ],
            ],
            'tag' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/tag',
                    'defaults' => [
                        'controller' => Controller\TagController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'search' => [// This child route will match POST request
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/search',
                            'defaults' => [
                                'controller' => Controller\TagController::class,
                                'action'     => 'search'
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'get' => [// This child route will match POST request
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\TagController::class,
                                        'action'     => 'search',
                                        'isAuthorizationRequired' => true
                                    ],
                                ],
                            ]
                        ]
                    ]
                ],
            ],
            'conversation' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/conversation',
                    'defaults' => [
                        'controller' => Controller\ConversationController::class,
                        'action'     => 'index',
                        'isAuthorizationRequired' => true,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'getConversation' => [// This child route will match GET request
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'get',
                            'defaults' => [
                                'controller' => Controller\ConversationController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'addMessage' => [// This child route will match POST request
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\ConversationController::class,
                                'action'     => 'add-message',
                            ],
                        ],
                    ],
                    'getConversations' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/all',
                            'defaults' => [
                                'controller' => Controller\ConversationController::class,
                                'action'     => 'get-all',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'getRoute' => [// This child route will match GET request
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\ConversationController::class,
                                        'action'     => 'get-all'
                                    ],
                                ],
                            ]
                        ]
                    ],
                    'getNotificationConversations' => [// This child route will match POST request
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/get-notification-conversations',
                            'defaults' => [
                                'controller' => Controller\ConversationController::class,
                                'action'     => 'get-notification-conversations',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'getRoute' => [// This child route will match POST request
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\ConversationController::class,
                                        'action'     => 'get-notification-conversations'
                                    ],
                                ],
                            ]
                        ]
                    ],
                    'updateConversationStatus' => [// This child route will match POST request
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/update-status',
                            'defaults' => [
                                'controller' => Controller\ConversationController::class,
                                'action'     => 'update-status',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'getRoute' => [// This child route will match POST request
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post',
                                    'defaults' => [
                                        'controller' => Controller\ConversationController::class,
                                        'action'     => 'update-status'
                                    ],
                                ],
                            ]
                        ]
                    ],
                ],
            ],
            'browse_location' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/browse-local',
                    'defaults' => [
                        'controller' => Controller\BrowseLocalController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'postRoute' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\BrowseLocalController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'getNearListings' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/all',
                            'defaults' => [
                                'controller' => Controller\BrowseLocalController::class,
                                'action'     => 'get-all',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'getRoute' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\BrowseLocalController::class,
                                        'action'     => 'get-all'
                                    ],
                                ],
                            ]
                        ]
                    ],
                    'getDefaultListing' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/get-default',
                            'defaults' => [
                                'controller' => Controller\BrowseLocalController::class,
                                'action'     => 'get-default',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'getRoute' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\BrowseLocalController::class,
                                        'action'     => 'get-default'
                                    ],
                                ],
                            ]
                        ]
                    ],
                ],
            ],
            'notification' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/notification',
                    'defaults' => [
                        'controller' => Controller\NotificationController::class,
                        'action'     => 'index',
                        'isAuthorizationRequired' => true,
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'getNotification' => [// This child route will match GET request
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'get',
                            'defaults' => [
                                'controller' => Controller\NotificationController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'getAllNotifications' => [// This child route will match POST request
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/all',
                            'defaults' => [
                                'controller' => Controller\NotificationController::class,
                                'action'     => 'get-all',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'getRoute' => [// This child route will match GET request
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\NotificationController::class,
                                        'action'     => 'get-all'
                                    ],
                                ],
                            ]
                        ]
                    ],
                    'updateNotificationStatus' => [// This child route will match POST request
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/update-status',
                            'defaults' => [
                                'controller' => Controller\NotificationController::class,
                                'action'     => 'update-status',
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'getRoute' => [// This child route will match POST request
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post',
                                    'defaults' => [
                                        'controller' => Controller\NotificationController::class,
                                        'action'     => 'update-status'
                                    ],
                                ],
                            ]
                        ]
                    ],
                ],
            ],
            'upload' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/upload',
                    'defaults' => [
                        'controller' => Controller\UploadController::class,
                        'action'     => 'index',
                        //'isAuthorizationRequired' => true @todo trebuie rezolvat bugul, nu se face uploadul daca este activa autorizatia
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'uploadAction' => [// This child route will match POST request
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/:main_folder',
                            'defaults' => [
                                'controller' => Controller\UploadController::class,
                                'action' => 'upload',
                                'main_folder' => '('.implode('|', UploadManager::$mainFolders).')',
                            ],
                        ],
                        'child_routes' => [
                            'uploadAction' => [// This child route will match POST request
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post',
                                    'defaults' => [
                                        'controller' => Controller\UploadController::class,
                                        'action'     => 'upload'
                                    ],
                                ],
                            ]
                        ],
                    ]
                ],
            ],
            'user_trade' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/trades',
                    'defaults' => [
                        'controller' => Controller\TradeController::class,
                        'action' => 'index',
                        'isAuthorizationRequired' => true
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'get_trade'=> [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/get-trade',
                            'action' => 'index',
                        ],
                        'may_terminate' => false,
                        'child_routes' =>[
                            'default' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\TradeController::class,
                                        'action' => 'index',
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'trade_offers' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/get-trade-offers',
                            'action' => 'get-user-trade-offers',
                        ],
                        'may_terminate' => false,
                        'child_routes' =>[
                            'default' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\TradeController::class,
                                        'action' => 'get-user-trade-offers',
                                    ]
                                ]
                            ]
                        ]

                    ],
                    'accepted_trades '=> [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/get-accepted-trades',
                            'action' => 'get-user-accepted-trades',
                        ],
                        'may_terminate' => false,
                        'child_routes' =>[
                            'default' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\TradeController::class,
                                        'action' => 'get-user-accepted-trades',
                                    ]
                                ]
                            ]
                        ]

                    ],
                ]
            ],
            'user' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/user',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'get_route' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'get',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'index',
                            ]
                        ]
                    ],
                    'post_route' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\UserController::class,
                                'action' => 'update-user',
                                'isAuthorizationRequired' => true,
                            ]
                        ]
                    ],
                    'review' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/review',
                            'defaults' => [
                                'isAuthorizationRequired' => true
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' =>[
                            'post_route' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post',
                                    'defaults' => [
                                        'controller' => Controller\UserController::class,
                                        'action' => 'add-review',
                                    ]
                                ]
                            ]
                        ]

                    ],
                    'profile' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/profile',
                        ],
                        'may_terminate' => false,
                        'child_routes' =>[
                            'get_route' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\UserController::class,
                                        'action' => 'get-user-profile',
                                    ]
                                ]
                            ]
                        ]

                    ],
                    'my-profile' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/my-profile',
                            'defaults' => [
                                'isAuthorizationRequired' => true
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' =>[
                            'get_route' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\UserController::class,
                                        'action' => 'get-user-profile',
                                    ]
                                ]
                            ]
                        ]

                    ],
                    'update-privacy' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/update-privacy',
                            'defaults' => [
                                'isAuthorizationRequired' => true
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' =>[
                            'post_route' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post',
                                    'defaults' => [
                                        'controller' => Controller\UserController::class,
                                        'action' => 'update-privacy',
                                    ]
                                ]
                            ]
                        ]

                    ],
                    'update-public' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/update-public',
                            'defaults' => [
                                'isAuthorizationRequired' => true
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' =>[
                            'post_route' => [
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'post',
                                    'defaults' => [
                                        'controller' => Controller\UserController::class,
                                        'action' => 'update-public',
                                    ]
                                ]
                            ]
                        ]

                    ],

                ]
            ],
            'location' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/location',
                    'defaults' => [
                        'controller' => Controller\LocationController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'getRoute' => [// This child route will match POST request
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'get',
                            'defaults' => [
                                'controller' => Controller\LocationController::class,
                                'action'     => 'get-location'
                            ],
                        ],
                    ],
                    'search' => [// This child route will match POST request
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/search',
                            'defaults' => [
                                'controller' => Controller\LocationController::class,
                                'action'     => 'search'
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'get' => [// This child route will match POST request
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\LocationController::class,
                                        'action'     => 'search'
                                    ],
                                ],
                            ]
                        ]
                    ],
                    'all' => [// This child route will match POST request
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/all',
                            'defaults' => [
                                'controller' => Controller\LocationController::class,
                                'action'     => 'index'
                            ],
                        ],
                        'may_terminate' => false,
                        'child_routes' => [
                            'get' => [// This child route will match POST request
                                'type' => Method::class,
                                'options' => [
                                    'verb' => 'get',
                                    'defaults' => [
                                        'controller' => Controller\LocationController::class,
                                        'action'     => 'index'
                                    ],
                                ],
                            ]
                        ]
                    ]
                ],
            ],
            'contact' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/contact',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'contact',
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'postRoute' => [
                        'type' => Method::class,
                        'options' => [
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'contact',
                            ]
                        ]
                    ]
                ]
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => ControllerFactory::class,
            Controller\BrowseLocalController::class => ControllerFactory::class,
            Controller\ListingController::class => ListingControllerFactory::class,
            Controller\AuthController::class => AuthControllerFactory::class,
            Controller\UserController::class => UserControllerFactory::class,
            Controller\UploadController::class => ControllerFactory::class,
            Controller\MainCategoryController::class => ControllerFactory::class,
            Controller\TagController::class => ControllerFactory::class,
            Controller\ConversationController::class => ControllerFactory::class,
            Controller\NotificationController::class => ControllerFactory::class,
            Controller\TradeController::class => TradeControllerFactory::class,
            Controller\SavedSearchController::class => ControllerFactory::class,
            Controller\LocationController::class => ControllerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\CurrentUserPlugin::class => PluginFactory::class,
            Controller\Plugin\IsDevClientPlugin::class => PluginFactory::class,
        ],
        'aliases' => [
            'currentUser' => Controller\Plugin\CurrentUserPlugin::class,
            'isDevClient' => Controller\Plugin\IsDevClientPlugin::class,
        ]
    ],
    'service_manager' => [
        'factories' => [
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
            Service\ListingManager::class => Service\Factory\ListingManagerFactory::class,
            Service\UploadManager::class => InvokableFactory::class,
            Service\MailSmtp::class => MailSmtpFactory::class,
            Service\TradeManager::class => TradeManagerFactory::class,
            Service\ConversationManager::class => ControllerManagerFactory::class,
            Service\NotificationManager::class => ControllerManagerFactory::class,
            Service\PusherManager::class => PusherManagerFactory::class,
            Service\RatingManager::class => RatingManagerFactory::class,
            Service\SavedSearchManager::class => ServiceFactory::class,
        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ]
];
