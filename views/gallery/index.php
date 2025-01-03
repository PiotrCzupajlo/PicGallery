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
        <input type="hidden" name="action" value="upload">
        <button type="submit">Upload</button>
    </form>

    <form method="POST" action="../index.php">
        <div class="gallery">
            <?php foreach ($results as $image): ?>
                <div class="gallery-item">
                    <img src="../uploads/<?= htmlspecialchars($image['fileName']) ?>" alt="Image" class="image" data-fullimg="../uploads/<?= htmlspecialchars($image['fullimg']) ?>">
                    <h3><?= htmlspecialchars($image['title']) ?></h3>
                    <p>By: <?= htmlspecialchars($image['author']) ?></p>
                    <p>Full Image: <?= htmlspecialchars($image['fullimg']) ?></p>
                    <label>
                        <input type="checkbox" name="selected_images[]" value="<?= htmlspecialchars($image['_id']) ?>"
                        <?php if (isset($_SESSION['selected_images']) && in_array($image['_id'], $_SESSION['selected_images'])): ?>
                            checked
                        <?php endif; ?>>
                        Remember this image
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" name="action" value="remember">Remember</button>
    </form>

    <form method="POST" action="../index.php" style="display: inline;">
        <input type="hidden" name="action" value="previous">
        <button type="submit">Previous Page</button>
    </form>

    <form method="POST" action="../index.php" style="display: inline;">
        <input type="hidden" name="action" value="next">
        <button type="submit">Next Page</button>
    </form>
    <form method="POST" action="../index.php" style="display: inline;">
        <input type="hidden" name="action" value="logout">
        <button type="submit">logout</button>
    </form>
    <form method="POST" action="../index.php" style="display: inline;">
    <input type="hidden" name="action" value="remembered">
    <button type="submit">Show Only Remembered</button>
</form>

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
