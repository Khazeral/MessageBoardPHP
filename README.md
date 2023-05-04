# Message Board - PHP Backend

Voici le backend de l'appli MessageBoard qui permet aux utilisateurs de poster des messages dans des salons.
Voici les fonctionnalités :

- Chaque utilisateur est différenciés les uns des autres.
- Les utilisateurs peuvent poster des messages dans n'importe quel salon et peuvent lire tous les messages de tous les salons, du plus récent au plus ancien.
- N'importe qui peut créer un nouveau salon du moment qu'il n'y en ai pas déjà un avec le même nom.
- Les utilisateurs ne peuvent pas poster deux messages consécutifs sauf si leur dernier message date de plus de 24 heures.

## Installation

1. Clonez ce dépôt sur votre serveur local ou distant.
2. Configurez votre serveur pour pointer vers le dossier du projet. (php -S localhost:8000 ./public/router.php)

## Utilisation

Pour verifier si toute l'installation s'est bien déroulée

- http://localhost:8000/
  **Pour renseigner correctement le contenu de la requete utilisez le Multipart Form**

### Création d'un user

POST http://localhost:8000/createuser

- username : string

### Création d'une room

POST http://localhost:8000/createroom

- room_name : string

### Création d'un message

POST http://localhost:8000/post-message

- id_user : int
- id_room : int
- message : string

### Récupération des salons

GET http://localhost:8000/rooms

### Récupération des messages d'un salon

GET http://localhost:8000/messages?id_room=0

- id_room : int

### Récupération des utilisateurs

GET http://localhost:8000/users

### Récupération d'un utilisateur par son id ou son username

GET http://localhost:8000/user?id=0

- id : int
  GET http://localhost:8000/user?username=string
- username : string

### Récupération d'un salon par son id ou son nom

GET http://localhost:8000/room?id=0

- id : int
  GET http://localhost:8000/room?room_name=string

## Tests

Les tests unitaires et fonctionnels sont écrits avec PHPUnit et Behat. Pour exécuter les tests, suivez les instructions d'installation de PHPUnit et Behat dans le projet.

- Pour exécuter les tests unitaires avec PHPUnit, utilisez la commande suivante :

```
php vendor/phpunit.phar
```

- Pour exécuter les tests fonctionnels avec Behat, utilisez la commande suivante :

```
php vendor/behat.phar -c behat.yml
```
