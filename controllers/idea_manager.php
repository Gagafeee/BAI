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
        if ($query->rowCount() === 0) throw new Exception("Nous n'avons pas pu modifier cette idée.");
    } catch (PDOException $error) {
        throw new Exception("Impossible d'ajouter votre vote : La base de donnée à renvoyé une erreur.");
    }
}

function get_idea(int $id)
{
    try {
        $pdo = getPDO();

        $query = $pdo->prepare("SELECT * FROM ideas WHERE id = :id;");
        $query->execute([":id" => $id]);
        return $query->fetch();
    } catch (PDOException $error) {
        throw new Exception("Impossible de récupérer les informations de votre idée : La base de donnée à renvoyé une erreur.");
    }
}

function does_idea_exist(int $id): bool
{
    return gettype(get_idea($id)) == "array";
}