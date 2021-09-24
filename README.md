PuMuKIT Template Bundle
=======================

This bundle provides a basic template editor to use on WebTVBundle. 

```bash
composer require teltek/pumukit-template-bundle
```

if not, add this to config/bundles.php

```
Pumukit\TemplateBundle\PumukitTemplateBundle::class => ['all' => true]
```

Then execute the following commands

```bash
php bin/console cache:clear
php bin/console cache:clear --env=prod
php bin/console assets:install
```
