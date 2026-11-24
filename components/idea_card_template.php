<article class="idea" data-idea-id="<?= (int)$result['id']; ?>">
    <div class="content">
        <div class="info"><?= $result['pseudo']; ?></div>

        <h2><?= $result['title']; ?></h2>

        <p>
            <?= $result['description']; ?>
        </p>

        <div class="idea-voting">
            <p><span class="vote-count" aria-live="polite" aria-atomic="true"><?= (int)($result['vote_approve'] ?? 0); ?></span> votes</p>
            <button type="button" class="vote-button">+1</button>
        </div>

        <span><?= $result['created_at']; ?></span>
    </div>
</article>