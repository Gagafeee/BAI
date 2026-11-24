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
    <th>Décision</th>
    <th>Pourquoi</th>
    <th>Commit</th>
</tr>
<tr>
    <td>1</td>
    <td>Le vrai problème : aucun anti-double-vote<br/>L'IA relève le fait qu'il est possible d'ajouter des votes à l'infini sans aucune validation ou auth <br/>"C'est le cœur du besoin et il n'est pas traité. Sans authentification ni garde-fou, n'importe qui peut inclure un vote en boucle (script de 3 lignes, ou même F5 sur un bouton) et gonfler arbitrairement les scores. Le point 2 de ta spec ("le système doit rester simple") ne justifie pas ce vide : la valeur métier d'un vote est nulle s'il est répétable à l'infini."</td>
    <td>Les specs</td>
    <td>Refusé</td>
    <td>La fonctionnalité est explicitement bonus (US 15). Je ne l'implémente pas directement.</td>
    <td></td>
</tr>
<tr>
    <td>2</td>
    <td>send_add_vote.php:6 — $_POST\["id"\] est lu avant le isset(). Si la clé manque, PHP émet un Warning: Undefined array key avant même ton test.</td>
    <td>Le code spécifié</td>
    <td>Accepté</td>
    <td>Cela permet effectivement de rendre plus robuste la validation. Je propage ces changements au reste des actions.</td>
    <td>3235e182</td>
</tr>
<tr>
    <td>3</td>
    <td>Pas de check de la méthode de requête</td>
    <td>Test GET actions/send_add_vote.php?id=12</td>
    <td>Accepté</td>
    <td>Cela permet effectivement de rendre plus robuste l'action. Je propage ces changements au reste des actions.</td>
    <td>aeb64ff8</td>
</tr>
<tr>
    <td>4</td>
    <td>Lorsque l'id de l'idée n'existe pas, une erreur 500 est renvoyé à la place de 404.</td>
    <td>La justification est correcte et cela provient d'un problème d'architecture de ma part.</td>
    <td>Accepté</td>
    <td>Cela permet effectivement de rendre plus robuste l'action.</td>
    <td>b2d30a78</td>
</tr>
<tr>
    <td>5</td>
    <td>Aucun Content-Type dans les réponses - fonctionnel mais critique</td>
    <td>Je pense que celà n'est pas nécéssaire pour une application de cette taille parce que cela pourrait provoquer des erreurs en fonction des méthodes d'envois des requêtes, mais je l'implémente quand même.</td>
    <td>Accepté</td>
    <td>Cela permet effectivement de rendre plus robuste l'action.</td>
    <td>66ff1c0b</td>
</tr>
<tr>
    <td>6</td>
    <td>Proposition d'optimisation de l'opération en ne vérifiant pas si l'id existe avant de mettre à jour, mais renvoyer une erreur en cas de 404 de la base de donnée.</td>
    <td>Selon l'IA cela permettrait de couvrir les cas où l'idée est supprimé entre le SELECT et l'UPDATE, je trouve cette idée intéressante.
        Je découvre en l'implémentant que celà entre en conflit avec le point 4. Je vais donc laisser l'implémentation telle quelle pour bien s'assurer de renvoyer un 404. Mais j'ajoute tout de même une validation en cas d'erreurs effective. 
    Cela necessiterait l'implémentation d'un système d'erreur personnalisé qui n'est pas justifié pour ce type d'application.
    </td>
    <td>Partiellement Accepté</td>
    <td>Cela permet effectivement de rendre plus robuste l'action.</td>
    <td>f178c079</td>
</tr>
<tr>
    <td>7</td>
    <td>Faute de copié-collé dans le message d'erreur dans getIdea().</td>
    <td>La faute est bien présente</td>
    <td>Accepté</td>
    <td>Cela permet effectivement de rendre plus robuste l'action.</td>
    <td>f035b3a4</td>
</tr>
<tr>
    <td>8</td>
    <td>gettype(...) == "array" : fragile et verbeux</td>
    <td>L'IA propose de remplacer par une simple verification du retour de fetch. Qui renvois false si aucune ligne n'a été trouvé.</td>
    <td>Refusé</td>
    <td>Même si cela parait être la solution largement utilisée, je préfère comparer le type voulu plutôt que l'erreur potentielle. Cela peut être pratique au cas où getIdea() change d'implémentation.</td>
    <td></td>
</tr>
<tr>
    <td>9</td>
    <td>Le catch de l'insertion ne fait rien de la PDOException, une nouvelle exception est renvoyé sans donnée de l'erreur d'origine.</td>
    <td>L'IA propose de remplacer par un error_log qui, après recherche, écrit l'erreur dans un fichier de log sur le système.</td>
    <td>Refusé</td>
    <td>Le remplacement de l'erreur est volontaire pour ne pas renvoyer les erreurs internes de la base de donnée pour des raisons de sécurités. Je préfère ne pas implémenter le système de log pour l'instant.</td>
    <td></td>
</tr>
<tr>
    <td>10</td>
    <td>Nommage incohérent: addIdea / getIdea / doesIdeaExist (camelCase) contre add_approve_vote (snake_case)</td>
    <td>L'IA indique d'utiliser "idéalement camelCase homogène à la PSR-1/12". Après recherche le document <a href="https://www.php-fig.org/psr/psr-12/#21-basic-coding-standard">PSR 12 section 2.1</a> recommande le PascalCase.</td>
    <td>Accepté</td>
    <td>Je vais continuer cependant d'utiliser le snake case, car il est déjà utilisé dans le projet. Le changement de norme devrait constituer un refactor global.</td>
    <td>3a2dd9bc</td>
</tr>
<tr>
    <td>11</td>
    <td>Mauvaises conditions dans les fonctions de réponses.</td>
    <td>L'IA indique que les check message !== null et error !== null dans request.php sont inutiles car les variables ne sont pas nullables, L'IA propose de les remplacer par !== ''. Cela ne corrige aucun bug puisque le message vide était encodé en HTML et ne revoyait rien.</td>
    <td>Accepté</td>
    <td>Je corrige le bug pour assurer la cohérence du programme.</td>
    <td>3a2dd9bc</td>
</tr>

</table>

