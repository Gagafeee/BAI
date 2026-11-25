<?php
require 'components/card_functions.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boite à idée</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>

<body>
    <header>
    </header>
    <div>
        <form action="/actions/send_create_idea.php">
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
        <form class="trie" id="form-tri">
            <label>Trié par ...</label>
             <select id="trie" name="trie" >
                <option value="ancien" >Plus ancien</option>
                <option value="recent">Plus récent</option>
                <option value="note">Plus voté</option>
            </select>
    </div>
    <div>
        <div class="catalogue">
            <?php addIdea(); ?>
        </div>
    </div>
    <script src="JS/tri.js"></script>
</body>



</html>