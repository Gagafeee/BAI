<?php
require_once __DIR__ . "/../libs/db.php";
function addIdea(array $idea): void
{
    try {
        $pdo = getPDO();

        $query = $pdo->prepare("INSERT INTO ideas (pseudo, title, description) VALUES (:pseudo, :title, :description);");
        $query->execute($idea);
    } catch (PDOException $error) {
        throw new Exception("Impossible d'ajouter votre idée : La base de donnée à renvoyé une erreur.");
    }
}