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
        'invokables' => array(
            'playgroundcms'                  => 'PlaygroundCms\Controller\Frontend\IndexController',
            'playgroundcmsadminpage'         => 'PlaygroundCms\Controller\Admin\PageController',
            'playgroundcmsadminblock'        => 'PlaygroundCms\Controller\Admin\BlockController',
            'playgroundcmsadmindynablock'    => 'PlaygroundCms\Controller\Admin\DynablockController',
        ),
    ),

    'dynacms' => array(
        'dynablocks' => array(
            'newsletter_subscription' => array(
                'title'       => 'Bloc de souscription',
                'description' => 'bloc dynamique',
                'widget'      => 'PlaygroundNewsletter\Widget\Subscribe',
            ),
        ),
        'dynareas' => array(
            'column_home' => array(
                'title' => 'Colonne de la home',
                'description' => 'ceci est une description',
                'location' => 'playground-game\index\index',
            ),
        ),
    ),

    'router' => array(
        'routes' => array(
        	'frontend' => array(
        		'child_routes' => array(
		            'cms' => array(
		                'type' => 'Zend\Mvc\Router\Http\Segment',
		                'options' => array(
		                    'route'    => 'page/:id',
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
		                            'route' => '/list[/:p]',
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
                'label' => 'Posts',
                'route' => 'admin/playgroundcmsadmin/pages/list',
                'resource' => 'cms',
                'privilege' => 'list',
                'pages' => array(
                    'list-pages' => array(
                        'label' => 'Posts list',
                        'route' => 'admin/playgroundcmsadmin/pages/list',
                        'resource' => 'cms',
                        'privilege' => 'list',
                        'pages' => array(
		                    'edit-page' => array(
		                    	'label' => 'Editer un article',
		                        'route' => 'admin/playgroundcmsadmin/pages/edit',
		                        'resource' => 'cms',
		                        'privilege' => 'edit',
		                    ),
						),
                    ),
                    'create-page' => array(
                        'label' => 'Create a post',
                        'route' => 'admin/playgroundcmsadmin/pages/create',
                        'resource' => 'cms',
                        'privilege' => 'add',
                    ),
                    'list-block' => array(
                        'label' => 'Content bloc list',
                        'route' => 'admin/playgroundcmsadmin/blocks/list',
                        'resource' => 'cms',
                        'privilege' => 'list',
                    ),
                    'create-block' => array(
                        'label' => 'Create a content bloc',
                        'route' => 'admin/playgroundcmsadmin/blocks/create',
                        'resource' => 'cms',
                        'privilege' => 'add',
                    ),
                    'list-dynablock' => array(
                        'label' => 'Dynamic blocs list',
                        'route' => 'admin/playgroundcmsadmin/dynablocks/list',
                        'resource' => 'cms',
                        'privilege' => 'list',
                    ),
                ),
            ),
        ),
    )
);
