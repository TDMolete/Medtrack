<?php
session_start();
$page_title = "Access Denied";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - MedTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center mt-5">
        <h1 class="display-4">403</h1>
        <p class="lead">You do not have permission to view this page.</p>
        <a href="index.php" class="btn btn-primary">Go Home</a>
    </div>
</body>
</html>