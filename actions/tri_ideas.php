<?php
require_once __DIR__ . '/../libs/db.php';

$tri = $_GET['tri'] ?? 'ancien';

$pdo = getPDO();

switch ($tri) {

    case 'ancien':
        $orderBy = 'created_at ASC';
        break;

    case 'recent':
        $orderBy = 'created_at DESC';
        break;

    case 'note':
        $orderBy = 'vote_approve DESC';
        break;

    default:
        $orderBy = 'created_at ASC';
        break;
}

$query = $pdo->query("SELECT * FROM ideas ORDER BY $orderBy");

$results = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $result) {

?>

<article class="idea">

    <div class="content">

        <div class="info">
            <?= htmlspecialchars($result['pseudo']); ?>
        </div>

        <h2>
            <?= htmlspecialchars($result['title']); ?>
        </h2>

        <p>
            <?= htmlspecialchars($result['description']); ?>
        </p>

        <span>
            <?= htmlspecialchars($result['created_at']); ?>
        </span>

        <span>
            <?= (int) $result['vote_approve']; ?>
        </span>

        <span>
            <?= (int) $result['vote_against']; ?>
        </span>

    </div>

</article>

    <?php
}

?>
