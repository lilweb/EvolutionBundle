<?php
/**
 * User: Geoffrey Brier
 * Date: 21/03/13
 * Time: 14:00
 */
namespace Lilweb\EvolutionBundle\Services\Parser;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\SplFileInfo;

use Monolog\Logger;

use Lilweb\EvolutionBundle\Model\Evolution;
use Lilweb\EvolutionBundle\Model\Sql\EvolutionUps;
use Lilweb\EvolutionBundle\Model\Sql\EvolutionDowns;
use Lilweb\EvolutionBundle\Exception\EvolutionParserException;

/**
 * Parse un fichier pour peupler un objet Evolution.
 */
class EvolutionParser
{
    /** @var \Monolog\Logger */
    private $logger;

    /**
     * Constructor.
     *
     * @param \Monolog\Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Parse $file to build an Evolution object.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param integer                                           $version
     * @param \Symfony\Component\Finder\SplFileInfo             $file
     *
     * @return \Lilweb\EvolutionBundle\Model\Evolution
     */
    public function parse(OutputInterface $output, $version, SplFileInfo $file)
    {
        $evolution = new Evolution();
        $ups = new EvolutionUps();
        $downs = new EvolutionDowns();

        $evolution->setVersion($version);
        $evolution->setFile($file);
        $evolution->setUps($ups);
        $evolution->setDowns($downs);

        if (($fd = fopen($file->getRealPath(), 'r')) === false) {
            throw EvolutionParserException::cannotReadFile();
        }

        $mode = '';
        $lineNumber = 1;

        $this->logger->debug('Parsing: en cours');
        $output->write("\t\t Parsing de l'évolution #".$version.' ... ');
        while (($line = fgets($fd)) !== false) {
            $line = trim($line);

            if ($line === '# --- !Ups') {
                $mode = 'ups';
            } else if ($line === '# --- !Downs') {
                $mode = 'downs';
            } else if (empty($mode) && !empty($line) && strpos($line, '-- ') !== 0 && strpos($line, '#') !== 0) {
                $output->writeln('<error>FAIL</error>');
                throw EvolutionParserException::cannotParseFile($lineNumber);
            } else if (strpos($line, '-- ') !== 0 && strpos($line, '#') !== 0 && !empty($mode)) {
                ${$mode}->append($line.PHP_EOL);
            }

            $lineNumber++;
        }

        fclose($fd);

        if ($ups->isEmpty()) {
            $output->writeln('<error>FAIL</error>');
            throw EvolutionParserException::missingUps();
        } else if ($downs->isEmpty()) {
            $output->writeln('<error>FAIL</error>');
            throw EvolutionParserException::missingDowns();
        }

        $this->logger->debug('Parsing: OK');
        $output->writeln('<info>OK</info>');

        return $evolution;
    }
}