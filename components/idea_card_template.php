<article class="idea" data-idea-id="<?= htmlspecialchars($result['id']); ?>">
    <div class="content">
        <div class="info"><?= htmlspecialchars($result['pseudo']); ?></div>

        <h2><?= htmlspecialchars($result['title']); ?></h2>

        <p>
            <?= htmlspecialchars($result['description']); ?>
        </p>

        <div class="idea-voting">
            <p><span class="vote-count" aria-live="polite" aria-atomic="true"><?= htmlspecialchars(($result['vote'] ?? 0)); ?></span> votes</p>
            <button type="button" class="vote-button">+1</button>
        </div>

        <span><?= htmlspecialchars($result['created_at']); ?></span>
    </div>
</article>