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

## User story 7 - Protection XSS - Relecture IA

> Gabriel - 25/11/2026 11h

Je viens d'effectuer un test xss en ajoutant une idée avec un contenu :

```html
<script>alert('XSS')</script> 
```

J'ai corrigé la faille en ajoutant `htmlspecialchars()` sur chaque champ affiché sur les cartes d'idées.

### Je vais faire relire le code par l'IA pour identifier de potentiels endroits où la vulnérabilité est encore présente :

J'utilise `Junie Lite + reflexion` avec `AIR` + les intégrations de l'IDE.

Prompt :
> Je suis en train de protéger mon site contre les failles de sécurité basique tel que l'injection XSS. Je viens d'ajouter la fonction php `htmlspecialchars` autour des elements affiché depuis la base
> de donnée.
>
> Aide-moi à identifier les endroits potentiels où une telle faille existe toujours.
>
> Je précise que le site ne possède pas de système d'authentification et ne prévoit pas d'en ajouter pour le moment, il est donc possible d'ajouter des votes à l'infini, c'est le comportement
> souhaité.
>
> Le modèle est en mode `plannification`, il a la possibilité de créer des agents, d'exécuter des commandes avec un système de sécurité et a accès à l'ensemble du projet sauf les données sensibles via
> un fichier `.aiignore`, il ne peut pas modifier les fichiers du projet.

### Réponse :

> Aucun XSS directement exploitable n’a été confirmé dans le rendu normal des idées. Un point de sortie PHP reste à durcir, et la sécurité des notifications dépend de la bibliothèque externe.
>

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
    <td>Possible future vulnérabilité dans les messages d'erreurs dans send_create_idea.php</td>
    <td>Le code en question, l'IA fait remarqué qu'une faille pourrait un jour apparaître si un des messages d'erreurs contient une donnée dynamique.</td>
    <td>Accepté</td>
    <td>C'est une bonne remarque pour éviter une faille future, j'ajoute htmlspecialchars.</td>
    <td>3e6952d3</td>
</tr>
<tr>
    <td>2</td>
    <td>Possible future vulnérabilité dans les messages des notifications</td>
    <td>L'IA indique je les messages retournés du serveurs sont passé directement à la libraire Toastify sans protection. Ce n'est pas une faille car la librarie utilise déjà son propre système de protection.</td>
    <td>Accepté</td>
    <td>J'ajoute tout de même htmlspecialchars dans les réponses JSON pour ne pas dépendre de la protection de la libraire.</td>
    <td>b7b4e69b</td>
</tr>
</table>

Les autres remarques parlent des méthodes déjà en places.

---

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
<tr>send_add_vote.php</tr>
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
<tr>idea_manager.php</tr>
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
<tr>redirect.php</tr>
<tr>
    <td>11</td>
    <td>Mauvaises conditions dans les fonctions de réponses.</td>
    <td>L'IA indique que les check message !== null et error !== null dans request.php sont inutiles car les variables ne sont pas nullables, L'IA propose de les remplacer par !== ''. Cela ne corrige aucun bug puisque le message vide était encodé en HTML et ne revoyait rien.</td>
    <td>Accepté</td>
    <td>Je corrige le bug pour assurer la cohérence du programme.</td>
    <td>8ae4b030</td>
</tr>
<tr>
    <td>12</td>
    <td>Les fonctions redirect* n'arrêtent pas l'exécution du programme après avoir défini la redirection.</td>
    <td>L'IA indique que les programme pourrait continuer à s'exécuter après un redirect.</td>
    <td>Partiellement Accepté</td>
    <td>C'est un comportement volontaire, je n'ajoute pas d'exit dans les fonctions concernées comme le propose l'IA. Mais je renomme les fonctions redirect* en set_redirect* et je précise leur doc pour éviter les futures erreurs de manipulations et clarifier leur fonctionnement.</td>
    <td>ef9a6b15</td>
</tr>
<tr>
    <td>13</td>
    <td>Possible faille car les location ne sont pas validé dans les fonctions de redirections. "si un jour tu passes une chaîne provenant de l'utilisateur (paramètre ?next=…), tu ouvres une faille de type redirection ouverte"</td>
    <td>Il n'existe pas réellement de faille, mais une pourait effectivement apparaître un jour.
    Après reflexion, je peux corriger cette erreur simplement</td>
    <td>Partiellement Accepté</td>
    <td>Je transforme $location en position relative en ajoutant un "/" devant. J'adapte la doc des fonctions. Je reteste avec les implémentations existantes. La racine du projet devient "/" → "".</td>
    <td>52072093</td>
