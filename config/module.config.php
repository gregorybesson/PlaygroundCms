<?php

return array(
    'doctrine' => array(
        'driver' => array(
            'playgroundcms_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/PlaygroundCms/Entity'
            ),

            'orm_default' => array(
                'drivers' => array(
                    'PlaygroundCms\Entity'  => 'playgroundcms_entity'
                )
            )
        )
    ),
    
    'bjyauthorize' => array(
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'cms' => array(),
            ),
        ),
    
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(array('admin'), 'cms', array('list','add','edit','delete')),
                ),
            ),
        ),

        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
        
                array('controller' => 'playgroundcms', 'roles' => array('guest', 'user')),

                //Admin
                array('controller' => 'playgroundcmsadminpage', 'roles' => array('admin')),
                array('controller' => 'playgroundcmsadminblock', 'roles' => array('admin')),
                array('controller' => 'playgroundcmsadmindynablock', 'roles' => array('admin')),
                array('controller' => 'playgroundcms_admin_slideshow', 'roles' => array('admin')),
                array('controller' => 'playgroundcms_admin_slide', 'roles' => array('admin')),
            ),
        ),
    ),

    'translator' => array(
        'locale' => 'fr_FR',
        'translation_file_patterns' => array(
            array(
                'type'         => 'phpArray',
                'base_dir'     => __DIR__ . '/../language',
                'pattern'      => '%s.php',
                'text_domain'  => 'playgroundcms'
            ),
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
        ),
        'template_path_stack' => array(
            'playgroundcms' => __DIR__ . '/../view/admin',
        	'playgroundcms' => __DIR__ . '/../view/frontend',
        ),
    ),

    'controllers' => array(
        'factories' => array(
            'playgroundcms'                  => 'PlaygroundCms\Service\Factory\FrontendIndexControllerFactory',
            'playgroundcmsadminpage'         => 'PlaygroundCms\Service\Factory\AdminPageControllerFactory',
            'playgroundcmsadminblock'        => 'PlaygroundCms\Service\Factory\AdminBlockControllerFactory',
            'playgroundcmsadmindynablock'    => 'PlaygroundCms\Service\Factory\AdminDynablockControllerFactory',
            'playgroundcms_admin_slideshow'  => 'PlaygroundCms\Service\Factory\AdminSlideshowControllerFactory',
            'playgroundcms_admin_slide'      => 'PlaygroundCms\Service\Factory\AdminSlideControllerFactory',
        ),
    ),

    'service_manager' => array(
        'aliases' => array(
            'playgroundcms_doctrine_em' => 'doctrine.entitymanager.orm_default',
            'playgroundcms_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',
        ),
        'factories' => array(
            'playgroundcms_block_service'     => 'PlaygroundCms\Service\Factory\BlockFactory',
            'playgroundcms_dynablock_service' => 'PlaygroundCms\Service\Factory\DynablockFactory',
            'playgroundcms_page_service'      => 'PlaygroundCms\Service\Factory\PageFactory',
            'playgroundcms_slideshow_service' => 'PlaygroundCms\Service\Factory\SlideshowFactory',
            'playgroundcms_slide_service'     => 'PlaygroundCms\Service\Factory\SlideFactory',
        ),
    ),

    'dynacms' => array(
        // 'dynablocks' => array(
        //     array(
        //         'title'       => 'Slideshow',
        //         'description' => 'bloc dynamique',
        //         'type'        => 'slideshowWidget',
        //         'identifier'  => 1
        //     ),
        // ),
        'dynareas' => array(
            'column_home' => array(
                'title' => 'Colonne de la home',
                'description' => 'ceci est une description',
                'location' => 'playground-game\index\index',
            )
        ),
    ),

    'router' => array(
        'routes' => array(
        	'frontend' => array(
        		'child_routes' => array(
		            'cms' => array(
		                'type' => 'Zend\Mvc\Router\Http\Segment',
		                'options' => array(
		                    'route'    => 'page[/:pid]',
		                    'defaults' => array(
		                        'controller' => 'playgroundcms',
		                        'action'     => 'index',
		                    ),
		                ),
		                'may_terminate' => true,
		                'child_routes' =>array(
		                    'list' => array(
		                        'type' => 'Segment',
		                        'options' => array(
		                            'route' => '/liste[/:p]',
		                            'defaults' => array(
		                                'controller' => 'playgroundcms',
		                                'action'     => 'list',
		                            ),
		                        ),
		                    ),
		                ),
		            ),
		            'winner' => array(
		                'type' => 'Zend\Mvc\Router\Http\Segment',
		                'options' => array(
		                    'route'    => 'les-gagnants',
		                    'defaults' => array(
		                        'controller' => 'playgroundcms',
		                        'action'     => 'winnerList',
		                    ),
		                ),
		                'may_terminate' => true,
		                'child_routes' =>array(
		                    'page' => array(
		                        'type' => 'Segment',
		                        'options' => array(
		                            'route' => '/:id',
		                            'defaults' => array(
		                                'controller' => 'playgroundcms',
		                                'action'     => 'winnerPage',
		                            ),
		                        ),
		                    ),
		                    'pagination' => array(
		                        'type' => 'Segment',
		                        'options' => array(
		                            'route' => '[:p]',
		                            'defaults' => array(
		                                'controller' => 'playgroundcms',
		                                'action'     => 'winnerList',
		                            ),
		                        ),
		                    ),
		                ),
		            ),
        		),
        	),

            'admin' => array(
                'child_routes' => array(
                    'playgroundcmsadmin' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/cms',
                            'defaults' => array(
                                'controller' => 'playgroundcmsadminpage',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'pages' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/pages',
                                    'defaults' => array(
                                        'controller' => 'playgroundcmsadminpage',
                                        'action'     => 'index',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'list' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/list[/:filter][/:p]',
                                            'defaults' => array(
                                                'controller' => 'playgroundcmsadminpage',
                                                'action'     => 'list',
                                                'filter' 	 => 'DESC'
                                            ),
                                            'constraints' => array(
				                                'filter' => '[a-zA-Z][a-zA-Z0-9_-]*',
				                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/create',
                                            'defaults' => array(
                                                'controller' => 'playgroundcmsadminpage',
                                                'action'     => 'create'
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit/:pageId',
                                            'defaults' => array(
                                                'controller' => 'playgroundcmsadminpage',
                                                'action'     => 'edit',
                                                'userId'     => 0
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove/:pageId',
                                            'defaults' => array(
                                                'controller' => 'playgroundcmsadminpage',
                                                'action'     => 'remove',
                                                'userId'     => 0
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'blocks' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/blocks',
                                    'defaults' => array(
                                        'controller' => 'playgroundcmsadminblock',
                                        'action'     => 'index',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'list' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/list[/:p]',
                                            'defaults' => array(
                                                'controller' => 'playgroundcmsadminblock',
                                                'action'     => 'list',
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/create',
                                            'defaults' => array(
                                                'controller' => 'playgroundcmsadminblock',
                                                'action'     => 'create'
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit/:blockId',
                                            'defaults' => array(
                                                'controller' => 'playgroundcmsadminblock',
                                                'action'     => 'edit',
                                                'blockId'    => 0
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove/:blockId',
                                            'defaults' => array(
                                                'controller' => 'playgroundcmsadminblock',
                                                'action'     => 'remove',
                                                'userId'     => 0
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'dynablocks' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/dynablocks',
                                    'defaults' => array(
                                        'controller' => 'playgroundcmsadmindynablock',
                                        'action'     => 'index',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'list' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/list[/:p]',
                                            'defaults' => array(
                                                'controller' => 'playgroundcmsadmindynablock',
                                                'action'     => 'list',
                                            ),
                                        ),
                                    ),
                                    'create' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/create/:dynareaId',
                                            'defaults' => array(
                                                'controller' => 'playgroundcmsadmindynablock',
                                                'action'     => 'create',
                                                'dynareaId'=> 0
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit/:dynareaId',
                                            'defaults' => array(
                                                'controller' => 'playgroundcmsadmindynablock',
                                                'action'     => 'edit',
                                                'dynareaId'=> 0
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove/:dynareaId',
                                            'defaults' => array(
                                                'controller' => 'playgroundcmsadmindynablock',
                                                'action'     => 'remove',
                                                'dynareaId'     => 0
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'slideshow' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/slideshow',
                                    'defaults' => array(
                                        'controller' => 'playgroundcms_admin_slideshow',
                                        'action'     => 'list',
                                    ),
                                ),
                                'may_terminate' => true, 
                                'child_routes' => array(
                                    'create' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                             'route' => '/create',
                                            'defaults' => array(
                                                'controller' => 'playgroundcms_admin_slideshow',
                                                'action'     => 'create',
                                            ),
                                        ),
                                    ),
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit[/:slideshowId]',
                                            'defaults' => array(
                                                'controller' => 'playgroundcms_admin_slideshow',
                                                'action'     => 'edit',
                                            ),
                                            'constraints' => array(
                                                'slideshowId' => '[0-9]*',
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove[/:slideshowId]',
                                            'defaults' => array(
                                                'controller' => 'playgroundcms_admin_slideshow',
                                                'action'     => 'remove',
                                            ),
                                            'constraints' => array(
                                                'slideshowId' => '[0-9]*',
                                            ),
                                        ),
                                    ),
                                    'activate' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/activate[/:slideshowId]',
                                            'defaults' => array(
                                                'controller' => 'playgroundcms_admin_slideshow',
                                                'action'     => 'activate',
                                            ),
                                            'constraints' => array(
                                                'slideshowId' => '[0-9]*',
                                            ),
                                        ),
                                    ),                                    
                                ),
                            ),
                            'slide' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/slide[/:slideshowId]',
                                    'defaults' => array(
                                        'controller' => 'playgroundcms_admin_slide',
                                        'action'     => 'create',
                                    ),
                                    'constraints' => array(
                                        'slideshowId' => '[0-9]*',
                                    ),
                                ),
                                'may_terminate' => true, 
                                'child_routes' => array(
                                    'edit' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/edit[/:id]',
                                            'defaults' => array(
                                                'controller' => 'playgroundcms_admin_slide',
                                                'action'     => 'edit',
                                            ),
                                            'constraints' => array(
                                                'id' => '[0-9]*',
                                            ),
                                        ),
                                    ),
                                    'remove' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/remove[/:id]',
                                            'defaults' => array(
                                                'controller' => 'playgroundcms_admin_slide',
                                                'action'     => 'remove',
                                            ),
                                            'constraints' => array(
                                                'id' => '[0-9]*',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'core_layout' => array(
        'frontend' => array(
            'modules' => array(
                'playgroundcms' => array(
                    'layout' => 'layout/2columns-left.phtml',
                    'controllers' => array(
                        'playgroundcms' => array(
                            'actions' => array(
                                'index' => array(
                                    'children_views' => array(
                                        'col_left' => 'playground-user/user/col-user.phtml'
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'navigation' => array(
        'default' => array(
        	'index' => array(
                'controller' => 'playgroundcms',
                'action'     => 'index',
            ),
            'winner' => array(
                'label' => 'Les gagnants',
                'route' => 'les-gagnants',
                'controller' => 'playgroundcms',
                'action'     => 'winnerList',
            ),
            'page' => array(
                'label' => 'Les gagnants',
                'route' => 'les-gagnants/:id',
                'controller' => 'playgroundcms',
                'action'     => 'winnerPage',
            ),
        ),
        'admin' => array(
            'playgroundcms' => array(
                'label' => 'Cms',
                'route' => 'admin/playgroundcmsadmin/pages/list',
                'resource' => 'cms',
                'privilege' => 'list',
                'pages' => array(
                    'elfinder' => array(
                        'label' => 'Your media repository',
                        'route' => 'admin/elfinder',
                        'resource' => 'cms',
                        'privilege' => 'add',
                    ),
                    'list-pages' => array(
                        'label' => 'Pages',
                        'route' => 'admin/playgroundcmsadmin/pages/list',
                        'resource' => 'cms',
                        'privilege' => 'list',
                    ),
                    'list-block' => array(
                        'label' => 'Blocks',
                        'route' => 'admin/playgroundcmsadmin/blocks/list',
                        'resource' => 'cms',
                        'privilege' => 'list',
                    ),
                    'list-dynablock' => array(
                        'label' => 'Dynamic blocks',
                        'route' => 'admin/playgroundcmsadmin/dynablocks/list',
                        'resource' => 'cms',
                        'privilege' => 'list',
                    ),
                    'slideshow' => array(
                        'label' => 'Slideshow',
                        'route' => 'admin/playgroundcmsadmin/slideshow',
                        'resource' => 'cms',
                        'privilege' => 'list',
                    ),
                ),
            ),
        ),
    )
);
