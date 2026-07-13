<?php

declare(strict_types=1);

namespace Survos\TablerBundle\EventSubscriber;

use Inspector\Inspector;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Drops 404 transactions before they're sent to inspector.dev, so bot/crawler
 * probes against nonexistent routes don't burn ingestion credits.
 *
 * ignore_routes (config/packages/inspector.yaml) can't do this: it filters by
 * matched route name, but most bot probes never match a route at all, so
 * there's nothing to name. Filtering on the transaction result (set to the
 * response status code) catches those too.
 *
 * Registration is guarded by class_exists() in
 * SurvosTablerBundle::loadExtension(), so this bundle stays a soft dependency
 * on inspector-apm/inspector-symfony.
 */
final class InspectorNoiseFilterSubscriber
{
    private static bool $registered = false;

    #[AsEventListener(event: KernelEvents::REQUEST, priority: 1024)]
    public function onKernelRequest(RequestEvent $event): void
    {
        if (self::$registered) {
            return;
        }
        self::$registered = true;

        Inspector::beforeFlush(static fn (Inspector $inspector): bool => $inspector->transaction()?->result !== '404');
    }
}
