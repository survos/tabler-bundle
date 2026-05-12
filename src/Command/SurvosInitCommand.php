<?php

namespace Survos\BaseBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;

#[AsCommand('survos:init', 'Basic environment: base page, heroku, yarn install, sqlite in .env.local')]
final class SurvosInitCommand
{
    private string $projectDir;
    private string $appCode = '';
    private SymfonyStyle $io;

    const recommendedBundles = ['SurvosWorkflowBundle'];
    const requiredJsLibraries = [
        'jquery', 'sass-loader@^11', 'node-sass', 'simulus', 'Hinclude', '@popperjs/core',
    ];
    const tools = ['heroku', 'easyadmin', 'all'];

    public function __construct(
        private readonly KernelInterface $kernel,
        private readonly EntityManagerInterface $em,
        private readonly ParameterBagInterface $parameterBag,
        private readonly Environment $twig,
    ) {
        $this->projectDir = $kernel->getProjectDir();
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Option('Configure heroku (must be logged in)')] bool $heroku = false,
    ): int {
        $this->io = $io;

        if ($heroku) {
            $this->checkHeroku($io);
            return Command::SUCCESS;
        }

        $this->checkYarn($io);
        $this->installYarnLibraries($io);
        $this->createConfigs($io);
        $this->createFavicon($io);
        $this->createTranslations($io);
        $this->setupDatabase($io);
        $this->updateBase($io);

        $io->success('Run xterm -e "yarn run encore dev-server" & install more bundles, then run bin/console survos:configure');
        return Command::SUCCESS;
    }

    private function getAppCode(): string
    {
        if (empty($this->appCode)) {
            $this->appCode = basename($this->kernel->getProjectDir());
        }
        return $this->appCode;
    }

    private function updateBase(SymfonyStyle $io): void
    {
        $fn = '/templates/base.html.twig';
        if ($io->confirm("Replace $fn?")) {
            $this->writeFile($fn, '{% extends "@SurvosBase/adminkit/layout.html.twig" %}');
        }
    }

    private function createFavicon(SymfonyStyle $io): void
    {
        $host = 'https://favicon.io/favicon-generator/?';
        $params = ['t' => $this->getAppCode(), 'ff' => 'Lancelot', 'fs' => 80, 'b' => 'rounded'];
        $url = $host . http_build_query($params);
        $io->writeln("\n\nDownload zip file at $url");

        $zipFile = 'favicon_io.zip';
        do {
            $path = $io->ask("path to $zipFile? Defaults to directory ABOVE the repo. Use ! to skip", '../');
            $fn = $path . $zipFile;
            if ($path !== '!' && !file_exists($fn)) {
                $this->io->error("$fn does not exist.");
            }
        } while ($path !== '!' && !file_exists($fn));

        if ($path === '!') {
            return;
        }

        $zip = new \ZipArchive();
        if ($zip->open($fn) === true) {
            $publicDir = $this->projectDir . '/./public';
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $io->writeln('Extracting ' . $filename . ' to ' . $publicDir);
                if (!$zip->extractTo($publicDir, [$zip->getNameIndex($i)])) {
                    $io->error(sprintf("Unable to extract %s to %s", $filename, $publicDir));
                }
            }
            $zip->close();
            $io->success('Favicons extracted');
        } else {
            $io->error('Error extracting Favicons');
        }
    }

    private function createTranslations(SymfonyStyle $io): void
    {
        $fn = '/translations/messages+intl-icu.en.yaml';
        if ($io->confirm("Replace $fn?")) {
            $appCode = $io->ask("Short Code?", $this->getAppCode());
            $t = [
                'home' => [
                    'title'       => $title = $io->ask('Title?', "$appCode Title"),
                    'intro'       => "Intro to $title",
                    'description' => $io->ask('description?', "$appCode *Description*, in _markdown_"),
                ],
            ];
            $this->writeFile($fn, Yaml::dump($t, 5));
        }
    }

    private function checkYarn(SymfonyStyle $io): void
    {
        if (!file_exists($this->projectDir . '/yarn.lock')) {
            $io->warning("Installing base yarn libraries with 'yarn install'");
            echo exec('yarn install');
        }
    }

    private function installYarnLibraries(SymfonyStyle $io): void
    {
        if (!file_exists($this->projectDir . '/yarn.lock')) {
            $io->error("run yarn install or bin/console survos:init first");
            return;
        }

        $packageFile = $this->parameterBag->get('kernel.project_dir') . '/package.json';
        $packageData = json_decode(file_get_contents($packageFile));
        $allPackages = array_merge(
            (array) ($packageData->devDependencies ?? []),
            (array) ($packageData->dependencies ?? [])
        );

        $missing = [];
        $requiredJsLibraries = self::requiredJsLibraries;
        $fa = '@fortawesome/fontawesome-free';
        array_push($requiredJsLibraries, $fa);

        foreach ($requiredJsLibraries as $jsLibrary) {
            if (strpos($jsLibrary, '@') > 3) {
                [$package, $version] = explode('@', $jsLibrary);
            } else {
                $package = $jsLibrary;
            }
            if (!array_key_exists($package, $allPackages)) {
                $missing[] = $jsLibrary;
            } else {
                $io->writeln(sprintf("%s installed as version %s", $jsLibrary, $allPackages[$package]));
            }
        }

        if ($missing) {
            $io->error("Missing " . implode(',', $missing));
            $command = sprintf("yarn add %s --dev", implode(' ', $missing));
            if ($io->confirm("Install them now with $command?", true)) {
                echo exec($command) . "\n";
            }
        }
    }

    private function checkHeroku(SymfonyStyle $io): void
    {
        $io->writeln("Checking Heroku");
        $io->writeln(exec("heroku buildpacks:add heroku/php"));
        $io->writeln(exec("heroku buildpacks:add heroku/nodejs"));

        foreach (['/Procfile', '/fpm_custom.conf', '/heroku-nginx.conf'] as $fn) {
            if (!file_exists($this->projectDir . $fn)) {
                $template = basename($fn) . '.twig';
                $this->writeFile($fn, $this->twig->render("@SurvosBase/heroku/$template", []));
            }
        }

        $monologFile = $this->projectDir . '/config/packages/prod/monolog.yaml';
        $data = Yaml::parse(file_get_contents($monologFile));
        $data['monolog']['handlers']['nested']['path'] = 'php://stderr';
        $this->writeFile('/config/packages/prod/monolog.yaml', Yaml::dump($data, 4));
    }

    private function setupDatabase(SymfonyStyle $io): void
    {
        $fn = $this->projectDir . '/.env.local';
        $data = "MAILER_DSN=smtp://localhost\n\n";
        if (!file_exists($fn) && $io->confirm('Use sqlite database in .env.local', true)) {
            $data .= "DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db";
            file_put_contents($fn, $data);
        }
    }

    private function createConfigs(SymfonyStyle $io): void
    {
        if ($prefix = $io->ask("Base Route Prefix", '/')) {
            $fn = '/config/routes/survos_base.yaml';
            $config = [
                'survos_base_bundle_oauth' => [
                    'resource' => '@SurvosBaseBundle/Controller/OAuthController.php',
                    'prefix'   => $prefix,
                    'type'     => 'annotation',
                ],
            ];
            file_put_contents($this->projectDir . $fn, Yaml::dump($config));
            $io->comment($fn . " written.");
        }
    }

    private function writeFile(string $fn, string $contents): void
    {
        file_put_contents($this->projectDir . $fn, $contents);
        $this->io->success($fn . " written.");
    }
}
