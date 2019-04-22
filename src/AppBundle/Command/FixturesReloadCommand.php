<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FixturesReloadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:fixturesReload')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Drop/Create Database and load Fixtures ....')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Drop/Create Database and load Fixtures ....')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();
        $application->setAutoExit(false);

        $output->writeln([
            '===================================================',
            '*********        Dropping DataBase        *********',
            '===================================================',
            '',
        ]);

        $options = array('command' => 'doctrine:database:drop',"--force" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));


        $output->writeln([
            '===================================================',
            '*********        Creating DataBase        *********',
            '===================================================',
            '',
        ]);

        $options = array('command' => 'doctrine:database:create',"--if-not-exists" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********         Updating Schema         *********',
            '===================================================',
            '',
        ]);

        //Create de Schema
        $options = array('command' => 'doctrine:schema:update',"--force" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));

        $output->writeln([
            '===================================================',
            '*********          Load Fixtures          *********',
            '===================================================',
            '',
        ]);

        //Loading Fixtures
        $options = array('command' => 'doctrine:fixtures:load',"--no-interaction" => true);
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }

}
