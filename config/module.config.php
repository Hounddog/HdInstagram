<?php
use HdApiClient\Client as Client;

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
        'factories' => array(
            'HD\Instagram\Client' => function($sm) {
            	$client = new Client();
            	$client->setApiNamespace('HD\Instagram');
            	return $client;
            },
            'HdApiClient\HttpClient' => function($sm) {
                $config = $sm->get("Config");
                $options = $config['hd-instagram']['options'];
                $client = new HdApiClient\Http\Client($options);
                return $client;
            },
        ),
        'invokables' => array(
        	'HD\Instagram\Api\Tags' => 'HD\Instagram\Api\Tags',
            'HD\Instagram\Api\Subscribe' => 'HD\Instagram\Api\Subscribe',
            'HD\Instagram\Api\Media' => 'HD\Instagram\Api\Media',
            'HD\Instagram\Listener\Auth\UrlClientId'     => 'HdApiClient\Listener\Auth\UrlClientId',
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