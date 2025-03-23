<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    protected $message = 'Recurso não encontrado';
}