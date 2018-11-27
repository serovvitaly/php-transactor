<?php

namespace App\Repository\Exception;

use Throwable;

/**
 * Исключение "Сущность не найдена"
 * @package App\Repository\Exception
 */
class EntityNotFoundException extends \Exception
{
    protected $message = 'Entity not found';
}
