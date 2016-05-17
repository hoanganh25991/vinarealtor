<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'BackEnd\Controller\Index' => 'BackEnd\Controller\IndexController',
//            'BackEnd\Controller\District' => 'BackEnd\Controller\DistrictController',
        ),
        "factories" => array(
            'BackEnd\Controller\Index' => 'BackEnd\Controller\IndexController',
            'BackEnd\Controller\Province' => 'BackEnd\Factory\ProvinceControllerFactory',
            'BackEnd\Controller\District' => 'BackEnd\Factory\DistrictControllerFactory',      
            'BackEnd\Controller\Ward' => 'BackEnd\Factory\WardControllerFactory',
        )
    ),
    'router' => array(
        'routes' => array(
            'backend' => array(
                'type' => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route' => '/backend',
                    'defaults' => array(
                        'controller' => 'Backend\Controller\Index',
                        'action' => 'index',
                    ),
                ),
//                'may_terminate' => true,
//                'child_routes' => array(
//                     'default' => array(
//                        'type'    => 'Segment',
//                        'options' => array(
//                            'route'    => '/province/index',
//                            'constraints' => array(
//                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
////                                'id'     => '[0-9]+',
////                                'page'     => '[0-9]+',
////                                'title'     => '.+',
////                                'type'     => '[a-zA-Z0-9_-]*',
////                                'col'     => '.+',
////                                'redirect' => '[a-zA-Z][a-zA-Z0-9_-]*',
////                                'ntype'    	=> '[0-9]+',
//                            ),
//                            'defaults' => array(
//                            ),
//                        ),
//                    ),                  
//                ),
            ),
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'Requesthelper' => 'BackEnd\View\Helper\Factory\RequestHelperFactory',
        )
    ),
    
    // ViewManager configuration
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
//        'not_found_template' => 'error/404',
//        'exception_template' => 'error/index',
        // Doctype with which to seed the Doctype helper
        'doctype' => 'HTML5',
        // e.g. HTML5, XHTML1
        // Layout template name
        'layout' => \BackEnd\Module::LAYOUT,
        // e.g. 'layout/layout'
        // TemplateMapResolver configuration
        // template/path pairs
        'template_map' => array(
            \BackEnd\Module::LAYOUT => __DIR__ . '/../view/layout/backend.phtml',
//            'error/404' => __DIR__ . '/../view/error/404.phtml',
//            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'province_add_template' => __DIR__ . '/../view/back-end/province/add.phtml',
            'district_add_template' => __DIR__ . '/../view/back-end/district/add.phtml',
            'ward_add_template' => __DIR__ . '/../view/back-end/ward/add.phtml',
            'test_pages'=>__DIR__ . '/../view/pager.phtml',
        ),
        // TemplatePathStack configuration
        // module/view script path pairs
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        // Additional strategies to attach
        // These should be class names or service names of View strategy classes
        // that act as ListenerAggregates. They will be attached at priority 100,
        // in the order registered.
        'strategies' => array(
            'ViewJsonStrategy',
            // register JSON renderer strategy
            'ViewFeedStrategy',
        // register Feed renderer strategy
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'adapter' => function($sm) {
                /** @var \Zend\ServiceManager\ServiceManager $sm */
                $config = $sm->get('config');
                return new \Zend\Db\Adapter\Adapter($config['db']);
            }
        ),
    ),
);