</tr>
<tr>
    <td>14</td>
    <td>?status=200 dans la chaîne de requête est redondant avec un vrai code HTTP</td>
    <td>Je pourrais effectivement utiliser le vrai status de la requête.</td>
    <td>Mis en attente</td>
    <td>Je m'occuperai de faire ce choix lors de l'implémentation des systèmes AJAX.</td>
    <td></td>
</tr>
<tr>
    <td>15</td>
    <td>exit est préférable à die() pour stopper l'exécution dans respond_error().</td>
    <td>Je découvre que contrairement à ce que dit l'IA, die et exit sont fonctionnellement identique (alias d'après la documentation PHP). Je comprends que exit est souvent utilisé plutôt que die pour représenter un arrêt controller de l'exécution du programme, alors que die représente une erreur fatale.</td>
    <td>Refusé</td>
    <td>Je garde die pour éviter de rendre le projet plus compliquer à lire puisqu'il n'y a aucune différence.</td>
    <td></td>
</tr>
<tr>db.php</tr>
<tr>
    <td>16</td>
    <td>die("Erreur de connexion…") trop imprécis.</td>
    <td>L'IA suggère de remplacer par un throw new RuntimeException(...)</td>
    <td>Refusé</td>
    <td>Je garde cette simple erreur car elle suffisante et cela permets de conserver le code proposé par le professeur.</td>
    <td></td>
</tr>
<tr>
    <td>17</td>
    <td>S'assurer que config.php est hors de la racine du projet pour éviter un accès non autorisé.</td>
    <td>Après check à https://localhost/config.php, la page renvois bien une page mais avec un contenu vide car toutes les variables sont en php. C'est tout de même une vulnérabilité à surveiller.</td>
    <td>Refusé</td>
    <td>Ce n'est pas une réelle faille pour le moment mais je note la remarque pour le futur.</td>
    <td></td>
</tr>
<tr>setup.sql</tr>
<tr>
    <td>18</td>
    <td>vote_against n'est jamais utilisé : à supprimer</td>
    <td></td>
    <td>Refusé</td>
    <td>Nous avons prévu depuis le début d'ajouter des votes pour et contre. Cela n'est pas prévu dans le backlog mais sa suppression devra être traité en équipe.</td>
    <td></td>
</tr>
<tr>
    <td>19</td>
    <td>Ajouter un index sur vote_approve pour optimiser le futur tri.</td>
    <td>À chercher si l'index peut vraiment accélérer une recherche si simple</td>
    <td>Je note</td>
    <td>Hors du cadre mais pertinent.</td>
    <td></td>
</tr>
<tr>Autre</tr>
<tr>
    <td>20</td>
    <td>Faute de français récurrente 🫠.</td>
    <td>« La base de donnée <b>à</b> renvoyé une erreur » Le seum de toujours faire des fautes aussi bêtes...</td>
    <td>Accepté</td>
    <td></td>
    <td>bab14428</td>
</tr>
<tr>
    <td>21</td>
    <td>respond_success(message ?string) → json_encode(['message' => null]) renvoie {"message":null}</td>
    <td>Comportement volontaire puisque la fonction s'appelle respond_success.</td>
    <td>Refusé</td>
    <td></td>
    <td></td>
</tr>
</table>

Je refais des tests par moi-même :

- Peut-on toujours ajouter une nouvelle idée.
- Peut-on toujours voter pour une idée.
- Les cas d'erreurs ont été testés au moment d'appliquer la correction.

---

## User story 10 - Dynamisation du système de vote - Full IA

> Gabriel - 24/11/2026 15h

Le système pour ajouter un vote sur une idée existe et est fonctionnel. Je vais utiliser `Junie Lite + reflexion` avec `AIR` + les intégrations de l'IDE pour implémenter le système frontend.

