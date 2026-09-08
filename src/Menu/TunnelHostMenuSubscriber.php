<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\Service\IconService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * One link that reopens the current page on the app's public tunnel hostname, and back.
 *
 * Local dev domains are private to the machine serving them -- a Symfony proxy .wip domain
 * resolves nowhere else, so anything that has to be opened on a second device (a phone
 * scanning a QR, a webhook sender, someone else's laptop) needs the tunnel hostname
 * instead. Finding that hostname otherwise means remembering it, or digging it out of a
 * cloudflared config, every time.
 *
 * NAVBAR_END is correct for this precisely because it is a flat single-action slot: the
 * item is one link, and navbar_end.html.twig prints item.uri directly without recursing
 * into children (which is why GitHubMenuSubscriber's dropdown had to move to NAVBAR_MENU).
 *
 * The destination host is named in the tooltip rather than left implicit. A link to the
 * wrong host looks exactly like a link to the right one until you follow it -- the same
 * failure mode as an unreachable QR code, and worth the same defence.
 */
final class TunnelHostMenuSubscriber
{
    use MenuBuilderTrait;

    public function __construct(
        private readonly RequestStack $requestStack,
        #[Autowire('%kernel.environment%')]
        private readonly string $environment,
        protected readonly ?IconService $iconService = null,
        /** survos_tabler.yaml's app.tunnel_host, default env TUNNEL_HOST. Bare host or full URL. */
        private readonly ?string $tunnelHost = null,
        /** survos_tabler.yaml's app.local_host, default env APP_BASE_URL. Bare host or full URL. */
        private readonly ?string $localHost = null,
    ) {
    }

    #[AsEventListener(event: MenuEvent::NAVBAR_END)]
    public function onNavbarEnd(MenuEvent $event): void
    {
        // A tunnel points at somebody's laptop. Even with TUNNEL_HOST set by accident in a
        // deployed environment, that is not a link to put in front of production users.
        if ($this->environment === 'prod') {
            return;
        }

        $tunnelHost = $this->normalizeHost($this->tunnelHost);
        $request = $this->requestStack->getCurrentRequest();
        if ($tunnelHost === null || $request === null) {
            return;
        }

        $onTunnel = strcasecmp($request->getHost(), $tunnelHost) === 0;

        // Coming back needs a configured local host; going out does not. An app that never
        // set APP_BASE_URL still gets the outbound half rather than nothing at all.
        $targetHost = $onTunnel ? $this->normalizeHost($this->localHost) : $tunnelHost;
        if ($targetHost === null || strcasecmp($targetHost, $request->getHost()) === 0) {
            return;
        }

        // Path AND query: this exists for deep links -- a capture screen, a specific intake.
        // Dropping the query would land you on the same page with its filters reset, which
        // reads as the switch having broken something.
        $uri = 'https://' . $targetHost . $request->getRequestUri();

        $item = $this->add(
            $event->getMenu(),
            uri: $uri,
            label: $onTunnel ? 'Local' : 'Tunnel',
            icon: $onTunnel ? 'device-desktop' : 'cloud',
        );

        $item->setExtra('tooltip', sprintf('Open this page on %s', $targetHost));
    }

    /**
     * Accepts a bare host or a full URL. These are hand-edited per machine and "https://..."
     * is the shape everyone types first; silently building https://https//host/... would be
     * a poor way to discover the difference.
     */
    private function normalizeHost(?string $value): ?string
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }

        $host = parse_url($raw, PHP_URL_HOST) ?? $raw;

        return trim($host, '/') ?: null;
    }
}
