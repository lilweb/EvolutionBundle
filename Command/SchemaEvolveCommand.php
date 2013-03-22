<?php
/**
 * User: Geoffrey Brier
 * Date: 20/03/13
 * Time: 14:53
 */
namespace Lilweb\EvolutionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Schema evolution command.
 */
class SchemaEvolveCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('lilweb:schema:evolve')
            ->setDescription('Lance l\'évolution de la base de données.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger =  $this->getContainer()->get('logger');

        try {
            $logger->debug('Début de l\'évolution');

            // 1. Get actual evolution version
            $logger->debug('Récupération de la dernière version déployée: en cours');
            $output->writeln('Récupération de la dernière version déployée: en cours');
            $lastEvolutionVersion = $this
                ->getContainer()
                ->get('doctrine.orm.entity_manager')
                ->getRepository('LilwebEvolutionBundle:Evolution')
                ->getLastVersion();
            $logger->debug('Récupération de la dernière version déployée: OK');
            $output->writeln('Récupération de la dernière version déployée: <info>OK</info>');

            // 2. Find all evolution files to process
            $logger->debug('Récupération et analyse des évolutions à effectuer: en cours');
            $output->writeln('Récupération et analyse des évolutions à effectuer: en cours');
            $evolutionContainer = $this
                ->getContainer()
                ->get('lilweb.evolution_builder')
                ->build($output, $lastEvolutionVersion);
            $logger->debug('Récupération et analyse des évolutions à effectuer: OK');
            $output->writeln('Récupération et analyse des évolutions à effectuer: <info>OK</info>');

            // 3. Process all evolutions
            $logger->debug('Exécution des évolutions: en cours');
            $output->writeln('Exécution des évolutions: en cours');
            $this
                ->getContainer()
                ->get('lilweb.evolution_handler')
                ->handle($output, $evolutionContainer);
            $logger->debug('Exécution des évolutions: OK');
            $output->writeln('Exécution des évolutions: <info>OK</info>');
        } catch (\Exception $e) {
            $logger->err($e->getMessage());
            throw $e;
        }

        $logger->debug('Fin de l\'évolution');
    }
}
