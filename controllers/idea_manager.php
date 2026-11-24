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

function add_approve_vote(int $idea_id): void
{
    try {
        $pdo = getPDO();

        $query = $pdo->prepare("UPDATE ideas SET vote_approve = vote_approve + 1 WHERE id = :idea_id");
        $query->execute(["idea_id" => $idea_id]);
    } catch (PDOException $error) {
        throw new Exception("Impossible d'ajouter votre vote : La base de donnée à renvoyé une erreur.");
    }
}