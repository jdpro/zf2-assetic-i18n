<?php
namespace AsseticI18n\Listener;

use AsseticBundle\Configuration;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class AsseticI18nConfigModifier implements ServiceLocatorAwareInterface, ListenerAggregateInterface
{
    
    use\Zend\ServiceManager\ServiceLocatorAwareTrait;

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\EventManager\ListenerAggregateInterface::attach()
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractController', MvcEvent::EVENT_DISPATCH, array(
            $this,
            'modifyConfiguration'
        ), 100);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\EventManager\ListenerAggregateInterface::detach()
     */
    public function detach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function modifyConfiguration()
    {
        $config = $this->getServiceLocator()->get('AsseticConfiguration');
        $modules = $config->getModules();
        foreach ($modules as $moduleName => $module) {
            if (! isset($module['collections']) || ! is_array($module['collections']))
                continue;
            $collections = $module['collections'];
            foreach ($collections as $collectionName => $collection) {
                if (! isset($collection['assets']) || ! is_array($collection['assets']) || empty($collection['assets']))
                    continue;
                    // check that the first asset endsWith .js
                $isJavascript = self::endsWith($collection['assets'][0], '.js');
                if (false === $isJavascript)
                    continue;
                unset($modules[$moduleName]['collections'][$collectionName]);
                $languages = self::getAvailableLanguages();
                foreach ($languages as $language) {
                    $collectionCopy = $collection;
                    $filterToAdd = $this->getFilterForLanguage($language);
                    $collectionCopy = array_merge($collectionCopy, $filterToAdd);
                    $modules[$moduleName]['collections'][$language . '_' . $collectionName] = $collectionCopy;
                }
            }
        }
        $config->setModules($modules);
    }

    private function endsWith($haystack, $needle)
    {
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }

    /**
     *
     * @param string $language            
     * @return array
     */
    private function getFilterForLanguage($language)
    {
        $filterToAdd = array(
            'filters' => array()
        );
        $filterToAdd['filters'][\AsseticI18n\Filter\LanguageFilterFactory::$filterKey . $language] = array(
            'name' => \AsseticI18n\Filter\LanguageFilterFactory::$filterKey . $language
        );
        return $filterToAdd;
    }

    /**
     *
     * @throws \DisplayLayer\ErrorHandling\Exception\ApplicationConfigurationException
     * @return array
     */
    private function getAvailableLanguages()
    {
        $config = self::getConfig();
        if (! isset($config['translator']) || ! isset($config['translator']['available_languages']))
            throw new \DisplayLayer\ErrorHandling\Exception\ApplicationConfigurationException("Please provide translator->available_languages field in configuration ");
        return $config['translator']['available_languages'];
    }

    /**
     *
     * @return array
     */
    private function getConfig()
    {
        return $this->getServiceLocator()->get('Config');
    }
}
