<?php

namespace Gahlawat\Fixer\Console\Commands;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Fixer extends Command
{
    protected function configure()
    {
        $this->setName('run')
            ->setDescription('Cleans up code')
            ->addArgument('dir', InputArgument::OPTIONAL, 'Clean up this directory', 'src/');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rootDirectory = dirname(__DIR__, 3);
        $rules         = json_encode(require $rootDirectory . '/config/fixer.php');

        $process = new Process([
            'php',
            "{$rootDirectory}/vendor/bin/php-cs-fixer",
            'fix',
            $input->getArgument('dir'),
            "--rules={$rules}",
        ]);

        $process->run();

        if ($process->isSuccessful()) {
            $output->writeln('<info>Code cleaned up!</info>');
        } else {
            $output->writeln('<error>Some error encountered!</error>');
        }

        echo $process->getOutput();
    }
}
