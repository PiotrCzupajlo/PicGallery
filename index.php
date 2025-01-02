<?php
require_once __DIR__ . '/controllers/GalleryController.php';
require_once __DIR__ . '/controllers/AuthController.php';

$galleryController = new GalleryController();
$authController = new AuthController();

session_start();

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'next':
            $galleryController->changePage($_POST['action']);
            $galleryController->index();
            break;
        case 'previous':
            $galleryController->changePage($_POST['action']);
            $galleryController->index();
            break;
        case 'remember':
            $galleryController->rememberSelection();
            $galleryController->index();
            break;
        case 'logout':
                $authController->logout();
            break;
        default:
            $galleryController->upload();
            break;
    }
} elseif (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'register':
            $authController->register();
            break;
        case 'login':
            $authController->login();
            break;

        case 'gallery':
            $galleryController->index();
            break;
        default:
            $galleryController->index();
            break;
    }
} else {
    if (isset($_SESSION['user'])) {
        $galleryController->index();
    } else {
        $authController->login();
    }
}
