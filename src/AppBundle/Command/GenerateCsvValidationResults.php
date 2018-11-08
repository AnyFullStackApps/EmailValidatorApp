<?php

namespace AppBundle\Command;

use AppBundle\Service\CsvEmailValidator;
use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCsvValidationResults extends Command
{
    //TODO: add progress bar, csv with header/noheader support
    /** @var CsvEmailValidator  */
    private $csvEmailValidator;
    public $csvInputDir;

    public function __construct(CsvEmailValidator $csvEmailValidator, $csvInputDir)
    {
        $this->csvInputDir = $csvInputDir;
        $this->csvEmailValidator = $csvEmailValidator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('csv:generate:validation-results-pack')
            ->addArgument('filename', InputArgument::REQUIRED, 'The name of CSV file.')
            ->setDescription('Validating CSV with emails and generates output files.')
            ->setHelp('This command allows you to validate certain CSV file with emails ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        $reader = Reader::createFromPath($this->csvInputDir.$filename.'.csv');
        $records = $reader->getRecords();
        $this->csvEmailValidator->createCsvEmailCorrect($records);
        $this->csvEmailValidator->createCsvEmailIncorrect($records);
        $this->csvEmailValidator->createValidationWithResume($records);

    }
}