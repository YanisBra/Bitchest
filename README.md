Login :

user@user : user

admin@admin : admin 

https://bitchestcda2.alwaysdata.net/login

Installation Bichest

1. Clonage du projet :

- Dans votre terminal, exécutez la commande suivante : git clone
https://github.com/YanisBra/Bitchest.git

2. Configuration de l'environnement :

- Ouvrez le fichier .env et configurez les paramètres de connexion
à votre serveur de base de données en modifiant les valeurs
suivantes :
“DATABASE_URL=mysql://user:password@host:port/database_n
ame”

3. Création de la base de données :

- Dans votre terminal, exécutez la commande suivante pour créer
la base de données : “symfony console doctrine:database:create”

4. Migrations :

- Exécutez les migrations pour créer les tables de la base de
données : "symfony console make:migration" “symfony console doctrine:migrations:migrate”

5. Démarrage du serveur Symfony :

- Lancez le serveur Symfony en exécutant la commande suivante :
“symfony server:start
