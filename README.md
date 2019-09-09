# La Bonne Formation

[La Bonne Formation](https://labonneformation.pole-emploi.fr) (LBF) est un projet Open Source des Startups d'Etat Pôle emploi.

> La Bonne Formation est un moteur de recherche complet des formations en France. Pour vous permettre de faire le meilleur choix, les formations sont triées par Taux de Retour à l’Emploi dans les 12 mois : grâce aux données internes de Pôle emploi nous savons vous dire quelle formation est la plus susceptible d’accélérer votre retour à l’emploi.

> Une fois votre choix réalisé, un questionnaire vous permettra de découvrir les financements dont vous pouvez bénéficier : apprentissage, chômage, insertion, compte personnel formation, dispositif spécifique de la région…

## Dépendances

L'application est codée avec le language PHP7 et utilise le framework Quarky et stocke ses données dans une base [Mariadb](https://mariadb.com/fr/). Le serveur [Sphinx](http://sphinxsearch.com) est utilisé comme moteur de recherche.

Elle utilise [wkhtmltopdf](http://www.sourceforge.net/projects/tcpdf) et [Fpdi](https://www.setasign.com/products/fpdi/about/) pour générer des documents PDF ainsi que les librairies [Bootstrap 3](https://getbootstrap.com), [Chartist](https://gionkunz.github.io/chartist-js/), [Slick](https://kenwheeler.github.io/slick/), [Mapbox](https://docs.mapbox.com/mapbox-gl-js/api/), [Tarteaucitron](https://github.com/AmauriC/tarteaucitron.js).

## Installation

Il est possible d'installer directement les dépendances sur sa machine, mais il est préconisé d'utiliser [Docker](https://www.docker.com/) et [Docker Compose](https://docs.docker.com/compose/) pour une installation accélérée. En effet, l'ensemble des dépendances sont installées grâce aux dockerfiles.

### Installation de Docker sur Debian/Ubuntu

```
sudo apt-get -y install  apt-transport-https ca-certificates curl  software-properties-common
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/$(lsb_release -is | tr '[:upper:]' '[:lower:]') $(lsb_release -cs) stable"
sudo apt-get update
sudo apt-get install -y docker-ce
sudo usermod -aG docker $USER
```

### Installation de docker-compose

```
sudo curl -L https://github.com/docker/compose/releases/download/1.18.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

### Execution du docker labonneformation
```
docker-compose up --build -d
```

L'application est disponible sur le port 8080 : *http://localhost:8080*


**Attention cette installation n'est pas adaptée à un environnement de production**

En production il faut se tourner vers une solution comme NGINX et PHP-FPM.

## Fichier de configuration pour environnement de développement

Dupliquer le fichier `config.php` qui se trouve dans `web/home/www/labonneformation/config/config.php` et le renommer `config.dev.php`.

## Base de données MariaDB pour le développement

Docker charge et indexe une base de données minimale pour les développeurs.

## Démarrer le serveur

Dans le docker `docker_web_1`, le serveur PHP a été démarré via la commande :

`php -S 0.0.0.0:80 batch/quarky.php`

## Mise à jour de l'index Sphinx

Dans le docker database, lancer la commande `indexer --all --rotate` pour mettre à jour l'index de Sphinx.

## Micro-framework Quarky

Quarky est un micro-framework pour PHP. Il prend en charge la gestion du pattern MVC, les routes,l'accès à la base de données, les caches, l'envoi de mail, la gestion des formulaires. La Bonne Formation est construit sur la base de Quarky.

Il est disponible dans le répertoire `www/web/home/www/labonneformation/sys/quark/`.

## Moteur de financement Trèfle

La Bonne Formation permet de simuler un financement de formation. Pour ceci il fait appel à l'API du serveur du simulateur [Trèfle](https://git.beta.pole-emploi.fr/open-source/trefle). Trèfle est un autre projet Open Source des Startups d'Etat Pôle emploi.

> Trèfle est un simulateur de dispositifs de financement qui, en fonction des données du demandeur et de la formation,
va générer une liste de financements possibles.
>
> Cette liste de financements proposés est accompagnée, selon le dispositif, de la rémunération dont pourrait bénéficier le demandeur. Les résultats sont donnés à titre informatif et nécessitent l'accompagnement d'un conseiller pour la suite des démarches.

