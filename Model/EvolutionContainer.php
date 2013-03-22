<?php
/**
 * User: Geoffrey Brier
 * Date: 21/03/13
 * Time: 14:00
 */
namespace Lilweb\EvolutionBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class containing Evolution objects.
 */
class EvolutionContainer
{
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $container;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->container = new ArrayCollection();
    }

    /**
     * @param \Lilweb\EvolutionBundle\Model\Evolution $result
     */
    public function addEvolution(Evolution $result)
    {
        $this->container->set($result->getVersion(), $result);
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getEvolutions()
    {
        return $this->container;
    }

    /**
     * @param string $key
     *
     * @return \Lilweb\EvolutionBundle\Model\Evolution|null
     */
    public function getEvolution($offset)
    {
        return $this->container->get($offset);
    }
}