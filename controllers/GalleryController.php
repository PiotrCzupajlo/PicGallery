<?php
class GalleryController {
    public function index() {
        require_once 'models/Image.php';
        $images = Image::getAll();
        require_once 'views/gallery/index.php';
    }

    public function upload() {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $fileName = basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                require_once 'models/Image.php';
                Image::add($fileName);
                header('Location: index.php');
                exit();
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "No file uploaded.";
        }
    }
}