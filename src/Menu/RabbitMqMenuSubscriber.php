<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Survos\TablerBundle\Event\MenuEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * Adds a link to the RabbitMQ management console when state-bundle is installed
 * AND actually configured to use it (survos_state.queue_driver: rabbitmq).
 *
 * tabler has no hard dependency on state-bundle or jwage/phpamqplib-messenger:
 * $queueDriver/$asyncTransportDsn are only bound (in SurvosTablerBundle::process())
 * when the "survos_state.queue_driver" container parameter exists, so this is a
 * silent no-op for apps that don't have state-bundle, or that have it on the
 * (default) doctrine driver.
 */
final class RabbitMqMenuSubscriber
{
    public function __construct(
        private readonly ?string $queueDriver = null,
        private readonly ?string $asyncTransportDsn = null,
    ) {}

    #[AsEventListener(event: MenuEvent::ADMIN_NAVBAR_MENU)]
    public function onAdminNavbarMenu(MenuEvent $event): void
    {
        if ($this->queueDriver !== 'rabbitmq' || !$this->asyncTransportDsn) {
            return;
        }

        $url = self::managementUrlFromDsn($this->asyncTransportDsn);
        if (!$url) {
            return;
        }

        $event->getMenu()->addChild('RabbitMQ', [
            'uri' => $url,
            'linkAttributes' => ['target' => '_blank'],
        ]);
    }

    private static function managementUrlFromDsn(string $dsn): ?string
    {
        // Still an unresolved "%env(...)%" placeholder at this point — nothing to link to.
        if (str_contains($dsn, '%env(')) {
            return null;
        }

        $host = parse_url($dsn, PHP_URL_HOST);
        if (!$host) {
            return null;
        }

        $secure = str_starts_with($dsn, 'phpamqplibs://') || str_starts_with($dsn, 'amqps://');

        return sprintf('%s://%s:%d', $secure ? 'https' : 'http', $host, $secure ? 15671 : 15672);
    }
}
