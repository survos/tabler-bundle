<?php

/* src/Service/LandingService.php v1.0 - Landing page data service */

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Yaml\Yaml;

class LandingService
{
    private ?array $landingData = null;

    public function __construct(
        #[Autowire('%kernel.project_dir%')] private readonly string $projectDir,
        private readonly string $landingConfigPath = 'config/text/landing.yaml',
    ) {
    }

    public function loadLandingData(): array
    {
        if (null === $this->landingData) {
            $path = $this->projectDir.'/'.$this->landingConfigPath;
            $this->landingData = file_exists($path) ? Yaml::parseFile($path) : [];
        }

        return $this->landingData;
    }

    /**
     * Define the order and which sections to render.
     * Override this in your app's service to customize.
     *
     * @return array<string, array{component: string, enabled: bool}>
     */
    public function getSectionOrder(): array
    {
        return [
            'hero' => ['component' => 'landing:hero', 'enabled' => true],
            'features' => ['component' => 'landing:features', 'enabled' => true],
            'sources' => ['component' => 'landing:sources', 'enabled' => true],
            'process' => ['component' => 'landing:benefits', 'enabled' => true],
            'faq' => ['component' => 'landing:faq', 'enabled' => true],
        ];
    }

    /**
     * Get translation key prefix for a section.
     */
    public function getTranslationPrefix(string $sectionCode): string
    {
        return $sectionCode;
    }
}
