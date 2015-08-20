<?php
namespace AsseticI18n\Filter;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

class LanguageFilterFactory implements AbstractFactoryInterface
{

    public static $filterKey = 'assetic_i18n_language_filter';

    private static $pattern = "/assetic_i18n_language_filter_([a-z]{2}_[A-Z]{2})/";

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return 1 === preg_match(self::$pattern, $requestedName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $matches = array();
        preg_match(self::$pattern, $requestedName, $matches);
        $targetLocale = $matches[1];
        $service = new LanguageFilter($targetLocale);
        $service->setServiceLocator($serviceLocator);
        return $service;
    }
}
