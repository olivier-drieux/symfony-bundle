<?php

namespace App\LinkwebBundle\Utils;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BundleHandler.
 */
class BundleHandler
{
    /**
     * Check if the given env variables exist.
     */
    public static function setup(string $bundleName, ContainerInterface $container, array $setup): void
    {
        // Check if env variables are registered
        self::areEnvRegistered($bundleName, $container, $setup['envs'] ?? []);

        // Check if bundles are registered
        self::areBundlesRegistered($bundleName, $container, $setup['bundles'] ?? []);

        // Check if classes are existing
        self::areClassesExisting($bundleName, $setup['classes'] ?? []);
    }

    /**
     * Check if the given env variables exist.
     */
    public static function areEnvRegistered(string $bundleName, ContainerInterface $container, array $envs): void
    {
        // If no bundlesClass are provided
        if (empty($envs)) {
            return;
        }

        // Loop on each env variable
        foreach ($envs as $env) {
            // If variable not in container and not in $_ENV, $_SERVER or getenv
            if (!$container->hasParameter($env) && empty($_ENV[$env] ?? $_SERVER[$env] ?? getenv($env))) {
                throw new \RuntimeException("Env variable $env not found but needed to use the bundle $bundleName");
            }
        }
    }

    /**
     * Check if the given bundles are registered in the kernel.
     */
    public static function areBundlesRegistered(string $bundleName, ContainerInterface $container, array $bundlesClass): void
    {
        // If no bundlesClass are provided
        if (empty($bundlesClass)) {
            return;
        }

        // Retrieve registered bundles from the container
        $registeredBundles = $container->getParameter('kernel.bundles');

        foreach ($bundlesClass as $bundleClass) {
            if (!in_array($bundleClass, $registeredBundles, true)) {
                throw new \RuntimeException("Bundle $bundleClass not found but needed to use the bundle $bundleName");
            }
        }
    }

    /**
     * Check if the given classes are available.
     */
    public static function areClassesExisting(string $bundleName, array $neededClasses): void
    {
        // If no classes are provided
        if (empty($neededClasses)) {
            return;
        }

        foreach ($neededClasses as $neededClass) {
            // Ensure DoctrineMigrationsBundle is loaded inside the bundle
            if (!class_exists($neededClass)) {
                throw new \RuntimeException("Class $neededClass not found but needed to use the bundle $bundleName");
            }
        }
    }

    /**
     * Recursively merge configuration into Symfony's container.
     *
     * @param array $data Configuration data to merge
     */
    public static function mergeConfig(ContainerBuilder $container, array $data): void
    {
        foreach ($data as $extension => $configs) {
            // Fetch existing configuration for the given extension
            $existingConfigs = $container->getExtensionConfig($extension);

            // Initialize the merged configuration
            $mergedConfig = [];

            // If there's existing configuration, merge recursively
            foreach ($existingConfigs as $existingConfig) {
                $mergedConfig = self::mergeArrays($mergedConfig, $existingConfig);
            }

            // Merge new data recursively
            $mergedConfig = self::mergeArrays($mergedConfig, $configs);

            // Prepend the merged configuration to the container
            $container->prependExtensionConfig($extension, $mergedConfig);
        }
    }

    /**
     * Merge two arrays recursively without using array_merge_recursive.
     */
    private static function mergeArrays(array $array1, array $array2): array
    {
        // Loop on each
        foreach ($array2 as $key => $value) {
            // If a nested array is present
            if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
                // Recursively loop on nested arrays
                $array1[$key] = self::mergeArrays($array1[$key], $value);
            } else {
                // Else only add the value
                $array1[$key] = $value;
            }
        }

        // Return merge array
        return $array1;
    }
}
