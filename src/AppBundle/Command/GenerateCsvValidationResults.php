<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCsvValidationResults extends Command
{
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
        return "test";
    }
}