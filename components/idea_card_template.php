<article class="idea">

    <div class="content">
        <div class="info"><?= $result['pseudo']; ?></div>

        <h2><?= $result['title']; ?></h2>

        <p>
            <?= $result['description']; ?>
        </p>

        <span><?= $result['created_at']; ?></span>
        <span><?= $result['vote_approve']; ?></span>
        <span><?= $result['vote_against']; ?></span>
    </div>
</article>