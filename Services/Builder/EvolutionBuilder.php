<?php
/**
 * User: Geoffrey Brier
 * Date: 21/03/13
 * Time: 14:00
 */
namespace Lilweb\EvolutionBundle\Services\Builder;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

use Monolog\Logger;

use Lilweb\EvolutionBundle\Entity\Evolution;
use Lilweb\EvolutionBundle\Model\EvolutionContainer;
use Lilweb\EvolutionBundle\Services\Parser\EvolutionParser;


/**
 * Builds the EvolutionContainer.
 */
class EvolutionBuilder
{
    /** @var string */
    private $kernelRootDir;

    /** @var \Monolog\Logger */
    private $logger;

    /** @var \Lilweb\EvolutionBundle\Services\Parser\EvolutionParser */
    private $parser;

    /**
     * Constructor.
     *
     * @param string                                                  $kernelRootDir
     * @param \Monolog\Logger                                         $logger
     * @param \Lilweb\EvolutionBundle\Services\Parser\EvolutionParser $parser
     */
    public function __construct($kernelRootDir, Logger $logger, EvolutionParser $parser)
    {
        $this->kernelRootDir = $kernelRootDir;
        $this->logger = $logger;
        $this->parser = $parser;
    }

    /**
     * Builds the evolution container.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Lilweb\EvolutionBundle\Entity\Evolution          $lastVersion
     *
     * @return \Lilweb\EvolutionBundle\Model\EvolutionContainer
     */
    public function build(OutputInterface $output, Evolution $lastVersion = null)
    {
        $container = new EvolutionContainer();
        $finder = new Finder();

        $files = $finder
            ->name('evolution-*.sql')
            ->depth('== 0')
            ->in($this->kernelRootDir.'/evolutions/')
            ->sortByName();

        $this->logger->debug('Construction du container d\'évolution: en cours');
        $output->writeln("\t".'Construction du container d\'évolution: en cours');

        foreach ($files as $file) {
            if (preg_match('/([\d]+).sql/', $file->getFilename(), $match)) {
                if (!ctype_digit($match[1]) || empty($match)) {
                    throw new \Exception($match[1].' n\'est pas un numéro de version correct');
                }

                $version = intval($match[1]);
                if ($lastVersion === null || $version > $lastVersion->getVersion()) {
                    $container->addEvolution(
                        $this->parser->parse($output, $version, $file)
                    );
                }
            } else {
                $output->writeln("\t".'<comment>Fichier '.$file->getFilename().' ignoré</comment>');
            }
        }

        $this->logger->debug('Construction du container d\'évolution: OK');
        $output->writeln("\t".'Construction du container d\'évolution: <info>OK</info>');

        $this->logger->debug($container->getEvolutions()->count().' évolutions à apporter');
        $output->writeln("\t".$container->getEvolutions()->count().' évolutions à apporter');

        return $container;
    }
}