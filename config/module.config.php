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
                        'controller' => 'HdInstagram\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
	'service_manager' => array(
        'factories' => array(
            'HdInstagram\Client' => function($sm) {
            	$client = new Client();
            	$client->setApiNamespace('HdInstagram');
            	return $client;
            },
            'HdApiClient\HttpClient' => function($sm) {
                $config = $sm->get("Config");
                $options = $config['hdinstagram']['options'];
                $client = new HdApiClient\Http\Client($options);
                return $client;
            },
        ),
        'invokables' => array(
        	'HdInstagram\Api\Tags' => 'HdInstagram\Api\Tags',
            'HdInstagram\Api\Subscribe' => 'HdInstagram\Api\Subscribe',
            'HdInstagram\Listener\Auth\UrlClientId'     => 'HdApiClient\Listener\Auth\UrlClientId',
        ),
    ),
	'hdinstagram' => array(
        'options' => array(
            'base_url' => 'https://api.instagram.com/',
            'api_version' => 'v1',
            'timeout'     => 10,
        )
	),
    'controllers' => array(
        'invokables' => array(
            'HdInstagram\Controller\Index' => 'HdInstagram\Controller\IndexController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);