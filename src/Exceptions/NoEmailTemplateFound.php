<?php

namespace Rojr\Scaffold\Email\Templates\Exceptions;

use Rhubarb\Crown\Exceptions\RhubarbException;

class NoEmailTemplateFound extends RhubarbException
{
    public function __construct(\Exception $previous = null)
    {
        parent::__construct('Email Template was unable to load as there was no model behind it.', $previous);
    }
}
