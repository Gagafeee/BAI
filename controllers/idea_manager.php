<?php

function addIdea(array $idea): void
{
    try {

        $pdo = getPDO();

        $query = $pdo->prepare("INSERT INTO ideas (pseudo, title, description) VALUES (:pseudo, :title, :description);");
        $query->execute($idea);
    } catch (PDOException $error) {
        throw new Exception("La base de donnée à renvoyé une erreur.");
    }
}