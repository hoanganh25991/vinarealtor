<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'BackEnd\Controller\Index' => 'BackEnd\Controller\IndexController',
            //'BackEnd\Controller\District' => 'BackEnd\Controller\DistrictController',
//            'BackEnd\Controller\IlluminateDatabase' => 'BackEnd\Controller\IlluminateDatabaseController',
            //            'BackEnd\Controller\PostImages'=>'BackEnd\Controller\PostImagesController',
        ),
        "factories" => array(
            'BackEnd\Controller\Province' => 'BackEnd\Factory\ProvinceControllerFactory',
            'BackEnd\Controller\District' => 'BackEnd\Factory\DistrictControllerFactory',
            'BackEnd\Controller\Ward' => 'BackEnd\Factory\WardControllerFactory',
            'BackEnd\Controller\Category' => 'BackEnd\Factory\CategoryControllerFactory',
            'BackEnd\Controller\Post' => 'BackEnd\Factory\PostControllerFactory',
            'BackEnd\Controller\PostImages' => 'BackEnd\Factory\PostImagesControllerFactory',
            'BackEnd\Controller\PostFeature' => 'BackEnd\Factory\PostFeatureControllerFactory',
            'BackEnd\Controller\Test' => 'BackEnd\Factory\TestControllerFactory',
            'BackEnd\Controller\Auth' => 'BackEnd\Factory\AuthControllerFactory',
            'BackEnd\Controller\Status' => 'BackEnd\Factory\PostStatusControllerFactory',
            'BackEnd\Controller\Type' => 'BackEnd\Factory\PostTypeControllerFactory',
            'BackEnd\Controller\RegexRouter' => function($sm){
                $real = $sm->getServiceLocator();
                return new \BackEnd\Controller\RegexRouterController($real);
            },
            'BackEnd\Controller\Admin' => function($sm){
                $real = $sm->getServiceLocator();
                return new \BackEnd\Controller\AdminController($real);
            },
            'BackEnd\Controller\IlluminateDatabase' => function($sm){
                $real = $sm->getServiceLocator();
                return new \BackEnd\Controller\IlluminateDatabaseController($real);
            }
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
                'may_terminate' => true,
                'child_routes' => array(
                    'province' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/province[/][action/:action][type/:type][/:id][/col/:col]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'col' => '.+',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Province',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'district' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/district[/][/:page][/action/:action][type/:type][/:id][/col/:col]',
                            'constraints' => array(
                                // 'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'col' => '.+',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\District',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'category' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/category[/][action/:action][type/:type][/:id][/col/:col]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'col' => '.+',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Category',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'status' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/status[/][action/:action][type/:type][/:id][/col/:col]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'col' => '.+',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Status',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'type' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/type[/][action/:action][type/:type][/:id][/col/:col]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'col' => '.+',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Type',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'post-images' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/post-images[/][/:action][type/:type][/:id][/col/:col]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'col' => '.+',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\PostImages',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'pager' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/district[/:page]',
                            'constraints' => array(
                                'page' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\District',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'ward' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/ward[/][/action/:action][type/:type][/:id][/col/:col]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                                'page' => '[0-9]+',
                                'col' => '.+',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Ward',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'ajax' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/ajax',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Ward',
                                'action' => 'ajax',
                            ),
                        ),
                    ),
                    'post-feature' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/post-feature',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\PostFeature',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'post-feature-view' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/post-feature/[:id]',
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\PostFeature',
                                'action' => 'view',
                            ),
                        ),
                    ),
                    'post-feature-edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/post-feature/[:id]/edit',
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\PostFeature',
                                'action' => 'edit',
                            ),
                        ),
                    ),
                    'post-feature-delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/post-feature/[:id]/delete',
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\PostFeature',
                                'action' => 'delete',
                            ),
                        ),
                    ),
                    'post-feature-create' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/post-feature/create',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\PostFeature',
                                'action' => 'create',
                            ),
                        ),
                    ),
                    'deep-feature' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/deep-feature',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\PostFeature',
                                'action' => 'deepFeature',
                            ),
                        ),
                    ),
                    'deep-feature-match' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/deep-feature/match',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\PostFeature',
                                'action' => 'deepFeatureMatch',
                            ),
                        ),
                    ),
                    'deep-feature-edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/deep-feature/[:id]/edit',
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\PostFeature',
                                'action' => 'deepFeatureEdit',
                            ),
                        ),
                    ),
                    'deep-feature-delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/deep-feature/[:id]/delete',
                            'constraints' => array(
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Backend\Controller\PostFeature',
                                'action' => 'deepFeatureDelete',
                            ),
                        ),
                    ),
                    'post-feature-paginator' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/post-feature/page[/:page]',
                            'constraints' => array(
                                'page' => '[0-9]+',
                            ),
                            'defaults' => array(
                                //                                'page' => 1,
                                'controller' => 'Backend\Controller\PostFeature',
                                'action' => 'index'
                            ),
                        ),
                    ),
                    'illuminate-database' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/illuminate-database',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\IlluminateDatabase',
                                'action' => 'index'
                            ),
                        ),
                    ),
                    'post-create' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/post/create',
                            'defaults' => array(
                                'controller' => 'Backend\Controller\Post',
                                'action' => 'create'
                            ),
                        ),
                    ),
                ),
            ),
            'join' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/join',
                    'defaults' => array(
                        'controller' => 'BackEnd\Controller\Auth',
                        'action' => 'join',
                    ),
                ),
            ),
            'login' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        'controller' => 'BackEnd\Controller\Auth',
                        'action' => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => 'BackEnd\Controller\Auth',
                        'action' => 'logout',
                    ),
                ),
            ),
            'test-user-comment' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/test/comment[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Backend\Controller\Test',
                        'action' => 'userComment',
                    ),
                ),
            ),
            'test-config' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/test/config[/:page]',
                    'constraints' => array(
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Backend\Controller\Test',
                        'action' => 'index',
                    ),
                ),
            ),
            'test-pagination' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/test/comment[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Backend\Controller\Test',
                        'action' => 'userComment',
                    ),
                ),
            ),
            'test-regex-router' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/test',
                    "defaults" => array(
                        "__NAMESPACE__" => 'Backend\Controller',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'step' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller][/:action][/page/:page][/order_by/:order_by][/order/:order]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'page' => '[0-9]+',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'formDeepCheckbox' => BackEnd\View\Helper\FormDeepCheckbox::class,
            'formUniSelect' => BackEnd\View\Helper\FormUniSelect::class,
            'formUniElementErrors' => BackEnd\View\Helper\FormUniElementErrors::class,
        ),
        'factories' => array(
            'Requesthelper' => 'BackEnd\View\Helper\Factory\RequestHelperFactory',
            "uniUrl" => function($sm){
                return new BackEnd\View\Helper\UniUrl($sm);
            },
        )
    ),
    // ViewManager configuration
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        // Doctype with which to seed the Doctype helper
        'doctype' => 'HTML5',
        // e.g. HTML5, XHTML1
        // Layout template name
        'layout' => \BackEnd\Module::LAYOUT,
        // e.g. 'layout/layout'
        // TemplateMapResolver configuration
        // template/path pairs
        'template_map' => array(
            \BackEnd\Module::LAYOUT => __DIR__ . '/../view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'province_add_template' => __DIR__ . '/../view/back-end/province/add.phtml',
            'district_add_template' => __DIR__ . '/../view/back-end/district/add.phtml',
            'ward_add_template' => __DIR__ . '/../view/back-end/ward/add.phtml',
            'category_add_template' => __DIR__ . '/../view/back-end/category/add.phtml',
            "post-status" => __DIR__ . "/../view/back-end/post-status/add.phtml",
            'test_pages' => __DIR__ . '/../view/pager.phtml',
            "msg-info" => __DIR__ . "/../view/child-view/msg.phtml",
            "post-feature-paginator" => __DIR__ . "/../view/child-view/post-feature-paginator.phtml",

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
            'adapter' => function($sm){
                /** @var \Zend\ServiceManager\ServiceManager $sm */
                $config = $sm->get('config');
                return new \Zend\Db\Adapter\Adapter($config['db']);
            },
            "SessionError" => function(){
                $sessionManger = new \Zend\Session\SessionManager();
                $sessionStorage = new \Zend\Session\Storage\SessionArrayStorage();
                $sessionManger->setStorage($sessionStorage);
                \Zend\Session\Container::setDefaultManager($sessionManger);
                $container = new \Zend\Session\Container("SessionError");
                return $container;
            },
            "init-capsule" => function($sm){
                $db = $sm->get("config")["db"];
                $capsule = new Illuminate\Database\Capsule\Manager();

                $capsule->addConnection([
                    "driver" => $db["illuminate_driver"],
                    "host" => $db["host"],
                    "database" => $db["database"],
                    "username" => $db["username"],
                    "password" => $db["password"],
                    "charset" => "utf8",
                    "collation" => "utf8_unicode_ci",
                    "prefix" => "",
                ]);

                // Set the event dispatcher used by Eloquent models... (optional)
                $capsule->setEventDispatcher(new Illuminate\Events\Dispatcher(new Illuminate\Container\Container));

                // Make this Capsule instance available globally via static methods... (optional)
                $capsule->setAsGlobal();

                // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
                $capsule->bootEloquent();
                return $capsule;
            }
        ),
    ),
);
        
