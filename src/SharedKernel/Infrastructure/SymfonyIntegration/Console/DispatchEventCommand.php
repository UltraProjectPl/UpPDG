<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\Console;

use App\SharedKernel\Application\Bus\EventBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DispatchEventCommand extends Command
{
    protected static $defaultName = 'app:event:dispatch';

    public function __construct(private EventBusInterface $eventBus)
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Dispatch event of specified class')
            ->addArgument('event', InputArgument::REQUIRED, 'The event class.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $eventClass = $input->getArgument('event');
        $event = new $eventClass();

        $output->writeln('Dispatching event of class ' . $eventClass);

        $this->eventBus->dispatch($event);

        return 0;
    }
}
