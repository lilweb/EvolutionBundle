<?php
/**
 * User: Geoffrey Brier
 * Date: 21/03/13
 * Time: 14:00
 */
namespace Lilweb\EvolutionBundle\Exception;

/**
 * Exceptions related to the processing taking place in the EvolutionParser class.
 */
class EvolutionParserException extends \Exception
{
    /**
     * @return string
     */
    private static function getLastErrorMessage()
    {
        $error = error_get_last();

        return $error['message'];
    }

    public static function cannotReadFile()
    {
        return new static(self::getLastErrorMessage());
    }

    public static function cannotParseFile($line)
    {
        return new static(sprintf('Un problème a été détecté lors du parsing (ligne %d), avez-vous bien définie vos "ups" et "downs"?', $line));
    }

    public static function missingUps()
    {
        return new static('Aucune évolution détectée dans le fichier');
    }

    public static function missingDowns()
    {
        return new static('Aucun rollback détectée dans le fichier');
    }
}