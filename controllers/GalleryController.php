<?php
require_once __DIR__ . '/../models/Image.php';
require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

class GalleryController {

    public function index() {
        $images = $this->getImagesFromDatabase();

        // Convert the MongoDB cursor to an array
        $imagesArray = iterator_to_array($images);
        
        if (!isset($_SESSION['page'])) {
            $_SESSION['page'] = 0; 
        }
        
        $results = []; // Ensure $results is initialized
        $i = count($imagesArray); // Get the count of images
        
        for ($j = 4 * $_SESSION['page']; $j < $i && $j< 4*($_SESSION['page']+1); $j++) {
            if (isset($imagesArray[$j])) {
                array_push($results, $imagesArray[$j]);
            }
        }
        require_once __DIR__ . '/../views/gallery/index.php';
    }
    public function changePage($action) {
        // Ensure the session variable is initialized
        if (!isset($_SESSION['page'])) {
            $_SESSION['page'] = 0;
        }

        // Update the page variable based on the action
        if ($action === 'next') {
            $_SESSION['page']++;
        } elseif ($action === 'previous' && $_SESSION['page'] > 0) {
            $_SESSION['page']--;
        }
    }

    public function upload() {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/';
            $fileName = basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $fileName;

            $watermarkText = isset($_POST['watermark']) ? $_POST['watermark'] : 'Watermark';
            $title = isset($_POST['title']) ? $_POST['title'] : 'Untitled';
            $author = isset($_POST['author']) ? $_POST['author'] : 'Unknown';

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Add watermark to the image
                $this->addWatermark($targetFile, $watermarkText);


                $miniatureFile = $this->createMiniature($targetFile);
                $miniatureName = substr($fileName,0,strlen($fileName)-4);
                $end = substr($fileName,strlen($fileName)-4);
                $this->saveToDatabase($miniatureName . '_miniature' . $end, $title, $author,$fileName);

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

        $thumbnailWidth = 200;
        $thumbnailHeight = 125;
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

    private function saveToDatabase($fileName, $title, $author,$fileName2) {
        $mongo = new MongoDB\Client(
            "mongodb://localhost:27017/wai"
            ,
            [
            'username' => 'wai_web'
            ,
            'password' => 'w@i_w3b',
            ]);
            $db = $mongo->wai;
       $collection=[
       'fileName' => $fileName,
       'title' => $title,
       'author' => $author,
       'fullimg'=>$fileName2,
       'uploadedAt' => new MongoDB\BSON\UTCDateTime()
       ];
       $result = $db->collection->insertOne($collection);
    }
    private function getImagesFromDatabase() {
        $mongo = new MongoDB\Client(
            "mongodb://localhost:27017/wai"
            ,
            [
            'username' => 'wai_web'
            ,
            'password' => 'w@i_w3b',
            ]);
            $db = $mongo->wai;
            $results = $db->collection->find();

        return $results;
    }


}