<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Menu;

use Survos\TablerBundle\Service\MenuContext;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Makes the controller's resolved arguments visible to menu listeners, so nobody has to pass an entity to a menu:
 *
 *     #[Route('/{publication}/issue/{issue}')]
 *     public function issue(Publication $publication, Issue $issue) { ... }
 *
 * and any listener can `$event->getOption('publication')` / `getOption('issue')` — the Doctrine entities the
 * argument resolvers already loaded, keyed by the parameter name. Only objects are taken (a string route parameter is a code, not an entity); the Request and the
 * session are left out. The template's own variables, tabler_menu_options() and tabler_menu() options all outrank them.
 */
final class AmbientMenuContext
{
    public function __construct(private readonly MenuContext $menuContext) {}

    #[AsEventListener(event: KernelEvents::CONTROLLER_ARGUMENTS)]
    public function onControllerArguments(ControllerArgumentsEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $options = [];
        foreach ($event->getNamedArguments() as $name => $value) {
            // Objects only: a string `{publication}` route parameter is a code, not the entity a listener expects.
            if (!is_object($value) || $value instanceof Request || $value instanceof SessionInterface) {
                continue;
            }
            $options[$name] = $value;
        }

        $this->menuContext->addAmbient($options);
    }
}
