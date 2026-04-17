<?php

namespace Survos\TablerBundle;

use App\Model\MenuItem;
use Survos\TablerBundle\Compiler\Configuration;
use Survos\TablerBundle\Components\CardComponent;
use Survos\TablerBundle\Components\DividerComponent;
use Survos\TablerBundle\Components\LocaleSwitcherComponent;
use Survos\TablerBundle\Components\MenuComponent;
use Survos\TablerBundle\Components\PageComponent;
use Survos\TablerBundle\Components\Ui\DropdownComponent;
use Survos\TablerBundle\Event\MenuEvent;
use Survos\TablerBundle\EventSubscriber\DebugMenuSlotsSubscriber;
use Survos\TablerBundle\Service\ContextService;
use Survos\TablerBundle\Service\IconService;
use Survos\TablerBundle\Service\LandingService;
use Survos\TablerBundle\Service\MenuDispatcher;
use Survos\TablerBundle\Service\MenuContext;
use Survos\TablerBundle\Service\MenuOptionsResolver;
use Survos\TablerBundle\Service\MenuRenderer;
use Survos\TablerBundle\Service\MenuService;
use Survos\TablerBundle\Service\PageContext;
use Survos\TablerBundle\Service\RouteAliasService;
use Survos\TablerBundle\Translation\RoutesTranslationLoader;
use Survos\TablerBundle\Twig\Components\Landing\Benefits;
use Survos\TablerBundle\Twig\Components\Landing\Faq;
use Survos\TablerBundle\Twig\Components\Landing\Features;
use Survos\TablerBundle\Twig\Components\Landing\Hero;
use Survos\TablerBundle\Twig\Components\Landing\Sources;
use Survos\TablerBundle\Twig\Components\MiniCard;
use Survos\TablerBundle\Twig\Components\TablerHead;
use Survos\TablerBundle\Twig\Components\TablerHeader;
use Survos\TablerBundle\Twig\Components\TablerIcon;
use Survos\TablerBundle\Twig\Components\TablerPageHeader;
use Survos\TablerBundle\Twig\IconExtension;
use Survos\TablerBundle\Twig\MenuExtension;
use Survos\TablerBundle\Twig\RouteAliasExtension;
use Survos\TablerBundle\Twig\TwigExtension;
use Survos\CoreBundle\Bundle\AssetMapperBundle;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SurvosTablerBundle extends AssetMapperBundle implements CompilerPassInterface
{
    public const ASSET_PACKAGE = 'tabler';

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass($this);
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        parent::prependExtension($container, $builder);

        // ux_icons config
        if (!$builder->hasExtension('ux_icons')) {
            return;
        }

        $configFile = __DIR__ . '/../config/packages/ux_icons.yaml';
        if (!file_exists($configFile)) {
            return;
        }

        $config = \Symfony\Component\Yaml\Yaml::parseFile($configFile);
        if (!isset($config['ux_icons']) || !is_array($config['ux_icons'])) {
            return;
        }

        $builder->prependExtensionConfig('ux_icons', $config['ux_icons']);
    }

    public function process(ContainerBuilder $container): void
    {
        // Collect route security requirements from IsGranted attributes
        $routeRequirements = $this->collectRouteRequirements($container);
        $container->setParameter('survos_tabler.route_requirements', $routeRequirements);

        // Set up Twig globals
        $this->configureTwigGlobals($container);
    }

    private function collectRouteRequirements(ContainerBuilder $container): array
    {
        $requirements = [];
        $taggedServices = $container->findTaggedServiceIds('container.service_subscriber');

        foreach (array_keys($taggedServices) as $controllerClass) {
            if (!class_exists($controllerClass)) {
                continue;
            }

            $reflectionClass = new \ReflectionClass($controllerClass);

            // Controller-level IsGranted attributes
            $controllerRequirements = [];
            foreach ($reflectionClass->getAttributes(IsGranted::class) as $attribute) {
                $controllerRequirements = $attribute->getArguments();
            }

            // Method-level attributes
            foreach ($reflectionClass->getMethods() as $method) {
                $methodRequirements = [];
                foreach ($method->getAttributes(IsGranted::class) as $attribute) {
                    $methodRequirements = $attribute->getArguments();
                }

                // Get route name(s) and associate requirements
                foreach ($method->getAttributes(Route::class) as $attribute) {
                    $args = $attribute->getArguments();
                    $routeName = $args['name'] ?? $method->getName();
                    $requirements[$routeName] = array_merge($methodRequirements, $controllerRequirements);
                }
            }
        }

        return $requirements;
    }

    private function configureTwigGlobals(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('twig')) {
            return;
        }

        $twigDef = $container->getDefinition('twig');

        foreach (MenuEvent::getConstants() as $name => $value) {
            $twigDef->addMethodCall('addGlobal', [$name, $value]);
        }
        $twigDef->addMethodCall('addGlobal', ['menuSlots', array_keys(MenuEvent::getConstants())]);

        // Theme from config
        if ($container->hasParameter('survos_tabler.theme')) {
            $twigDef->addMethodCall('addGlobal', ['theme', $container->getParameter('survos_tabler.theme')]);
        }

        if ($container->hasParameter('survos_tabler.app')) {
            $twigDef->addMethodCall('addGlobal', ['tabler_identity', $container->getParameter('survos_tabler.app')]);
        }

    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {

        $builder->setParameter('survos_tabler.app', $config['app'] ?? []);

        // Load generated component services
        $container->import('../config/component-services.php');

        foreach([CardComponent::class, DividerComponent::class] as $componentClass) {
            $builder->register($componentClass)
                ->setAutowired(true)
                ->setAutoconfigured(true);
        }


        // find all the routes and create sensible translations from the route name.  We _could_ provide a hint in options
        // === Translation Loader ===
        $builder->autowire('survos.tabler_translations', RoutesTranslationLoader::class)
            ->setAutowired(true)
            ->setAutoconfigured(true)
//            ->setArgument('$controllers', new IteratorArgument($controllerRefs))
            ->addTag('translation.loader', ['alias' => 'bin']);

        // === Parameters ===
        $builder->setParameter('survos_tabler.config', $config);
        $builder->setParameter('survos_tabler.routes', $config['routes']);
        $builder->setParameter('survos_tabler.theme', $config['options']['theme']);
        $builder->setParameter('survos_tabler.route_requirements', []); // Populated in compiler pass

        // === Core Services ===

        $builder->register(MenuDispatcher::class)
            ->setArgument('$factory', new Reference('knp_menu.factory'))
            ->setArgument('$dispatcher', new Reference('event_dispatcher'))
            ->setArgument('$menuOptionsResolver', new Reference(MenuOptionsResolver::class));

        $builder->register(MenuContext::class)
            ->setArgument('$requestStack', new Reference('request_stack'));

        $builder->register(PageContext::class)
            ->setArgument('$requestStack', new Reference('request_stack'));

        $builder->register(MenuOptionsResolver::class)
            ->setArgument('$defaultOptions', $config['menu_options'])
            ->setArgument('$menuContext', new Reference(MenuContext::class));

        $builder->register(MenuRenderer::class)
            ->setArgument('$dispatcher', new Reference(MenuDispatcher::class))
            ->setArgument('$knpHelper', new Reference('knp_menu.helper'))
            ->setArgument('$requestStack', new Reference('request_stack'))
            ->setArgument('$templatePrefix', '@SurvosTabler/menu/');

        $builder->register(DebugMenuSlotsSubscriber::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $iconConfig = $config['icons'] ?? [];
        $iconAliases = array_merge([
            'layout-navbar' => 'menu-2',
        ], $iconConfig['aliases'] ?? []);
        $builder->register(IconService::class)
            ->setArgument('$configuredAliases', $iconAliases)
            ->setArgument('$configuredPresets', $iconConfig['presets'] ?? [])
            ->setArgument('$defaultPrefix', $iconConfig['prefix'] ?? 'tabler');

        $builder->register(LandingService::class)
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->setPublic(true)
//            ->setArgument('$projectDir', $iconConfig['aliases'] ?? [])
        ;

        $builder->register(RouteAliasService::class)
            ->setArgument('$configuredAliases', $config['routes'])
            ->setArgument('$router', new Reference('router'));

        $builder->register(ContextService::class)
            ->setAutowired(true)
            ->setArgument('$config', $config)
            ->setArgument('$options', $config['options']);

        $builder->register(MenuService::class)
            ->setAutowired(true)
            ->setArgument('$routeRequirements', '%survos_tabler.route_requirements%')
            ->setArgument('$impersonateUrlGenerator', new Reference('security.impersonate_url_generator', ContainerInterface::NULL_ON_INVALID_REFERENCE))
            ->setArgument('$authorizationChecker', new Reference('security.authorization_checker', ContainerInterface::NULL_ON_INVALID_REFERENCE))
            ->setArgument('$usersToImpersonate', $config['impersonate'])
            ->setArgument('$security', new Reference('security.helper', ContainerInterface::NULL_ON_INVALID_REFERENCE));

        // === Twig Extensions ===

        $builder->register(MenuExtension::class)
            ->setArgument('$renderer', new Reference(MenuRenderer::class))
            ->setArgument('$menuContext', new Reference(MenuContext::class))
            ->addTag('twig.extension');

// In loadExtension(), replace IconExtension registration:
        $builder->register(IconExtension::class)
//            ->setArgument('$uxIconRuntime', new Reference(UXIconRuntime::class))
//            ->setArgument('$uxIconRuntime', new Reference(UXIconRuntime::class, ContainerInterface::NULL_ON_INVALID_REFERENCE))
            ->setArgument('$twig', new Reference('twig', ContainerInterface::NULL_ON_INVALID_REFERENCE))
            ->setArgument('$iconService', new Reference(IconService::class))
            ->addTag('twig.extension');
        $builder->register(RouteAliasExtension::class)
            ->setArgument('$routeAliasService', new Reference(RouteAliasService::class))
            ->addTag('twig.extension');

        $builder->autowire('survos.tabler_twig', TwigExtension::class)
            ->setArgument('$config', $config)
            ->setArgument('$routes', $config['routes'])
            ->setArgument('$options', $config['options'])
            ->setArgument('$contextService', new Reference(ContextService::class))
            ->setArgument('$pageContext', new Reference(PageContext::class))
            ->addTag('twig.extension');

        // === Twig Components we created (not generated from tabler) ===
        $simpleComponents = [
            MiniCard::class,
            TablerIcon::class,
            TablerHead::class,
            TablerHeader::class,
            PageComponent::class,
            TablerPageHeader::class,
            LocaleSwitcherComponent::class,

            // landing
            Benefits::class,
            Hero::class,
            Sources::class,
            Faq::class,
            Features::class
        ];

        foreach ($simpleComponents as $componentClass) {
            $builder->register($componentClass)
                ->setAutowired(true)
                ->setAutoconfigured(true);
        }
        $builder->getDefinition(PageComponent::class)
            ->setArgument('$defaultLayout', $config['options']['layout']);

        foreach ([DropdownComponent::class] as $componentClass) {
            $builder->register($componentClass)
                ->setAutowired(true)
                ->setAutoconfigured(true);
        }

        // @todo: , MenuBreadcrumbComponent::class
        // Menu components need extra arguments
        foreach ([MenuComponent::class] as $componentClass) {
            $builder->register($componentClass)
                ->setAutowired(true)
                ->setAutoconfigured(true)
                ->setArgument('$menuOptionsResolver', new Reference(MenuOptionsResolver::class))
                ->setArgument('$helper', new Reference('knp_menu.helper'))
                ->setArgument('$menuDispatcher', new Reference(MenuDispatcher::class));
        }

    }

    public function configure(DefinitionConfigurator $definition): void
    {
        (new Configuration())->configure($definition);
    }
}
