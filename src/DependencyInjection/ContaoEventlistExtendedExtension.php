<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoEventlistExtendedBundle\DependencyInjection;

use Composer\Semver\Semver;
use Contao\CoreBundle\ContaoCoreBundle;
use InspiredMinds\ContaoEventlistExtendedBundle\EventListener\SetCanonicalListener;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContaoEventlistExtendedExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        (new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config')))
            ->load('services.yml')
        ;

        if (Semver::satisfies(ContaoCoreBundle::getVersion(), '>=5.3@dev')) {
            $container->removeDefinition(SetCanonicalListener::class);
        }
    }
}
