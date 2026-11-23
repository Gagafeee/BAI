<?php
require_once __DIR__ . '/../controllers/idea_manager.php';

# Construct data from request
$data = [];

# Idea title
$idea_title = $_GET("title");
# TODO: Add validation

# Idea content
$idea_content = $_GET("content");
# TODO: Add validation

# Author pseudo
$author_pseudo = $_GET("pseudo");
# TODO: Add validation

# Idea content
$idea_content = $_GET("content");
# TODO: Add validation

# Execute
try {
    addIdea($data);
} catch (Exception $e) {
    echo $e;
}