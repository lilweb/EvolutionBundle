<?php
/**
 * User: Geoffrey Brier
 * Date: 21/03/13
 * Time: 14:00
 */
namespace Lilweb\EvolutionBundle\Exception;

/**
 * Exceptions related to the processing taking place in the EvolutionHandler class.
 */
class EvolutionHandlerException extends \Exception
{
    public static function evolutionFailure($reason)
    {
        throw new static($reason);
    }
}