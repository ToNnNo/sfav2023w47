<?php

namespace App\Command;

use App\Command\Service\ServiceClassGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function Symfony\Component\String\u;

#[AsCommand(
    name: 'app:service:create',
    description: 'Create a new service class',
    aliases: ['make:service']
)]
class ExampleCommand extends Command
{
    public function __construct(
        private readonly ServiceClassGenerator $classGenerator
    )
    {
        // si le constructeur existe alors faire appel au constructeur parent !
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Nom du service')
            // ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        $serviceName = $input->getArgument('name');
        if(!$serviceName) {
            $name = $io->ask('Enter the service name', validator: function($answer) {
                if(!$answer) {
                    throw new \RuntimeException('The service name cannot be null');
                }

                return $answer;
            });

            $input->setArgument('name', $name);
        }
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $className = u($name)->camel()->title();
        $namespace = "App\\Service\\".$className;

        if(class_exists($namespace)) {
            throw new \Exception('Service already exists');
        }

        // create file service and content
        $this->classGenerator->generateClass($className);

        $io->text(sprintf("<fg=blue>created</>: src/Service/%s.php", $className));
        $io->success('Success !');
        $io->text("Next: Ouvrez votre service et ajoutez votre code");

        return Command::SUCCESS;
    }
}
