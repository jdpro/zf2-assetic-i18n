<?php
namespace AsseticI18n\View\Helper;

use Zend\View\Renderer\PhpRenderer;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use AsseticBundle\View\ViewHelperStrategy as BaseStrategy;
use ServiceLocatorFactory\ServiceLocatorFactory;

class ViewHelperStrategy extends BaseStrategy
{

    protected function appendScript($path)
    {
        $this->addCurrentLanguageToPath($path);
        parent::appendScript($path);
    }

    private function addCurrentLanguageToPath(&$path)
    {
        $translator = $this->getTranslator();
        $currentLanguage = $translator->getTranslator()->getLocale();
        $lastSlashPosition = strrpos($path, '/');
        $path = substr($path, 0, $lastSlashPosition) . '/' . $currentLanguage . '_' . substr($path, $lastSlashPosition + 1);
    }

    /**
     *
     * @return \Zend\Mvc\I18n\Translator
     */
    private function getTranslator()
    {
        return ServiceLocatorFactory::getInstance()->get('translator');
    }
}
