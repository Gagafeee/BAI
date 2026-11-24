<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boite à idée</title>
    <link rel="stylesheet" href="CSS/style.css">
    <script src="/scripts/form_action.js" defer></script>
</head>

<body>
<header>
</header>

<div>
    <form action="/actions/send_create_idea.php" method="POST">
        <div>
            <input type="text" maxlength="30" placeholder="Votre pseudo..." name="pseudo" id="pseudo">
            <input type="hidden" name="">
        </div>
        <div class="idea">
            <input type="text" maxlength="100" placeholder="Titre de votre idee..." name="title" id="titre">
            <textarea type="text" maxlength="1000" placeholder="Contenu de votre idee..." name="content" id="content"></textarea>
        </div>
        <div>
            <input type="submit" value="envoyer">
        </div>
    </form>
</div>

<div>
    <!--TODO: Bouton pour ajouter un vote sur une idée. Remplacer le     '1' par l'id de l'idée.-->
    <button onclick="sendActionRequest('/actions/send_add_vote.php', {id: 1})">+1</button>
</div>
</body>


</html>