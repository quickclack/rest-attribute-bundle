<?php

declare(strict_types=1);

namespace Quickclack\RestAttributeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class RestAttributeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Загружаем конфигурацию
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Загружаем сервисы
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../Resources/config')
        );
        $loader->load('services.yaml');

        // Устанавливаем параметры
        $container->setParameter('rest_attribute.default_error_message', $config['default_error_message']);

        // Если валидация отключена, удаляем зависимость от Validator
        if (!$config['enable_validation']) {
            $container->getDefinition('quickclack.rest_attribute.param_fetcher')
                ->replaceArgument(1, null);
        }
    }

    public function getAlias(): string
    {
        return 'rest_attribute';
    }
}