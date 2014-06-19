<?php

namespace HD\Instagram;

use Zend\ModuleManager\ModuleManager;
use Zend\EventManager\StaticEventManager;
use Zend\Cache\StorageFactory;
use Zend\Mvc\ApplicationInterface;

use ZfcBase\Module\AbstractModule;

class Module extends AbstractModule
{
    public function bootstrap(ModuleManager $moduleManager, ApplicationInterface $app)
    {
        $em = $app->getEventManager()->getSharedManager();
        $sm = $app->getServiceManager();
    }

    public function getNamespace(){
        return __NAMESPACE__;
    }

    public function getDir() {
        return __DIR__;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/',
                ),
            ),
        );
    }


    public function getConfig($env = null)
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'HD\Instagram\Client' => function ($sm) {
                    $config = $sm->get('Config');

                    $httpClient = $sm->get('HD\API\Client\Http\Client');
                    $httpClient->setOptions($config['hd-instagram']['options']);

                    $client = $sm->get('HD\API\Client\Client');
                    $client->setHttpClient($httpClient);
                    $client->setApiNamespace('HD\Instagram');
                    $client->authenticate('HD\Instagram\Listener\Auth\UrlClientId', $config['hd-instagram']);
                    return $client;
                },
            ),
        );
    }
}
