<?php
require_once __DIR__ . "/../controllers/idea_manager.php";
require_once __DIR__ . '/../libs/response.php';

# idea id
$idea_id = $_POST["id"];
if (!isset($idea_id)) respond_error(400, "Impossible d'ajouter un vote : Aucune idée n'a été spécifié.");
$idea_id = (int)$idea_id;
if (gettype($idea_id) != "integer") respond_error(400, "Impossible d'ajouter un vote : L'identifiant de l'idée n'est pas valide.");

# Execute
try {
    add_approve_vote($idea_id);
    respond_success("Nous avons bien ajouté votre vote.");
} catch (Exception $e) {
    respond_error(500, $e->getMessage());
}