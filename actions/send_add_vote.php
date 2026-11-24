<?php
require_once __DIR__ . "/../controllers/idea_manager.php";
require_once __DIR__ . '/../libs/response.php';

# idea id
$idea_id = $_POST["id"];
# TODO: Add validation

# Execute
try {
    add_approve_vote($idea_id);
    respond_success("Nous avons bien ajouté votre vote.");
} catch (Exception $e) {
    echo $e;
}