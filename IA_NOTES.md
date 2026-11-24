# Nous ajoutons ici toutes les notes des tâches effectuées avec l'IA.

| UserStory                   |   |
|-----------------------------|---|
| Personne & date             |   |
| Outil d'IA et modèle        |   |
| Ce que j'ai codé            |   |
| Ce que j'ai transmis à l'IA |   |
| Prompt                      |   |

| UserStory | Personne & date | Outil d'IA et modèle | Ce que j'ai transmis à l'IA | Promt |
|-----------|-----------------|----------------------|-----------------------------|-------|
|           |                 |                      |                             |       |

## User story 9 - Système de vote - Relecture IA

> Gabriel - 24/11/2026 11h

J'ai pu implémenter le système pour ajouter un vote :

- Un `send_add_vote()` action avec validation de l'ID et execution dans un try/catch.
- Une fonction dans le controller associé `add_approve_vote()` avec l'exécution de la requête.
- \* L'implémentation du formulaire coté client se fera plus tard [Story 10]().

[État du code](https://github.com/Gagafeee/BAI/compare/main...b22994ccfc328a0ee2ed005f821d9ef0e2fec3f5)

### → Je vais utiliser Mistral `GLM-5.3 flash + reflexion` pour relire le code.

Le prompt :
> Je viens d'implémenter un système de vote sur mon application de boite à idée, l'objectif est de pouvoir voter (up-vote) pour une idée, pas besoin d'authentification, le système doit rester simple.
>
> Relis le code que je te donne et identifie les erreurs, failles et maladresses. Donne-moi également des pistes pour pouvoir l'améliorer.

\+ `send_add_vote.php`, `idea_manager.php`, `db.php`, `response.php`, `setup.sql`.

\* `db.php` contient uniquement la logique de connexion, les données de connexions se situent dans `config.php`.
\* `setup.php` contient uniquement la logique de création des tables de la base de donnée.

### Analyse de la réponse

<table>
<tr>
    <th>N°</th>
    <th>Remarque de l'IA</th>
    <th>Ce que j'ai vérifié</th>
    <th>Correction</th>
    <th>Décision</th>
    <th>Pourquoi</th>
</tr>
<tr>
    <td>1</td>
    <td>Le vrai problème : aucun anti-double-vote<br/>L'IA relève le fait qu'il est possible d'ajouter des votes à l'infini sans aucune validation ou auth <br/>"C'est le cœur du besoin et il n'est pas traité. Sans authentification ni garde-fou, n'importe qui peut inclure un vote en boucle (script de 3 lignes, ou même F5 sur un bouton) et gonfler arbitrairement les scores. Le point 2 de ta spec ("le système doit rester simple") ne justifie pas ce vide : la valeur métier d'un vote est nulle s'il est répétable à l'infini."</td>
    <td>Les specs</td>
    <td></td>
    <td>Refusé</td>
    <td>La fonctionnalité est explicitement bonus (US 15). Je ne l'implémente pas directement.</td>
</tr>
<tr>
    <td>2</td>
    <td>send_add_vote.php:6 — $_POST\["id"\] est lu avant le isset(). Si la clé manque, PHP émet un Warning: Undefined array key avant même ton test.</td>
    <td>Le code spécifié</td>
    <td>

```php
$idea_id = $_POST["id"] ?? null;
```

</td>
    <td>Accepté</td>
    <td>Cela permet effectivement de rendre plus robuste la validation. Je propage ces changements au reste des actions.</td>

</tr>
</table>

