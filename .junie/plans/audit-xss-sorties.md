---
sessionId: session-261125-114258-1k93
---

# Objectif et périmètre

### Objectif
Identifier les sorties où des données non fiables pourraient être interprétées comme du HTML ou du JavaScript, puis fournir des recommandations classées par risque.

### Périmètre
- Examiner le flux des champs `pseudo`, `title` et `content` de `index.php` à la base, puis leur affichage dans `components/idea_card_template.php`.
- Examiner les réponses et erreurs de `actions/send_create_idea.php`, `actions/send_add_vote.php` et `libs/response.php`, ainsi que leur affichage dans `scripts/action-manager.js` et `scripts/vote.js`.
- Préserver le fonctionnement sans authentification et les votes répétés à l’infini, qui sont explicitement souhaités.
- Produire un audit et des corrections recommandées; ne pas modifier les fichiers du projet dans ce périmètre.

# Analyse technique

### Parcours et constats dans le code
- `components/card_functions.php` récupère les idées et délègue leur rendu à `components/idea_card_template.php`. Le gabarit applique déjà `htmlspecialchars()` au pseudo, au titre, à la description, à l’identifiant, au nombre de votes et à la date; aucun champ de la base non échappé n’a été repéré dans ce rendu.
- `actions/send_create_idea.php` prend les trois champs du formulaire sans validation serveur, puis `controllers/idea_manager.php` les insère avec une requête PDO préparée. La validation serveur est à examiner pour robustesse, mais elle ne remplace pas l’encodage au moment du rendu.
- Point de sortie à risque : `actions/send_create_idea.php` fait `echo $e` dans le bloc `catch`. L’exception est donc rendue directement dans la réponse HTML; les erreurs applicatives actuelles semblent employer des messages génériques, mais cette sortie brute peut divulguer des détails et devenir une injection HTML si une exception contient ultérieurement une donnée contrôlable.
- Les réponses de `libs/response.php` sont encodées en JSON. `scripts/vote.js` met à jour le compteur avec `textContent` et affiche les messages avec Toastify. La documentation actuelle de Toastify indique que l’échappement HTML est activé par défaut (`escapeMarkup`), mais `index.php` charge Toastify depuis un CDN sans version explicitement épinglée et les appels ne fixent pas cette option.

### Décisions et livrable
- Distinguer les vulnérabilités directement démontrées des points conditionnels ou de durcissement; ne pas présenter l’absence de validation côté serveur comme une XSS en soi.
- Recommander une réponse d’erreur générique plutôt que l’affichage brut de l’exception, avec journalisation côté serveur, et expliciter l’encodage dans le contexte HTML (`ENT_QUOTES`, `ENT_SUBSTITUTE`, `UTF-8`) pour rendre l’intention du gabarit robuste.
- Vérifier le contrat Toastify et recommander de fixer explicitement l’échappement des notifications ou de rendre leur texte via une API DOM texte si la bibliothèque ne garantit plus ce comportement.
- Ne pas inclure de limitation des votes, d’authentification, ni de mécanisme anti-vote répété.

# Validation

### Approche
- Revoir statiquement chaque source contrôlable et chaque sortie HTML/DOM trouvée dans les fichiers PHP et JavaScript concernés; confirmer qu’aucune autre insertion HTML brute n’est présente.
- Vérifier le flux des erreurs et des notifications depuis la réponse serveur jusqu’au navigateur, y compris le comportement d’échappement de la version de Toastify chargée.
- Dans un environnement local, contrôler que les caractères spéciaux saisis dans les idées sont affichés comme du texte et que les notifications ne les interprètent pas comme du balisage.
- Remettre une liste de constats avec emplacement, niveau de risque, caractère exploitable ou conditionnel, et recommandation. Aucun test de vote ne doit imposer une limite au nombre de votes.

# Delivery Steps

###   Step 1: Tracer les sources non fiables et les sorties
Le flux des données saisies et des réponses serveur jusqu’aux contextes HTML et DOM concernés est documenté.

- Examiner les entrées de `index.php`, leur transfert par `actions/send_create_idea.php` et leur persistance via `controllers/idea_manager.php`.
- Vérifier les contextes de sortie dans `components/idea_card_template.php`, les réponses JSON de `libs/response.php` et le rendu des notifications dans `scripts/action-manager.js` et `scripts/vote.js`.
- Rechercher les autres sorties PHP ou insertions HTML JavaScript dans le projet et noter les contrôles déjà en place.

###   Step 2: Qualifier les risques et remettre les recommandations
Un rapport concis distingue les risques XSS actuels des renforcements préventifs, sans changement de code ni de comportement des votes.

- Qualifier `echo $e` dans `actions/send_create_idea.php` comme sortie brute à risque et documenter les conditions de son exploitabilité actuelle.
- Confirmer l’échappement des champs dans `components/idea_card_template.php` et vérifier la garantie Toastify liée à `escapeMarkup` et au CDN non versionné.
- Proposer des corrections ciblées, puis valider en environnement local l’affichage textuel des caractères spéciaux dans les cartes et notifications.
- Exclure explicitement toute authentification, limitation ou déduplication des votes.