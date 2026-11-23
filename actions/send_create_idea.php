<?php
require_once __DIR__ . '/../controllers/idea_manager.php';

# Construct data from request
$data = [];

# Idea title
$idea_title = $_GET["title"];
# TODO: Add validation
$data["title"] = $idea_title;

# Idea content
$idea_content = $_GET["content"];
# TODO: Add validation
$data["description"] = $idea_content;

# Author pseudo
$author_pseudo = $_GET["pseudo"];
# TODO: Add validation
$data["pseudo"] = $author_pseudo;

# Execute
try {
    addIdea($data);
} catch (Exception $e) {
    echo $e;
}