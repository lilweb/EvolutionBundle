<?php
/**
 * User: Geoffrey Brier
 * Date: 20/03/13
 * Time: 15:08
 */
namespace Lilweb\EvolutionBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EvolutionRepository.
 */
class EvolutionRepository extends EntityRepository
{
    /**
     * Gets the last version that was successfuly executed.
     *
     * @return \Lilweb\EvolutionBundle\Entity\Evolution
     */
    public function getLastVersion()
    {
        return $this
            ->createQueryBuilder('e')
            ->select('e')
            ->orderBy('e.version', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
