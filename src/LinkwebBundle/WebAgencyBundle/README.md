# WebAgencyBundle

## TODO
- Symfony Flex recipe to migrate WebAgency (https://symfony.com/doc/current/setup/flex.html#symfony-flex-recipes)

## Installation

```php
// config/bundles.php
return [
    // ...
    LinkwebBundle\WebAgencyBundle\WebAgencyBundle::class => ['all' => true],
];
```

## Configuration

### Entity

```yaml
# config/packages/doctrine.yaml
doctrine:
    orm:
        mappings:
            WebAgencyBundle:
                type: attribute
                dir: '%kernel.project_dir%/LinkwebBundle/WebAgencyBundle/src/Entity'
                prefix: 'LinkwebBundle\WebAgencyBundle\Entity'
                alias: LinkwebBundle
```

### Migration

```yaml
# config/packages/doctrine_migrations.yaml
doctrine_migrations:
  migrations_paths:
        'LinkwebBundle\WebAgencyBundle\Migrations': '%kernel.project_dir%/LinkwebBundle/WebAgencyBundle/src/Migrations'
````

### Routing

```yaml
# config/packages/routes.yaml
linkweb_shared_bundle:
    resource: '%kernel.project_dir%/LinkwebBundle/WebAgencyBundle/src/Controller/'
    type: attribute
```
