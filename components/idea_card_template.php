<article class="idea">

    <!--TODO: Ne pas oublier d'ajouter la mise à jour de l'UI après avoir ajouté +1-->
    <!--TODO: Bouton pour ajouter un vote sur une idée. Remplacez le     '1' par l'id de l'idée.-->
    <div class="content">
        <div class="info"><?= $result['pseudo']; ?></div>

        <h2><?= $result['title']; ?></h2>

        <p>
            <?= $result['description']; ?>
        </p>

    </div>
</article>