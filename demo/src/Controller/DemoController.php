<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DemoController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function home(): Response
    {
        return $this->render('demo/home.html.twig', [
            'section' => 'Overview',
            'summary' => 'A minimal Symfony 8 app that exercises the slot-driven Tabler base layout.',
        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(): Response
    {
        return $this->render('demo/page.html.twig', [
            'title' => 'Admin',
            'pretitle' => 'High-level',
            'summary' => 'Top-level administration area used to expose global actions and overview navigation.',
        ]);
    }

    #[Route('/tenants', name: 'app_tenants')]
    public function tenants(): Response
    {
        return $this->render('demo/page.html.twig', [
            'title' => 'Tenants',
            'pretitle' => 'Admin',
            'summary' => 'Tenant list page showing slot-driven breadcrumbs, actions, and contextual sidebar links.',
        ]);
    }

    #[Route('/tenants/{tenant}', name: 'app_tenant_show')]
    public function tenant(string $tenant): Response
    {
        return $this->render('demo/page.html.twig', [
            'title' => sprintf('Tenant %s', ucfirst($tenant)),
            'pretitle' => 'Tenant',
            'summary' => 'Tenant overview page. Use this to test entity-level context and secondary navigation.',
            'entity' => $tenant,
        ]);
    }

    #[Route('/tenants/{tenant}/intakes', name: 'app_tenant_intakes')]
    public function intakes(string $tenant): Response
    {
        return $this->render('demo/page.html.twig', [
            'title' => 'Intakes',
            'pretitle' => sprintf('Tenant %s', ucfirst($tenant)),
            'summary' => 'Collection intake workflow page. Good for testing entity -> related entity navigation.',
            'entity' => $tenant,
        ]);
    }

    #[Route('/tenants/{tenant}/images', name: 'app_tenant_images')]
    public function images(string $tenant): Response
    {
        return $this->render('demo/page.html.twig', [
            'title' => 'Images',
            'pretitle' => sprintf('Tenant %s', ucfirst($tenant)),
            'summary' => 'Related media list used to demonstrate hierarchical slot navigation.',
            'entity' => $tenant,
        ]);
    }

    #[Route('/museums', name: 'app_museums')]
    public function museums(): Response
    {
        return $this->render('demo/page.html.twig', [
            'title' => 'Museums',
            'pretitle' => 'Collection',
            'summary' => 'Museum listing page for the second hierarchy example: Museum -> Epochs -> Search/List/Edit.',
        ]);
    }

    #[Route('/museums/{museum}/epochs', name: 'app_museum_epochs')]
    public function epochs(string $museum): Response
    {
        return $this->render('demo/page.html.twig', [
            'title' => 'Epochs',
            'pretitle' => sprintf('Museum %s', ucfirst($museum)),
            'summary' => 'Related entity page to exercise breadcrumbs and page actions under a different hierarchy.',
            'entity' => $museum,
        ]);
    }

    #[Route('/search', name: 'app_search')]
    public function search(): Response
    {
        return $this->render('demo/page.html.twig', [
            'title' => 'Search',
            'pretitle' => 'Tools',
            'summary' => 'Search/list/edit style page to verify utility slots and page-level actions.',
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('demo/page.html.twig', [
            'title' => 'Profile',
            'pretitle' => 'Auth',
            'summary' => 'Stub profile page used by the AUTH slot dropdown.',
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('demo/page.html.twig', [
            'title' => 'Login',
            'pretitle' => 'Auth',
            'summary' => 'Placeholder login page. Real projects can swap this route for their own auth bundle.',
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): never
    {
        throw new \LogicException('Configure logout in the firewall to activate this route.');
    }
}
