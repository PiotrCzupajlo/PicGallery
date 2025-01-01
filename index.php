<?php
require_once __DIR__ . '/controllers/GalleryController.php';
require_once __DIR__ . '/controllers/AuthController.php';

$galleryController = new GalleryController();
$authController = new AuthController();

session_start();

// Handle requests
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'register':
            $authController->register();
            break;
        case 'login':
            $authController->login();
            break;
        case 'logout':
            $authController->logout();
            break;
        case 'gallery': // New case for the gallery page
            $galleryController->index();
            break;
        default:
            $galleryController->index();
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && in_array($_POST['action'], ['next', 'previous'])) {
        $galleryController->changePage($_POST['action']);
        $galleryController->index();
    } else {
        $galleryController->upload();
    }
} else {
    // Check if the user is logged in
    if (isset($_SESSION['user'])) {
        // Redirect to gallery page if the user is logged in
        $galleryController->index();
    } else {
        // Redirect to login page by default
        $authController->login();
    }
}
