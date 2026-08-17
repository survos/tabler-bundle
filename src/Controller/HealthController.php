<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Controller;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Liveness endpoint for deploy healthchecks (dokku CHECKS / app.json healthchecks,
 * Kubernetes probes, uptime monitors).
 *
 * Deliberately the cheapest possible 200: no database, no session, no template, no
 * translation. It answers exactly one question — "did this container boot and can it
 * route a request?" — which is the question a deploy gate should ask.
 *
 * Pointing a startup check at a real page instead is the trap this exists to fix. A
 * dashboard or search page drags in the database and the search backend, so a slow
 * index makes the check time out and the deploy roll back an application that was
 * fine. It also makes the gate as slow as the heaviest page, when the whole point of
 * a startup check is a fast deploy.
 *
 * Correspondingly it does NOT prove dependencies are healthy: it returns 200 with an
 * unreachable database. That is the intended trade for a liveness probe. A readiness
 * check that verifies dependencies is a different endpoint with different failure
 * semantics, and should not gate the deploy.
 *
 * Not an AbstractController subclass on purpose: it needs nothing from the container,
 * and the base class exists to provide helpers this endpoint must not use.
 *
 * Lives here because nearly every Survos app installs tabler-bundle, so they all get
 * the endpoint for free. Note it inherits the bundle's configurable route prefix
 * (default ''), so an app that sets survos_tabler.route_prefix moves this path too.
 */
final class HealthController
{
    public function __construct(
        #[Autowire('%kernel.environment%')]
        private readonly string $environment,
    ) {
    }

    #[Route('/health', name: 'survos_tabler_health', methods: ['GET', 'HEAD'])]
    public function health(): Response
    {
        $response = new JsonResponse([
            'status' => 'ok',
            'env' => $this->environment,
        ]);

        // Never cached: a healthcheck answered from a CDN or proxy cache reports the
        // health of whatever was alive when the entry was stored. packages.survos.com
        // is Cloudflare-proxied, which makes this load-bearing rather than tidy.
        $response->setPrivate();
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate');

        return $response;
    }
}
