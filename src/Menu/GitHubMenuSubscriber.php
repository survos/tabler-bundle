<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\IconService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;

final class GitHubMenuSubscriber
{
    use MenuBuilderTrait;

    public function __construct(
        private readonly string $projectDir,
        private readonly RequestStack $requestStack,
        protected readonly ?IconService $iconService = null,
    ) {}

    #[AsEventListener(event: MenuEvent::ADMIN_NAVBAR_MENU)]
    public function onAdminNavbarMenu(MenuEvent $event): void
    {
        $repoUrl = $this->githubRepoUrl();
        if (!$repoUrl) {
            return;
        }

        $submenu = $this->addSubmenu($event->getMenu(), 'GitHub', 'github');
        $this->add($submenu, uri: $repoUrl, label: 'Repo', icon: 'github', external: true);
        $this->add($submenu, uri: $repoUrl . '/issues', label: 'Issues', icon: 'issues', external: true);
        $this->add($submenu, uri: $this->newIssueUrl($repoUrl), label: 'New issue', icon: 'plus', external: true);
    }

    private function githubRepoUrl(): ?string
    {
        $config = $this->gitConfig();
        if (!$config) {
            return null;
        }

        if (!preg_match_all('/^\s*url\s*=\s*(\S+)\s*$/m', $config, $matches)) {
            return null;
        }

        foreach ($matches[1] as $remoteUrl) {
            $repoUrl = $this->normalizeGitHubUrl($remoteUrl);
            if ($repoUrl) {
                return $repoUrl;
            }
        }

        return null;
    }

    private function gitConfig(): ?string
    {
        $gitPath = $this->projectDir . '/.git';
        if (is_file($gitPath . '/config')) {
            return file_get_contents($gitPath . '/config') ?: null;
        }

        if (!is_file($gitPath)) {
            return null;
        }

        $gitDir = trim((string) preg_replace('/^gitdir:\s*/', '', trim((string) file_get_contents($gitPath))));
        if ($gitDir === '') {
            return null;
        }

        if (!str_starts_with($gitDir, '/')) {
            $gitDir = $this->projectDir . '/' . $gitDir;
        }

        foreach ([$gitDir . '/config', $this->commonGitConfig($gitDir)] as $configFile) {
            if ($configFile && is_file($configFile)) {
                return file_get_contents($configFile) ?: null;
            }
        }

        return null;
    }

    private function commonGitConfig(string $gitDir): ?string
    {
        $commonDirFile = $gitDir . '/commondir';
        if (!is_file($commonDirFile)) {
            return null;
        }

        $commonDir = trim((string) file_get_contents($commonDirFile));
        if ($commonDir === '') {
            return null;
        }

        if (!str_starts_with($commonDir, '/')) {
            $commonDir = $gitDir . '/' . $commonDir;
        }

        return $commonDir . '/config';
    }

    private function normalizeGitHubUrl(string $remoteUrl): ?string
    {
        $remoteUrl = preg_replace('/\.git$/', '', trim($remoteUrl)) ?? '';

        if (preg_match('#^git@github\.com:(?<path>[^/]+/[^/]+)$#', $remoteUrl, $matches)) {
            return 'https://github.com/' . $matches['path'];
        }

        if (preg_match('#^ssh://git@github\.com/(?<path>[^/]+/[^/]+)$#', $remoteUrl, $matches)) {
            return 'https://github.com/' . $matches['path'];
        }

        if (preg_match('#^https://github\.com/(?<path>[^/]+/[^/]+)$#', $remoteUrl, $matches)) {
            return 'https://github.com/' . $matches['path'];
        }

        return null;
    }

    private function newIssueUrl(string $repoUrl): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $pageUrl = $request?->getUri();
        $body = $pageUrl ? "Page: {$pageUrl}\n\nProblem:\n" : "Problem:\n";

        return $repoUrl . '/issues/new?' . http_build_query([
            'title' => 'Issue from ' . ($request?->getPathInfo() ?: 'admin'),
            'body' => $body,
        ]);
    }
}