Prompt :
> Voici notre mini-projet de boite à idée, c'est un site en pure HTML, CSS, PHP, JS dans lequel on peut ajouter librement des nouvelles idées et voter pour celles que l'on préfère.
> Dans le dossier `actions` tu trouveras l'action `send_add_vote` pour ajouter un nouveau vote sur une idée existante via une requête POST.
> Tu peux retrouver un exemple de requête dans test_requests.http
> Les cartes sont généré via le template dans `components/idea_card_template.php`
>
> Je te demande d'implémenter le frontend de cette fonctionnalité de vote en pure JS avec une logique AJAX.
> - Commence par ajouter l'affichage du nombre de votes sur chaque carte (n'utilise que vote_approve).
> - Ajoute un bouton "+1" sur chaque carte qui envois une nouvelle requête vers l'endpoint avec l'id de la carte en question.
> - Implémente une fonction d'envois de requête générique dans un fichier `action-manager`
> - Fait attention de bien gérer les erreurs, valeurs de retours, erreurs de réseaux etc. Lis le fichier de response dans `libs/response` pour adapter la fonction au backend.
> - Je te demanderai plus tard d'ajouter un système de notification pour afficher les résultats, fait attention à bien séparer le traitement de la réponse pour que je puisse facilement changer
>   ultérieurement l'affichage des résultats. Met seulement un message / erreur dans la console pour le moment.
> - Enfin, ajoute la mise à jour dynamique de l'affichage en incrémentant le compteur de vote de la carte. Fait attention de ne le faire que si la requête a bien abouti.
>
> 
> - Place tous les fichiers js que tu pourras créer dans un nouveau dossier `scripts`.
> - N'utilise pas de librairies externes.
>
> Produit ensuite un petit résumé des différents élements que tu as ajouté avec les différents choix que tu as fait.

Le modèle a la possibilité de créer des agents, d'exécuter des commandes avec un système de sécurité et a accès à l'ensemble du projet sauf les donnée sensibles via un fichier `.aiignore` :

```aiignore
/config.php # Donnés de connexions
/SQL/seed.sql # Donnés fictives de la base de donnée

IA_NOTES.md # Notes à propos de l'utilisation de l'IA (Ce fichier).
```

### Reflexion :

> Réfléchi pendant 3 minutes et 16 secondes


> ### Lecture du projet
> Je vais examiner l’action de vote, le format de réponse, le template des cartes et les scripts déjà chargés par la page. Cela permettra d’intégrer le JavaScript sans dépendance et de respecter les
> conventions existantes.
> ---
> Je vais maintenant vérifier les champs transmis et la structure exacte des réponses JSON, puis observer comment les cartes sont rendues et stylées. Je pourrai ainsi choisir un contrat d’API
> réutilisable et câbler le clic sans perturber le rendu PHP.
> ---
> Je vais vérifier le schéma des idées et la logique SQL d’incrémentation pour que le compteur affiché corresponde exactement au champ vote_approve. Ensuite, je pourrai ajouter les attributs de carte,
> le bouton, le gestionnaire générique et son branchement dans la page.
> ---
> La structure retenue s’appuie sur `data-idea-id` pour relier chaque carte à l’action POST et sur un compteur accessible pour l’affichage dynamique. J’ajoute maintenant ces éléments au template et à
> index.php, puis les deux scripts sans dépendance et un style léger pour le contrôle de vote.
> --- 
> ### Ajout et modification des fichiers.
>
> L’implémentation est en place dans le template, la page et les nouveaux scripts sous scripts/, sans toucher aux fichiers locaux déjà modifiés. Je vais contrôler la syntaxe PHP/JavaScript puis
> exercer le gestionnaire sur des cas de succès, d’erreur HTTP et de réseau pour vérifier le contrat backend et l’absence de faux incrément.
>
> ### Vérification
> Execution de `node --check scripts/action-manager.js && node --check scripts/vote.js` autorisé par l'utilisateur. (J'ai bien vérifié dans la
> documentation: [Node --check](https://nodejs.org/api/cli.html#-check) )
>
> Execution de `php -l components/idea_card_template.php && php -l index.php` autorisé par l'utilisateur. (J'ai vérifié le `-l` via `man`.)
>
> Execution de
> ```shell
> node -e '
> global.window = {};
> global.fetch = async (url, options) => ({ok: true, status: 200, json: async () => ({message: "Nous avons bien ajouté votre vote."}), requestUrl: url, requestOptions: options});
> require("./scripts/action-manager.js");
> (async () => {
> const result = await window.ActionManager.sendAction("actions/send_add_vote.php", {id: 5});
> if (!result.ok || result.status !== 200 || result.message !== "Nous avons bien ajouté votre vote.") throw new Error("Réponse de succès inattendue");
> if (global.lastRequest) throw new Error("unexpected");
> global.fetch = async () => ({ok: false, status: 404, json: async () => ({message: "Cette idée n’existe pas."})});
> const failure = await window.ActionManager.sendAction("actions/send_add_vote.php", {id: 999});
> if (failure.ok || failure.status !== 404 || failure.message !== "Cette idée n’existe pas.") throw new Error("Erreur HTTP non gérée");
> global.fetch = async () => {
> throw new Error("offline");
> };
> const network = await window.ActionManager.sendAction("actions/send_add_vote.php", {id: 5});
> if (network.ok || network.status !== null || !network.message.includes("réseau")) throw new Error("Erreur réseau non gérée");
> console.log("Gestionnaire vérifié : succès, HTTP 404 et erreur réseau.");
> })().catch((error) => {
> console.error(error);
> process.exitCode = 1;
> });
> '
> ```
> autorisé par l'utilisateur.
>
> ---
> Les contrôles de syntaxe et les scénarios du gestionnaire passent. Je complète la vérification en simulant le clic sur une carte pour confirmer que son ID est envoyé, que le bouton est réactivé et
> que le compteur ne bouge pas après un échec.
>
> --- 
> Execution de `git diff -- components/idea_card_template.php index.php CSS/style.css scripts/action-manager.js scripts/vote.js && git status --short` autorisé automatiquement.
>
> Execution de
> ```shell
> node -e '
> let clickHandler;
> let sentRequest;
> const count = {textContent: "2"};
> const card = {dataset: {ideaId: "5"}, querySelector: () => count};
> const button = {disabled: false, closest: () => card};
> global.document = {
>   querySelector: () => ({
>   addEventListener: (eventName, handler) => {
>   if (eventName === "click") clickHandler = handler;
>   }
>   })
>   };
>   global.ActionManager = {
>   sendAction: async (url, data) => {
>   sentRequest = {url, data};
>   return {ok: true, status: 200, message: "Vote ajouté."};
>   }
> };
> require("./scripts/vote.js");
> const event = {target: {closest: (selector) => selector === ".vote-button" ? button : null}};
> (async () => {
>   await clickHandler(event);
>   if (count.textContent !== "3" || sentRequest.url !== "actions/send_add_vote.php" || sentRequest.data.id !== "5" || button.disabled) throw new Error("Le vote réussi n’a pas été traité correctement");
>   global.ActionManager.sendAction = async () => ({ok: false, status: 404, message: "Idée absente."});
>   await clickHandler(event);
>   if (count.textContent !== "3" || button.disabled) throw new Error("Un vote échoué a modifié le compteur ou bloqué le bouton");
>   console.log("Interface vérifiée : ID envoyé, compteur incrémenté au succès seulement, bouton réactivé.");
>   })()
>   .catch((error) => {
>     console.error(error);
>     process.exitCode = 1;
> });'
> ``` 
> autorisé par l'utilisateur.
>
>
> Le vote AJAX est implémenté sur les cartes, avec un compteur basé uniquement sur vote_approve et une mise à jour après confirmation du serveur.
>


