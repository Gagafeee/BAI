<?php
require_once __DIR__ . '/../controllers/idea_manager.php';
require_once __DIR__ . '/../libs/response.php';

# Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond_error(405);

# Construct data from request
$data = [];

# Idea title
$idea_title = $_POST["title"] ?? null;
# TODO: Add validation
$data["title"] = $idea_title;

# Idea content
$idea_content = $_POST["content"] ?? null;
# TODO: Add validation
$data["description"] = $idea_content;

# Author pseudo
$author_pseudo = $_POST["pseudo"] ?? null;
# TODO: Add validation
$data["pseudo"] = $author_pseudo;

# Execute
try {
    addIdea($data);
    set_redirect_successful("", "Nous avons bien ajouté votre idée.");
} catch (Exception $e) {
    echo $e;
}