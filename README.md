# Installation

## 1. Configuration du composer.json
Dans le composer.json, rajouter ces lignes (à adapter si besoin est):

```javascript
"repositories" : [
        {
            "type": "vcs",
            "url": "https://github.com/lilweb/EvolutionBundle.git"
        }
    ],
```

Puis (à adapter si besoin est):

```javascript
"require": {
    "Lilweb/evolution-bundle": "dev-master"
}
```

Il est aussi possible de le faire en ligne de commande:

```sh
$> composer require Lilweb/evolution-bundle
```


## 2. Activer le bundle

Dans app/AppKernel.php:

```php
<?php
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Lilweb\EvolutionBundle\LilwebEvolutionBundle(),
        );

        // ...
    }
```


## 3. Configurer le bundle

