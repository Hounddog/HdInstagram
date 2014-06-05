<?php

namespace HD\Instagram;

use Zend\ModuleManager\ModuleManager,
    Zend\EventManager\StaticEventManager,
    Zend\Cache\StorageFactory,
    Zend\Mvc\ApplicationInterface;

use ZfcBase\Module\AbstractModule;

class Module extends AbstractModule
{
    public function bootstrap(ModuleManager $moduleManager, ApplicationInterface $app)
    {
        $em = $app->getEventManager()->getSharedManager();
        $sm = $app->getServiceManager();


        $em->attach('HdApiClient\Client', 'api', function($e) use ($sm) {
            $config = $sm->get('Config');
            $client_id = $config['hd-instagram']['client_id'];
            $client_secret = $config['hd-instagram']['client_secret'];
            $client = $e->getTarget();
            $client->authenticate('url_client_id', $client_id, $client_secret);
        } );
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
}
