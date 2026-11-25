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
        $orderBy = 'vote DESC';
        break;

    default:
        $orderBy = 'created_at ASC';
        break;
}

$query = $pdo->query("SELECT * FROM ideas ORDER BY $orderBy");
$results = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $result) {
    include __DIR__ . "/../components/idea_card_template.php";
}

