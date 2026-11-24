<article class="idea category-<?= $result['category_id']?>" id="idea">

    <div class="content">
        <div class="info"><?= $result['pseudo']; ?></div>

        <h2><?= $result['title']; ?></h2>

        <p>
            <?= $result['content']; ?>
        </p>

    </div>
</article>