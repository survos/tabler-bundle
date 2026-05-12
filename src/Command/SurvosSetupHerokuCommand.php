<?php

namespace Survos\BaseBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Twig\Environment;

#[AsCommand('survos:setup-heroku', 'Creates files for heroku deployment')]
final class SurvosSetupHerokuCommand
{
    public function __construct(private readonly Environment $twig)
    {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $procfile = $this->twig->render("@SurvosBase/Procfile.twig", []);

        // heroku init
        // setup ENV vars
        // tweak monolog: https://devcenter.heroku.com/articles/deploying-symfony3#changing-the-log-destination-for-production
        // add node: heroku buildpacks:add --index 2 heroku/nodejs

        $io->success('Heroku setup complete.');

        return Command::SUCCESS;
    }
}
