<?php
/**
 * User: Geoffrey Brier
 * Date: 21/03/13
 * Time: 14:00
 */
namespace Lilweb\EvolutionBundle\Model;

use Symfony\Component\Finder\SplFileInfo;

use Lilweb\EvolutionBundle\Model\Sql\EvolutionDowns;
use Lilweb\EvolutionBundle\Model\Sql\EvolutionUps;

/**
 * Simple object representing an evolution:
 *  - version
 *  - file associated
 *  - ups
 *  - downs
 */
class Evolution
{
    /** @var integer */
    private $version;

    /** @var \Symfony\Component\Finder\SplFileInfo */
    private $file;

    /** @var Lilweb\EvolutionBundle\Model\Sql\EvolutionUps */
    private $ups;

    /** @var Lilweb\EvolutionBundle\Model\Sql\EvolutionDowns */
    private $downs;

    /**
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param integer $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $file
     */
    public function setFile(SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     *
     * @return \Lilweb\EvolutionBundle\Model\Sql\EvolutionUps
     */
    public function getUps()
    {
        return $this->ups;
    }

    /**
     * @param \Lilweb\EvolutionBundle\Model\Sql\EvolutionUps $ups
     */
    public function setUps(EvolutionUps $ups)
    {
        $this->ups = $ups;
    }

    /**
     * @return \Lilweb\EvolutionBundle\Model\Sql\EvolutionDowns
     */
    public function getDowns()
    {
        return $this->downs;
    }

    /**
     * @param \Lilweb\EvolutionBundle\Model\Sql\EvolutionDowns $downs
     */
    public function setDowns(EvolutionDowns $downs)
    {
        $this->downs = $downs;
    }
}