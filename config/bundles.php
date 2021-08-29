<?php

return [
    App\SharedKernel\Infrastructure\SymfonyIntegration\SharedKernelBundle::class => ['all' => true],
    App\User\Infrastructure\SymfonyIntegration\UserBundle::class => ['all' => true],
    App\Employer\Infrastructure\SymfonyIntegration\EmployerBundle::class => ['all' => true],

    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],

    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],
];
