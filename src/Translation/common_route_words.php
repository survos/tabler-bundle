<?php

declare(strict_types=1);

/**
 * Curated translations for route-name suffixes shared across apps (e.g. "tenant_about" and
 * "app_about" both end in "about"). RoutesTranslationLoader checks this dictionary, keyed by
 * the route name's last underscore-separated segment, before falling back to auto-humanizing
 * the raw route name. One shared file so every app using tabler-bundle benefits without each
 * having to translate the same handful of common concepts on its own.
 *
 * Add a locale here only once real content backs it — an empty array is worse than no entry:
 * it would silently disable the auto-humanization fallback for every route in that locale.
 *
 * @return array<string, array<string, string>> locale => word => translation
 */
return [
    'en' => [
        'home' => 'Home',
        'homepage' => 'Home',
        'about' => 'About',
        'contact' => 'Contact',
        'settings' => 'Settings',
        'profile' => 'Profile',
        'search' => 'Search',
        'login' => 'Login',
        'logout' => 'Logout',
        'register' => 'Register',
        'privacy' => 'Privacy',
        'terms' => 'Terms',
        'show' => 'Details',
    ],
    'hu' => [
        'home' => 'Kezdőlap',
        'homepage' => 'Kezdőlap',
        'about' => 'Rólunk',
        'contact' => 'Kapcsolat',
        'settings' => 'Beállítások',
        'profile' => 'Profil',
        'search' => 'Keresés',
        'login' => 'Bejelentkezés',
        'logout' => 'Kijelentkezés',
        'register' => 'Regisztráció',
        'privacy' => 'Adatvédelem',
        'terms' => 'Feltételek',
        'show' => 'Részletek',
    ],
    'fr' => [
        'home' => 'Accueil',
        'homepage' => 'Accueil',
        'about' => 'À propos',
        'contact' => 'Contact',
        'settings' => 'Paramètres',
        'profile' => 'Profil',
        'search' => 'Recherche',
        'login' => 'Connexion',
        'logout' => 'Déconnexion',
        'register' => 'Inscription',
        'privacy' => 'Confidentialité',
        'terms' => 'Conditions',
        'show' => 'Détails',
    ],
];
