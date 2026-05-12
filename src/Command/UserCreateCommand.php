<?php

namespace Survos\BaseBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Survos\BaseBundle\Event\UserCreatedEvent;

#[AsCommand('survos:user:create', 'Creates a user record with email and password')]
final class UserCreateCommand
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordEncoder,
        private readonly UserProviderInterface $userProvider,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Email address of account')] string $email,
        #[Argument('Plain text password')] ?string $password = null,
        #[Option('Comma-delimited list of roles')] ?string $roles = null,
        #[Option('Update password')] bool $changePassword = false,
        #[Option('Username (defaults to email)')] ?string $username = null,
        #[Option('User class')] string $userclass = 'App\\Entity\\User',
        #[Option('Extra string passed to event dispatcher')] ?string $extra = null,
        #[Option('Change password/roles if account exists')] bool $force = false,
    ): int {
        $action   = 'no-action';
        $username ??= $email;

        try {
            $user = $this->userProvider->loadUserByIdentifier($username);
            if (!$changePassword && !$roles) {
                $io->warning("$email already exists, use --change-password to overwrite the existing password");
            } else {
                $action = 'updated';
            }
        } catch (UserNotFoundException) {
            $action = 'created';
            $user   = new $userclass();
            $user->setEmail($email);
            $this->entityManager->persist($user);
        }

        if ($roles) {
            $user->setRoles(explode(',', $roles));
        }

        if ($password) {
            $user->setPassword($this->passwordEncoder->hashPassword($user, $password));
        }

        $this->eventDispatcher->dispatch(new UserCreatedEvent($user, $extra));
        $this->entityManager->flush();

        if ($io->isVerbose()) {
            $table = new Table($io);
            $table->setHeaders(['Field', 'Value'])
                ->setRows([
                    ['email', $user->getEmail()],
                    ['roles', implode(',', $user->getRoles())],
                ]);
            $table->render();
        }

        $io->success("User $email $action");
        return Command::SUCCESS;
    }
}
