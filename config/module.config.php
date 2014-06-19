<?php
return array(
    'router' => array(
        'routes' => array(
            'hd-instagram' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/hd-instagram',
                    'defaults' => array(
                        'controller' => 'HD\Instagram\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'HD\Instagram\Api\Tags' => 'HD\Instagram\Api\Tags',
            'HD\Instagram\Api\Subscribe' => 'HD\Instagram\Api\Subscribe',
            'HD\Instagram\Api\Media' => 'HD\Instagram\Api\Media',
            'HD\Instagram\Listener\Auth\UrlClientId'     => 'HD\Instagram\Listener\Auth\UrlClientId',
        ),
    ),
    'hd-instagram' => array(
        'options' => array(
            'base_url' => 'https://api.instagram.com/',
            'api_version' => 'v1',
            'timeout'     => 10,
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'HD\Instagram\Controller\Index' => 'HD\Instagram\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
