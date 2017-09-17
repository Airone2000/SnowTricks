<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class InitCommand extends Command
{
    private $kernel;

    public function __construct($name = null, KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('snowtricks:start')
            ->setDescription("Initialisation de l'application");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            "Initialisation de l'application",
            "===============================",
            "Veuillez patienter"
        ]);

        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        $buffer = new BufferedOutput();


        $input = new ArrayInput(['command' => 'doctrine:database:create']);
        $application->run($input, $buffer);

        $input = new ArrayInput(['command' => 'doctrine:schema:update', '--force' => true]);
        $application->run($input, $buffer);
        $output->writeln([
            "La base de données a été créée !",
            "-------------------------------"
        ]);

        $input = new ArrayInput(['command' => 'doctrine:fixtures:load']);
        $application->run($input);

        $output->writeln([
            "Les données ont été chargées dans l'application.",
            "-------------------------------------------------",
            "Opération terminée avec succès !"
        ]);
    }
}