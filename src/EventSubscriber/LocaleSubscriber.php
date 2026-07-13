<?php

declare(strict_types=1);

namespace Survos\TablerBundle\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Survos\TablerBundle\Contract\HasPreferredLocaleInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Resolves the request locale, in order: explicit ?_locale= switch, session,
 * the logged-in user's stored preference, Accept-Language, default_locale.
 * Runs after routing (32) but before the framework's own LocaleListener (16)
 * and LocaleAwareListener (15), so the locale it sets is what actually reaches
 * the translator and other locale-aware services for this request.
 *
 * Persisting to the user is optional: if the app's User class doesn't
 * implement HasPreferredLocaleInterface (e.g. via PreferredLocaleTrait), or
 * Doctrine ORM isn't installed, this falls back to session-only switching.
 */
final class LocaleSubscriber implements EventSubscriberInterface
{
    private const string SESSION_KEY = '_locale';

    public function __construct(
        private readonly array $enabledLocales,
        private readonly ?Security $security = null,
        private readonly ?EntityManagerInterface $em = null,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => ['onKernelRequest', 20]];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        // API Platform marks stateless operations with the `_stateless` request attribute; touching
        // the session here (even a read) would trip AbstractSessionListener's "session used while
        // declared stateless" guard, so skip locale/session resolution entirely for those requests.
        if ($request->attributes->get('_stateless', false)) {
            return;
        }

        $user = $this->security?->getUser();

        $switchedLocale = $this->resolveValid($request->query->get('_locale'));
        if ($switchedLocale !== null) {
            $this->persist($request, $switchedLocale, $user);
            $request->setLocale($switchedLocale);

            return;
        }

        $session = $request->hasSession() ? $request->getSession() : null;
        $sessionLocale = $this->resolveValid($session?->get(self::SESSION_KEY));
        if ($sessionLocale !== null) {
            $request->setLocale($sessionLocale);

            return;
        }

        $userLocale = $user instanceof HasPreferredLocaleInterface ? $this->resolveValid($user->getPreferredLocale()) : null;
        if ($userLocale !== null) {
            $session?->set(self::SESSION_KEY, $userLocale);
            $request->setLocale($userLocale);

            return;
        }

        $negotiated = $request->getPreferredLanguage($this->enabledLocales);
        if ($negotiated !== null) {
            $request->setLocale($negotiated);
        }
    }

    private function resolveValid(mixed $locale): ?string
    {
        return \is_string($locale) && \in_array($locale, $this->enabledLocales, true) ? $locale : null;
    }

    private function persist(Request $request, string $locale, mixed $user): void
    {
        $request->getSession()->set(self::SESSION_KEY, $locale);

        if ($user instanceof HasPreferredLocaleInterface && $this->em !== null && $user->getPreferredLocale() !== $locale) {
            $user->setPreferredLocale($locale);
            $this->em->flush();
        }
    }
}
