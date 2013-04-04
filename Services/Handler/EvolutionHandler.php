<?php
/**
 * User: Geoffrey Brier
 * Date: 21/03/13
 * Time: 14:00
 */
namespace Lilweb\EvolutionBundle\Services\Handler;

use Doctrine\ORM\EntityManager;

use Monolog\Logger;

use Symfony\Component\Console\Output\OutputInterface;

use Lilweb\EvolutionBundle\Entity\Evolution;
use Lilweb\EvolutionBundle\Model\EvolutionContainer;
use Lilweb\EvolutionBundle\Exception\EvolutionHandlerException;

/**
 * The EvolutionHandler service is responsible for processing the EvolutionContainer to
 * apply evolutions and rolling back if necessary.
 */
class EvolutionHandler
{
    /** @var \Monolog\Logger */
    private $logger;

    /** @var \Doctrine\DBAL\Connection */
    private $connection;

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    /**
     * Constructor.
     *
     * @param \Lilweb\EvolutionBundle\Services\Handler\Logger $logger
     * @param \Doctrine\ORM\EntityManager                     $connection
     */
    public function __construct(Logger $logger, EntityManager $em)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->connection = $em->getConnection();
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Lilweb\EvolutionBundle\Model\EvolutionContainer  $container
     */
    public function handle(OutputInterface $output, EvolutionContainer $container)
    {
        $evolutionStack = array();

        try {
            // 1. Apply evolutions one by one
            foreach ($container->getEvolutions() as $evolution) {
                $this->logger->debug('Application de l\'évolution #'.$evolution->getVersion());
                $output->write("\t".'Application de l\'évolution #'.$evolution->getVersion().' ... ');

                $evolutionStack[] = $evolution;
                $stmts = explode(';', $evolution->getUps()->getSql());

                foreach ($stmts as $stmt) {
                    if ($this->connection->exec($stmt) === false) {
                        throw EvolutionHandlerException::evolutionFailure($this->connection->errorInfo());
                    }
                }

                $output->writeln('<info>OK</info>');
            }

            $output->writeln("\t".count($evolutionStack).' évolutions apportées');

            // 2. Sauvegarde des évolutions
            foreach ($evolutionStack as $evolutionModel) {
                $evolution = new Evolution();
                $evolution->setVersion($evolutionModel->getVersion());

                $this->em->persist($evolution);
            }

            $this->em->flush();
        } catch (\Exception $e) {
            // 2. Rollback
            $output->writeln('<error>FAIL</error>');

            $reversedEvolutionStack = array_reverse($evolutionStack);
            foreach ($reversedEvolutionStack as $evolution) {
                $this->logger->debug('Rollback de l\'évolution #'.$evolution->getVersion());
                $output->writeln("\t".'Rollback de l\'évolution #'.$evolution->getVersion());

                $stmts = explode(';', $evolution->getDowns()->getSql());
                try {
                    foreach ($stmts as $stmt) {
                        $this->connection->exec($stmt);
                    }
                } catch (\Exception $rollbackException) {
                    $output->writeln("\t<comment>Echec du rollback (".$rollbackException->getMessage().")</comment>");
                }
            }

            throw $e;
        }
    }
}