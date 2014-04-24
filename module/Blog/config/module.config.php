<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'controllers' => array(
        'invokables' => array(
            'Blog\Controller\ManagerController' => 'Blog\Controller\ManagerController',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
        'blog_entities' => array(
          'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
          'cache' => 'array',
          'paths' => array(__DIR__ . '/../src/Blog/Entity')
        ),

        'orm_default' => array(
          'drivers' => array(
            'Blog\Entity' => 'blog_entities'
          )
    ))),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'manager' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/manager[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\ManagerController',
                        'action'     => 'index',
                    ),
                ),
            ),
            'manager/blog' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/manager/blog/[:blog_id][/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'blog_id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\BlogController',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
