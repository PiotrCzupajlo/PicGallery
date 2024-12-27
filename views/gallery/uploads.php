<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
</head>
<body>
    <h1>Upload a New Image</h1>
    <form action="../index.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <input type="text" name="watermark" placeholder="Enter watermark text" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
