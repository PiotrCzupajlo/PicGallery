<?php
class Image {
    private static $images = [];

    public static function getAll() {
        $filePath = __DIR__ . '/../uploads/images.json';
        if (!file_exists($filePath)) {
            file_put_contents($filePath, json_encode([])); // Initialize the file if it doesn't exist
        }
        self::$images = json_decode(file_get_contents($filePath), true);
        return self::$images;
    }

    public static function add($fileName) {
        $filePath = __DIR__ . '/../uploads/images.json';
        self::$images = self::getAll(); // Refresh the images array
        self::$images[] = $fileName;   // Add the new file
        file_put_contents($filePath, json_encode(self::$images)); // Save updated array
    }
}