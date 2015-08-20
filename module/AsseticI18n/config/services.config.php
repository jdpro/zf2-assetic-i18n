<?php
return array(
    'abstract_factories' => array(
        'AsseticI18n\Filter\LanguageFilterFactory',
    ),
    'invokables' => array(
        'AsseticI18n\Listener\AsseticI18nConfigModifier' => 'AsseticI18n\Listener\AsseticI18nConfigModifier',
        'AsseticI18n\Model\PrimaryStringProvider' => 'AsseticI18n\Model\PrimaryStringProvider',
    ),
    'aliases' => array(
        'assetic-i18n-config-modifier' => 'AsseticI18n\Listener\AsseticI18nConfigModifier',
        'primary-string-provider' => 'AsseticI18n\Model\PrimaryStringProvider'
    )
);
