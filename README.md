# Installation

## 1. Configuration du composer.json
Dans le composer.json, rajouter ces lignes (à adapter si besoin est):

```javascript
"require": {
    "Lilweb/evolution-bundle": "0.*"
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

Vous pouvez surcharger le répertoire où les évolutions sont stockées:
```
# app/config/config.yml
lilweb_evolution:
    evolution_dir: /full/path/to/directory

```

NB: Par défaut, celui-ci équivaut à `%kernel.root_dir/evolutions`.

## 4. Utilisation

Le fonctionnement du bundle se veut très simple: appliquer une suite d'évolutions à une base de données.

A partir de la dernière évolution effectuée, le bundle va chercher les nouvelles évolutions à appliquer tout en étant
capable de rollback en cas de problème.

Pour écrire une évolution, il vous suffit de définir vos:
  - "Ups": Le ou les évolutions à appliquer
  - "Downs": Les 'contre-évolutions' (eg: qu'est-ce que je fais si mon évolution a échouée)

Exemple:

```sql
-- Migration du 12/10/2004

# Script #1

-- Script number one

# /!\ Respecter la syntaxe de la ligne suivante:
# --- !Ups

CREATE TABLE IF NOT EXISTS `toto` (
  `id` int(11) NOT NULL, -- comment
  `name` varchar(10) DEFAULT NULL, # comment
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `titi` (
  `id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# /!\ Respecter la syntaxe de la ligne suivante:
# --- !Downs

DROP TABLE toto;
DROP TABLE titi;
```

NB: Le nom du fichier doit respecter le format `[\d]+.sql` (eg: 1.sql, 15.sql ...)
NB2: En cas de problèmes, un rollback s'effectura à partir de la version buggué, jusque la version d'avant évolution.