J'ajouterais une instruction "Propose du code clair à relire avant d'exécuter les commandes pour que je puisse plus facilement les relire, même pour des vérifications temporaires." car les codes de
vérifications ont été pénibles à relire.

### Réponse :

> **Changes**
> - Ajout de l’ID de l’idée, du compteur positif et du bouton +1 dans components/idea_card_template.php.
> - Ajout de scripts/action-manager.js pour l’envoi POST générique et la gestion des réponses JSON, erreurs HTTP et réseau.
> - Ajout de scripts/vote.js et chargement dans index.php ; journalisation séparée du traitement de la réponse, et incrément uniquement après succès.
> - Mise en page légère des contrôles de vote dans CSS/style.css.
>
> **Verification**
> - Vérifications de syntaxe PHP et JavaScript réussies.
> - Simulations réussies pour les réponses de succès, HTTP 404, erreur réseau et clic avec mise à jour conditionnelle du compteur.
>
> **Notes**
> - Aucun test contre une base de données réelle n’a été lancé ; les modifications locales préexistantes à .aiignore et IA_NOTES.md ont été préservées.

### Relecture du code et interpretation des résultats :

- Il a supprimé les deux commentaires en lien avec cette implémentation dans le template des cartes.
- Rajouté l'id de la carte dans un `data-idea-id`.
- Il a rajouté une section pour afficher les votes avec le css associé :

```php
<div class="idea-voting">
    <p>Votes positifs : <span class="vote-count" aria-live="polite" aria-atomic="true">
        <?= (int) ($result['vote_approve'] ?? 0); ?></span>
    </p>
    <button type="button" class="vote-button">+1</button>
</div>
```

- Je vais devoir faire des recherches sur les propriétés d'accessibilité `aria-live: polite` et `aria-atomic`
- Je change l'affichage pour retirer le texte "Votes positifs".

- Il a bien ajouté un dossier `scripts` avec un fichier `action-manager.js` et `vote.js` et les a ajoutés à index.html. Il a bien précisé le `defer`.
- Aucun commentaire ni aucune doc de fonctions.

### Script action-manager.js

- Il a directement ajouté un nouveau prototype à la windows `windows.ActionManager = (()=>{})()`, ce qui est une mauvaise pratique, j'aurais préféré une classe et une instance globale.

