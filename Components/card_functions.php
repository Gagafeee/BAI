<?php

require 'libs/db.php';

function addIdea() {
    $pdo = getPDO();

    $query      = $pdo->query('SELECT * FROM ideas ORDER BY title');
    $results    = $query->fetchALL();

    foreach($results as $key => $result) {
        include 'idea_card_template.php';
    }

}