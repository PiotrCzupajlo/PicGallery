<?php
class GalleryController {
    public function index() {
        require_once __DIR__ . '/../models/Image.php';
        $images = Image::getAll();
        require_once __DIR__ . '/../views/gallery/index.php';
    }

    public function upload() {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            $fileName = basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $fileName;

            $watermarkText = isset($_POST['watermark']) ? $_POST['watermark'] : 'Watermark';

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Add watermark to the image
                $this->addWatermark($targetFile, $watermarkText);

                // Create a miniature of the image
                $miniatureFile = $this->createMiniature($targetFile);

                require_once __DIR__ . '/../models/Image.php';
                $miniatureFileName = basename($miniatureFile);
                Image::add($miniatureFileName);
                header('Location: ../index.php');
                exit();
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "No file uploaded.";
        }
    }

    private function addWatermark($filePath, $watermarkText) {
        $fileType = mime_content_type($filePath);
        $pathInfo = pathinfo($filePath);
        $watermarkedFilePath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_watermarked.' . $pathInfo['extension'];

        // Create image resource based on file type
        switch ($fileType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($filePath);
                break;
            default:
                echo "Unsupported file type: " . htmlspecialchars($fileType);
                return;
        }

        $color = imagecolorallocate($image, 255, 255, 255); // White color
        $fontFile = __DIR__ . '/../fonts/arial.ttf'; // Path to a TTF font file
        $fontSize = 20;

        // Position the watermark at the bottom-right corner
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $watermarkText);
        $x = imagesx($image) - $textBox[2] - 10; // 10px margin
        $y = imagesy($image) - 10; // 10px from bottom

        imagettftext($image, $fontSize, 0, $x, $y, $color, $fontFile, $watermarkText);

        // Save the image to the new file
        switch ($fileType) {
            case 'image/jpeg':
                imagejpeg($image, $watermarkedFilePath);
                break;
            case 'image/png':
                imagepng($image, $watermarkedFilePath);
                break;
        }

        imagedestroy($image);

        // Return the path of the watermarked image
        return $watermarkedFilePath;
    }

    private function createMiniature($filePath) {
        $fileType = mime_content_type($filePath);
        $pathInfo = pathinfo($filePath);
        $miniatureFilePath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_miniature.' . $pathInfo['extension'];

        // Create image resource based on file type
        switch ($fileType) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($filePath);
                break;
            default:
                echo "Unsupported file type: " . htmlspecialchars($fileType);
                return;
        }

        $thumbnailWidth = 150;
        $thumbnailHeight = 150;
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        $thumbnail = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);
        imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $originalWidth, $originalHeight);

        // Save the thumbnail to the new file
        switch ($fileType) {
            case 'image/jpeg':
                imagejpeg($thumbnail, $miniatureFilePath);
                break;
            case 'image/png':
                imagepng($thumbnail, $miniatureFilePath);
                break;
        }

        imagedestroy($image);
        imagedestroy($thumbnail);

        // Return the path of the miniature image
        return $miniatureFilePath;
    }
}