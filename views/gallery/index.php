<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
</head>
<body>
    <h1>Picture Gallery</h1>
    <form action="../index.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <input type="text" name="watermark" placeholder="Enter watermark text" required>
        <button type="submit">Upload</button>
    </form>
    <div>
        <h2>Uploaded Images</h2>
        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
            <?php foreach ($images as $image): ?>
                <div style="border: 1px solid #ccc; padding: 10px;">
                    <img src="../uploads/<?php echo htmlspecialchars($image); ?>" alt="" style="max-width: 200px; height: auto;">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
