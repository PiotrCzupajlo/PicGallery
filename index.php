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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->upload();
} else {
    $controller->index();
}
