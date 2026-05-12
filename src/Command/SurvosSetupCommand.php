<?php

namespace Survos\BaseBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;

#[AsCommand('survos:configure', 'Setup libraries and basic base page')]
final class SurvosSetupCommand
{
    private string $projectDir;
    private SymfonyStyle $io;

    const recommendedBundles = [
        'SurvosWorkflowBundle' => ['repo' => 'survos/workflow-bundle'],
    ];

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly EntityManagerInterface $em,
        private readonly Environment $twig,
    ) {
        $this->projectDir = $kernel->getProjectDir();
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $this->io = $io;

        $this->createSubscribers($io);
        $bundles = $this->checkBundles($io);
        $this->updateAssets($io, ['bundles' => $bundles, 'yarnPackages' => []]);

        $io->success('Base Configuration Complete.');
        return Command::SUCCESS;
    }

    private function createSubscribers(SymfonyStyle $io): void
    {
        $dir = $this->projectDir . '/src/EventSubscriber';
        $fn  = $dir . '/SidebarMenuSubscriber.php';

        if (!file_exists($fn)) {
            if ($prefix = $io->ask("Application Menu Subscriber Class", 'App/EventSubscriber/SidebarMenuSubscriber')) {
                if (!is_dir($dir)) {
                    mkdir($dir);
                }
                $php = $this->twig->render("@SurvosBase/MenuSubscriber.php.twig", []);
                file_put_contents($fn, $php);
                $io->comment($fn . " written.");
            }
        }
    }

    private function updateAssets(SymfonyStyle $io, array $params): void
    {
        if ($io->confirm("Replace app assets (js and css)?")) {
            try {
                $file = $this->projectDir . '/webpack.config.js';
                $this->writeFile('/./webpack.config.js',
                    str_replace('//.enableSassLoader()', '.enableSassLoader()', file_get_contents($file)));
                $this->writeFile('/assets/app.js', $this->twig->render("@SurvosBase/app.js.twig", $params));
                $this->writeFile('/assets/styles/app.scss', $this->twig->render("@SurvosBase/app.scss.twig", $params));
            } catch (\Exception $e) {
                $io->error($e->getMessage());
            }
        }

        echo exec('yarn run encore dev');
    }

    private function checkBundles(SymfonyStyle $io): array
    {
        $bundles = $this->kernel->getBundles();

        foreach (self::recommendedBundles as $bundleName => $info) {
            if (empty($bundles[$bundleName])) {
                $io->warning($bundleName . ' is recommended, install it using composer req ' . $info['repo']);
            }
        }

        return $bundles;
    }

    private function writeFile(string $fn, string $contents): void
    {
        file_put_contents($this->projectDir . $fn, $contents);
        $this->io->success($fn . " written.");
    }
}
