<?php
require_once __DIR__ . "/../controllers/idea_manager.php";
require_once __DIR__ . '/../libs/response.php';

# idea id
$idea_id = $_POST["id"];
if (!isset($idea_id)) respond_error(400, "Impossible d'ajouter un vote : Aucune idée n'a été spécifié.");
# Vérifie que tous les caractères de la chaine sont bien des chiffres.
if (!ctype_digit($idea_id)) respond_error(400, "Impossible d'ajouter un vote : L'identifiant de l'idée n'est pas valide.");
$idea_id = intval($idea_id);

# Execute
try {
    add_approve_vote($idea_id);
    respond_success("Nous avons bien ajouté votre vote.");
} catch (Exception $e) {
    respond_error(500, $e->getMessage());
}