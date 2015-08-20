<?php
namespace AsseticI18n\Error;

class MissingTextEntryException extends \Exception
{

    public function __construct($stringCode)
    {
        $message = sprintf('No text entry provided for string code : %s ', $stringCode);
        parent::__construct($message);
    }
}