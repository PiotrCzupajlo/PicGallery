<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
</head>
<body>
    <h1>Picture Gallery</h1>
    <form action="../index.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <input type="text" name="watermark" placeholder="Enter watermark text" required>
        <input type="text" name="title" placeholder="Enter image title" required>
        <input type="text" name="author" placeholder="Enter author name" required>
        <button type="submit">Upload</button>
    </form>

    <div class="gallery">
        <?php foreach ($images as $image): ?>
            <div class="gallery-item">
                <img src="../uploads/<?= htmlspecialchars($image['fileName']) ?>" alt="Image" class="image" data-fullimg="../uploads/<?= htmlspecialchars($image['fullimg']) ?>">
                <h3><?= htmlspecialchars($image['title']) ?></h3>
                <p>By: <?= htmlspecialchars($image['author']) ?></p>
                <p>Full Image: <?= htmlspecialchars($image['fullimg']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // Add event listener to each image
        document.querySelectorAll('.image').forEach(function(image) {
            image.addEventListener('click', function() {
                // Get the full image URL from the data-fullimg attribute
                var fullimgUrl = this.getAttribute('data-fullimg');
                
                // Change the src attribute of the image to the full image URL
                this.setAttribute('src', fullimgUrl);
            });
        });
    </script>
</body>
</html>