<?php
/* src/Compiler/Configuration.php v1.1 */

declare(strict_types=1);

namespace Survos\TablerBundle\Compiler;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

final class Configuration
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->append($this->getIconsConfig())
                ->append($this->getAppConfig())
                ->append($this->getRoutesConfig())
                ->append($this->getDebugConfig())
                ->append($this->getOptionsConfig())
                ->arrayNode('menu_options')
                    ->useAttributeAsKey('name')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('impersonate')
                    ->useAttributeAsKey('name')
                    ->scalarPrototype()->end()
                    ->info('User identifiers that can be impersonated')
                ->end()
            ->end();
    }

    private function getIconsConfig(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('icons');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('prefix')->defaultValue('tabler')->end()
                ->arrayNode('aliases')
                    ->useAttributeAsKey('name')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('presets')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('icon')->isRequired()->end()
                            ->scalarNode('class')->defaultValue('')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $rootNode;
    }

    private function getAppConfig(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('app');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('code')->defaultValue('my-project')->end()
                ->scalarNode('title')->defaultValue('My Project')->end()
                ->scalarNode('description')->defaultValue('')->end()
                ->scalarNode('abbr')->defaultValue('my<b>Project</b>')->end()
                ->scalarNode('logo')->defaultNull()->end()
                ->scalarNode('logo_small')->defaultNull()->end()
                ->scalarNode('homepage_route')->defaultNull()->end()
                ->scalarNode('homepage_url')->defaultNull()->end()

                ->arrayNode('links')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('github')->defaultNull()->end()
                        ->scalarNode('docs')->defaultNull()->end()
                        ->scalarNode('sponsor')->defaultNull()->end()
                        ->scalarNode('site')->defaultNull()->end()
                        ->scalarNode('contact')->defaultNull()->end()
                    ->end()
                ->end()

                ->arrayNode('social')
                    ->useAttributeAsKey('name')
                    ->scalarPrototype()->end()
                ->end()

                ->arrayNode('meta')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('og_image')->defaultNull()->end()
                        ->scalarNode('twitter_site')->defaultNull()->end()
                        ->scalarNode('theme_color')->defaultNull()->end()
                    ->end()
                ->end()

                ->arrayNode('header')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('locale_switcher')->defaultTrue()->end()
                        ->scalarNode('container')->defaultValue('container-fluid')->end()
                        ->arrayNode('auth')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultTrue()->end()
                                ->booleanNode('show_login')->defaultTrue()->end()
                                ->booleanNode('show_user_menu')->defaultTrue()->end()
                                ->arrayNode('routes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('login')->defaultValue('app_login')->end()
                                        ->scalarNode('logout')->defaultValue('app_logout')->end()
                                        ->scalarNode('register')->defaultValue('app_register')->end()
                                        ->scalarNode('profile')->defaultValue('app_profile')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $rootNode;
    }

    private function getRoutesConfig(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('routes');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('home')->defaultValue('app_homepage')->end()
                ->scalarNode('login')->defaultNull()->end()
                ->scalarNode('logout')->defaultNull()->end()
                ->scalarNode('register')->defaultNull()->end()
                ->scalarNode('profile')->defaultNull()->end()
                ->scalarNode('settings')->defaultNull()->end()
                ->scalarNode('search')->defaultNull()->end()
            ->end();

        return $rootNode;
    }

    private function getOptionsConfig(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('options');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('theme')->defaultValue('tabler')->end()
                ->enumNode('layout')
                    ->values(['horizontal', 'dashboard', 'vertical', 'condensed'])
                    ->defaultValue('horizontal')
                ->end()
                ->booleanNode('dark_mode')->defaultFalse()->end()
                ->booleanNode('show_locale_dropdown')->defaultTrue()->end()
            ->end();

        return $rootNode;
    }

    private function getDebugConfig(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('debug');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('menu_slots')->defaultFalse()->end()
            ->end();

        return $rootNode;
    }
}
