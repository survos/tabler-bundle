<?php

namespace Survos\BaseBundle\Command;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Twig\Environment;

#[AsCommand('survos:build-docs', 'Compile .rst.twig files')]
final class SurvosBuildDocsCommand
{
    public function __construct(private readonly Environment $twig)
    {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Template Directory')] string $templateDir = './templates/',
        #[Argument('Template Subdirectory')] string $templateSubdir = 'docs/',
        #[Option('Output Directory (the .rst file)')] string $outputDir = './docs',
    ): int {
        $finder = new Finder();
        $finder->files()->in($templateDir . $templateSubdir);

        foreach ($finder as $file) {
            $rst = $this->twig->render($templateSubdir . $file->getBasename(), []);
            $outputFilename = $outputDir . $file->getBasename('.twig');
            file_put_contents($outputFilename, $rst);
            $io->writeln("$outputFilename written.");
        }

        $io->success('Templates compiled, now run make html');

        return Command::SUCCESS;
    }
}
