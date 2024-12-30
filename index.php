<?php
// File structure:
// - index.php
// - controllers/
//    - GalleryController.php
// - models/
//    - Image.php
// - views/
//    - gallery/
//       - index.php
//       - upload.php
// - uploads/

// index.php
// index.php
require_once __DIR__ . '/controllers/GalleryController.php';

$controller = new GalleryController();

// Handle requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the action is for page navigation
    if (isset($_POST['action']) && in_array($_POST['action'], ['next', 'previous'])) {
        $controller->changePage($_POST['action']); // Change the page based on the action
        $controller->index(); // Reload the index view
    } else {
        $controller->upload(); // Handle file upload
    }
} else {
    $controller->index(); // Display the gallery
}