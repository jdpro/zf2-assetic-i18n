<?php
namespace AsseticI18n;

use Zend\Mvc\MvcEvent;
use Zend\Console\Console;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        // TODO make the test more accurate
        if (Console::isConsole())
            $eventManager->attachAggregate($serviceManager->get('assetic-i18n-config-modifier'));
    }
}
