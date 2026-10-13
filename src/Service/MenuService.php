<?php

declare(strict_types=1);

namespace Survos\TablerBundle\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Impersonate\ImpersonateUrlGenerator;

/**
 * Service for menu-related helpers including route security checks.
 */
final class MenuService
{
    public function __construct(
        private readonly array $routeRequirements,
        private readonly ?ImpersonateUrlGenerator $impersonateUrlGenerator,
        private readonly ?AuthorizationCheckerInterface $authorizationChecker,
        private readonly array $usersToImpersonate,
        private readonly ?Security $security,
    ) {
    }

    /**
     * Get security requirements for a route.
     *
     * @return array<string> List of required roles/attributes
     */
    public function getRouteRequirements(string $routeName): array
    {
        return $this->routeRequirements[$routeName] ?? [];
    }

    /**
     * Check if current user can access a route based on IsGranted attributes.
     */
    public function canAccessRoute(string $routeName, mixed $subject = null): bool
    {
        $requirements = $this->getRouteRequirements($routeName);

        if (empty($requirements)) {
            return true;
        }

        if (!$this->authorizationChecker) {
            return true; // No security, allow all
        }

        foreach ($requirements as $attribute) {
            if (!$this->authorizationChecker->isGranted($attribute, $subject)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get all route requirements.
     */
    public function getAllRouteRequirements(): array
    {
        return $this->routeRequirements;
    }

    /**
     * Get impersonate URL for a user identifier.
     */
    public function getImpersonateUrl(string $identifier): ?string
    {
        if (!$this->impersonateUrlGenerator) {
            return null;
        }

        try {
            return $this->impersonateUrlGenerator->generateUrl($identifier);
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Get list of users that can be impersonated.
     */
    public function getUsersToImpersonate(): array
    {
        return $this->usersToImpersonate;
    }

    /**
     * Check if current user is impersonating.
     */
    public function isImpersonating(): bool
    {
        return $this->security?->isGranted('IS_IMPERSONATOR') ?? false;
    }

    /**
     * Get exit impersonation URL.
     */
    public function getExitImpersonationUrl(): ?string
    {
        if (!$this->impersonateUrlGenerator || !$this->isImpersonating()) {
            return null;
        }

        try {
            return $this->impersonateUrlGenerator->generateExitUrl();
        } catch (\Exception) {
            return null;
        }
    }
}
