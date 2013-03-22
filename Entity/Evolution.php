<?php
/**
 * User: Geoffrey Brier
 * Date: 20/03/13
 * Time: 15:00
 */
namespace Lilweb\EvolutionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass = "Lilweb\EvolutionBundle\Repository\EvolutionRepository")
 * @ORM\Table(name = "evolutions")
 */
class Evolution
{
    /**
     * @var integer The version applied.
     *
     * @ORM\Id
     * @ORM\Column(type = "integer")
     */
    private $version;

    /**
     * @var \DateTime The date the at which the evolution was run.
     *
     * @ORM\Column(
     *     type     = "datetime",
     *     nullable = true,
     *     name     = "execution_date"
     * )
     */
    private $executionDate;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->executionDate = new \DateTime();
    }

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
     * @return \DateTime
     */
    public function getExecutionDate()
    {
        return $this->executionDate;
    }

    /**
     * @param \DateTime $executionDate
     */
    public function setExecutionDate(\DateTime $executionDate)
    {
        $this->executionDate = $executionDate;
    }
}