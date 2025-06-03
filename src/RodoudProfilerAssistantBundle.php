<?php

declare(strict_types=1);

namespace Rodoud\ProfilerAssistantBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Rodoud\ProfilerAssistantBundle\DependencyInjection\RodoudProfilerAssistantExtension;

final class RodoudProfilerAssistantBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function loadExtension(
        array $config,
        ContainerConfigurator $container,
        ContainerBuilder $builder
    ): void {
        $container->import('../config/services.yaml');
    }

    /**
     * Explicitly specify which extension to use (optional - Symfony auto-discovers by naming convention)
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new RodoudProfilerAssistantExtension();
    }
}