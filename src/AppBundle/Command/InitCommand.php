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
        # Ici, j'injecte le kernel car il sera indispensable pour le suite.
        # Voir $this->execute()
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
        # Sans cette instruction, la commande risquerait de rendre la main sans préavis.
        $application->setAutoExit(false);
        $buffer = new BufferedOutput();

        # Première sous-commande : créer la base de données et conserver
        # la sortie dans un buffer.
        $input = new ArrayInput(['command' => 'doctrine:database:create']);
        $application->run($input, $buffer);

        # La seconde étape (sous-commande) consiste à créer la structure de la base de données
        # à partir des entités.
        $input = new ArrayInput(['command' => 'doctrine:schema:update', '--force' => true]);
        $application->run($input, $buffer);
        $output->writeln([
            "La base de données a été créée !",
            "-------------------------------",
            "Chargement des données ..."
        ]);

        # Troisième étape : on charge les fixtures.
        # On évite toute intéraction. En effet, les intéractions (demande de confirmation)
        # sont en anglais. Cela ferait tâche de mélanger anglais et français.
        $input = new ArrayInput(['command' => 'doctrine:fixtures:load', '--no-interaction' => true]);
        $input->setInteractive(false);
        $application->run($input, $buffer);

        $output->writeln([
            "Les données ont été chargées dans l'application.",
            "-------------------------------------------------",
            "Opération terminée avec succès !"
        ]);
    }
}
