# e-buy
Application e-commerce build avec Symfony et React.Js avec l'envirenoment Docker (Docker is the best :P)

## Prérequis
Afin de pouvoir exécuter l'application sur votre poste, vous devez d'aborder installer les dépendances suivantes :
  * docker
  * docker-compose
  * makefile
## Exécution
Suivre les procedures suivantes :
  * ouvrir le terminal dans le dossier du projet
  * Executer `docker-compose build`
  * Si vous etes sur linux, executer `make dev`
  * Si vous etes sur windows, demarrer le container de docker-compose en faisant `docker-compose up` ou `docker-compose up -d` pour detacher le terminal

Puis
  * Naviguer maintenant vers [http://localhost:8000](http://localhost:8000), votre application devrais demarrer correctement

## Pre remplissage de la base de donnee :
Afin de pre-remplir la base de donnees avec des donnees de test,
  * Sur linux: executer `make fixtures`
  * Sur windows: executer `docker-compose --user-data=www-data exec php-fpm bin/console doctrine:fixtures:load`
