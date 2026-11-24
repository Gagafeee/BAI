<?php
require_once __DIR__ . "/../controllers/idea_manager.php";
require_once __DIR__ . '/../libs/response.php';

# Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond_error(405);

# idea id
$idea_id = $_POST["id"] ?? null;
if (!isset($idea_id)) respond_error(400, "Impossible d'ajouter un vote : Aucune idée n'a été spécifié.");
# Vérifie que tous les caractères de la chaine sont bien des chiffres.
if (!ctype_digit($idea_id)) respond_error(400, "Impossible d'ajouter un vote : L'identifiant de l'idée n'est pas valide.");
$idea_id = intval($idea_id);
if (doesIdeaExist($idea_id) === false) respond_error(404, "Impossible d'ajouter votre vote : Cette idée n'existe pas.");

# Execute
try {
    add_approve_vote($idea_id);
    respond_success("Nous avons bien ajouté votre vote.");
} catch (Exception $e) {
    respond_error(500, $e->getMessage());
}