### Script vote.js

- A pris soin de désactiver le bouton pendant le chargement.
- Il a ajouté une verification du html avant d'incrémenter le nombre affiché :

```js
const currentCount = Number(voteCount.textContent.trim());
if (!Number.isSafeInteger(currentCount) || currentCount < 0 || currentCount === Number.MAX_SAFE_INTEGER) {
    console.error('Le compteur de votes affiché est invalide.');
    return;
}
```

Je pense que c'est inutile étant donné qu'une erreur JS apparaitra s'il n'arrive pas à parser la valeur actuelle.

## Je demande des modifications

Prompt :
> - Dans action-manager: J'aurais préféré une classe avec une instance global plutôt que d'ajouter un element à la windows.
> - Ajoute des commentaires et des documentations de fonctions.
> - Isole mieux l'affichage des résultats en créant des fonctions display_success et display_error.


Relecture des scripts, les modifications sont bonnes.

- Les nouvelles fonctions sont correctes :

```js
/** Displays a successful action result; replace this function to add another notification UI. */
function display_success(message) {
    console.info(message);
}

/** Displays a failed action result; replace this function to add another notification UI. */
function display_error(message) {
    console.error(message);
}
```

Je retire cependant la fin des commentaires qui n'est pas nécessaire.

```js
const currentCount = Number(voteCount.textContent.trim());
if (!Number.isSafeInteger(currentCount) || currentCount < 0 || currentCount === Number.MAX_SAFE_INTEGER) {
    display_error('Le compteur de votes affiché est invalide.');
    return;
}
```

`display_error` ne devrait pas être utilisé ici car ce n'est pas une erreur normale. Je retire cette partie au regard de ce que j'ai signifié à l'itération précédante.

### Test et exécution du code

| Test                       | Résultat attendu                                                   | Résultat                                                         |
|----------------------------|--------------------------------------------------------------------|------------------------------------------------------------------|
| Rien a cassé               | Le site se lance sans erreur et affiche bien le catalogue          | Correct                                                          |
| Nombre de vote             | Le nombre de vote est affiché sur chaque carte et est correct      | Correct                                                          |
| Script load et event       | Le script se charge bien et le catalogue possède un event listener | Pas d'erreur dans la console + event                             |
| ça fonctionne              | Clicker sur un bouton envois la requête                            | Nouvelle requête `send_add_vote` au click avec le bon id         |
| Validation de la requête   | Le backend ne renvois pas d'erreur et incrémente la db             | Correct                                                          |
| Traitement de la réponse   | Message de success dans la console                                 | Le message dans la console est bien celui renvoyé par le backend |
| Mise à jour de l'affichage | Le compteur de vote est incrémenté au click                        | Correct                                                          |

| Test                  | Configuration                                              | Résultat attendu                                                                        | Résultat |
|-----------------------|------------------------------------------------------------|-----------------------------------------------------------------------------------------|----------|
| Affichage d'erreur    | Je force le backend a renvoyer une erreur                  | L'erreur du backend apparait dans la console et l'affichage ne change pas               | Correct  |
| Cas hors service      | Je coupe le serveur et tente d'ajouter un vote             | Erreur dans la console "Impossible de contacter le serveur (erreur réseau)."            | Correct  |
| Erreurs de validation | Je change la requête pour une invalide string + id inconnu | L'erreur de validation du backend apparait dans la console et l'affichage ne change pas | Correct  |

## Ajout d'une librairie de notifications

- J'ai moi-même cherché et ajouté la librairie Toastify d'après leur guide. (je ne laisse pas l'IA choisir ni implémenter la lib)

Prompt :
>
> I added the toastify library, modify the code to display a success notification when the request is successful and the returned error otherwise
>

> Worked for 7s
>
> Vote success and error results now use Toastify notifications, with distinct green and red styling respectively. The existing response handling and success-only vote-count increment remain
> unchanged.
> **Changes**
> - Replaced console output in display_success and display_error with Toastify toasts.
> - Success toasts display for 3 seconds; error toasts display for 4 seconds, both closable.

Je redéfinis le délai d'affichage des erreurs à 10s pour plus de lisibilité et j'ajoute `stopOnFocus: true` d'après la documentation.

| Test   | Résultat attendu                                                                           | Résultat |
|--------|--------------------------------------------------------------------------------------------|----------|
| Import | La librairie est bien importé et pas d'erreur                                              | Correct  |
| Succès | Une notification apparait lorsque j'ajoute un vote et le compteur se met toujours à jours. | Correct  |
| Erreur | Une notification d'erreur apparait lors d'une erreur.                                      | Correct  |