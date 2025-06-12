<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SCP Foundation - Create new log</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="stylesheet.css">
        <link rel="icon" type="image/png" href="images/page logo.png">
    </head>
  <body>
        <header class="text-center text-lg-start">
            <img src="images/SCP main logo.png" alt="SCP Logo" class="logo">
            <h1 class="display-1 ps-lg-4">SCP Foundation</h1>
            <p class="ps-lg-4">The one stop shop for you paranormal needs</p>
        </header>

        <?php include "connection.php";?>

        <nav class="mt-3 mb-3 d-flex flex-wrap justify-content-center gap-2">
            <?php foreach($result as $link): ?>
                <a href="index.php?link=<?php echo urlencode($link['Item']); ?>" class="btn btn-dark"><?php echo urlencode($link['Item']); ?></a>
            <?php endforeach; ?>
            
            <a href="create.php" class="btn btn-dark">Log New SCP</a>
        </nav>
    <div class="container">

        <?php

                include "connection.php";

                if(isset($_POST['submit']))
                {
                    // write a prepared statement to insert data
                    $insert = $connection->prepare("insert into SCP_Data(Item, Class, Description, Image) values(?,?,?,?)");
                    $insert->bind_param("ssss", $_POST['Item'], $_POST['Class'], $_POST['Description'], $_POST['Image']);

                    if($insert->execute())
                    {
                        echo "
                            <div class='alert alert-success p-3 m-3'>Record successfully created</div>
                        ";
                    }
                    else
                    {
                        echo "
                        <div class='alert alert-danger p-3 m-3'>Error: {$insert->error}</div>
                        ";
                    }
                }
            
        ?>
        <h1>Create a new record.</h1>
        <p><a href="index.php" class="btn btn-dark">Back to index page.</a></p>
        <div class="p-3 rounded border border-secondary shadow bg-dark text-light">
            <form method="post" action="create.php" class="form-group">
                <label>Enter New SCP:</label>
                <br>
                <input type="text" name="Item" placeholder="SCP Designation..." class="form-control bg-secondary text-white" required>
                <br><br>
                <label>Enter Class:</label>
                <br>
                <select name="Class" class="form-control bg-secondary text-white" required>
                    <option value="" disabled selected style="color: #6c757d;">Select Class...</option>
                    <option value="Safe">Safe</option>
                    <option value="Euclid">Euclid</option>
                    <option value="Keter">Keter</option>
                </select>
                <br><br>
                <label>Enter image:</label>
                <br>
                <input type="text" name="Image" placeholder="images/nameofimage.png..." class="form-control bg-secondary text-white">
                <br><br>
                <label>Enter description:</label>
                <br>
                <textarea name="Description" class="form-control bg-secondary text-white" placeholder="Enter description:" required></textarea>
                <br><br>
                <input type="submit" name="submit" class="btn btn-info mt-3">
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>