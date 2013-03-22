<?php
/**
 * User: Geoffrey Brier
 * Date: 21/03/13
 * Time: 14:00
 */
namespace Lilweb\EvolutionBundle\Model\Sql;

/**
 * Représente une evolution SQL. 
 */
abstract class EvolutionSqlEntity
{
    /** @var string */
    protected $sql;

    /**
     * Constructor.
     *
     * @param string $sql
     */
    public function __construct($sql = '')
    {
        $this->sql = $sql;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param string $sql
     */
    public function setSql($sql)
    {
        $this->sql = $sql;
    }

    /**
     * @param string $sql
     */
    public function append($sql)
    {
        $this->sql .= $sql;
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->sql === null || !strlen($this->sql);
    }
}