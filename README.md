# Boîte à idées

Petite application web en PHP qui permet de proposer des idées et de voter pour celles des autres. Les idées et leurs votes sont stockés dans une base MySQL.

## Fonctionnalités

- Créer une idée avec un pseudo, un titre et une description.
- Parcourir les idées avec leur auteur et leur nombre de votes.
- Pouvoir ajouter des votes ; les retours sont affichés sous forme de notifications.

## Technologies

- PHP avec PDO et le pilote MySQL.
- MySQL.
- HTML, CSS et JavaScript natifs.
- [Toastify](https://github.com/apvarun/toastify-js), chargé depuis jsDelivr pour les notifications.

## Prérequis

- PHP avec l’extension `pdo_mysql` activée.
- MySQL.
- Un navigateur web ; une connexion Internet est nécessaire pour charger Toastify depuis le CDN.

## Installation et lancement

1. Créez la base de donné en éxecutant `/SQL/setup.sql` ou via PHPmyAdmin :
   Le script crée la base `db` et la table `ideas`.

2. Créez votre configuration locale à partir du modèle :

   ```sh
   cp config.example.php config.php
   ```

   Modifiez les constantes `DB_HOST`, `DB_NAME`, `DB_USER` et `DB_PASS` dans `config.php` pour correspondre à votre serveur MySQL. Ce fichier est ignoré par Git afin de ne pas versionner les
   identifiants locaux. La base configurée doit être celle créée à l’étape précédente (par défaut `db`).

3. (Facultatif) Chargez les idées d’exemple :
   Exécutez le script dans `/SQL/seed.sql`

4. Depuis la racine du projet, démarrez le serveur PHP intégré :

   ```sh
   php -S localhost:8080
   ```

5. Ouvrez <http://localhost:8080> dans votre navigateur.

## Structure du projet

```text
.
├── actions/          # Endpoint du serveur
├── components/       # Rendu des composants visuels
├── controllers/      # Opérations métier et business logic
├── CSS/              # Feuille de style
├── libs/             # Scripts PHP internes utils
├── scripts/          # Scripts frontend JavaScript
├── SQL/              # Création de la base et données d’exemple
├── config.example.php
├── index.php         # Page principale
└── tests_requests.http # Exemples de requêtes HTTP
```

## Endpoints

Les deux actions acceptent des requêtes `POST` encodées en `application/x-www-form-urlencoded` :

| Endpoint                       | Champs                       | Résultat                                              |
|--------------------------------|------------------------------|-------------------------------------------------------|
| `actions/send_create_idea.php` | `pseudo`, `title`, `content` | Ajoute une idée puis redirige vers la page d’accueil. |
| `actions/send_add_vote.php`    | `id`                         | Ajoute un vote positif et renvoie une réponse JSON.   |

Des exemples de requêtes sont disponibles dans `tests_requests.http`.

## État actuel

L’application est un projet en développement.