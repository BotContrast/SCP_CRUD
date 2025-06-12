<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SCP Foundation - Update</title>
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
     
            // Enable error reporting
            error_reporting(E_ALL);

            // Display errors
            ini_set('display_errors', 1);
     
            include "connection.php";
            
            // initialise $row as empty array
            $row = [];
            
            // directed from index page record [update] button
            if(isset($_GET['update']))
            {
                $id = $_GET['update'];
                // based on id select appropriate record from db
                $recordID = $connection->prepare("select * from SCP_Data where ID = ?");
                
                if(!$recordID)
                {
                    echo "<div class='alert alert-danger p-3 m-2'>Error preparing record for updating.</div>";
                    exit;
                }
                
                $recordID->bind_param("i", $id);
                
                if($recordID->execute())
                {
                    echo "<div class='alert alert-success p-3 m-2'>Record ready for updating.</div>";
                    $temp = $recordID->get_result();
                    $row = $temp->fetch_assoc();
                }
                else
                {
                    echo "<div alert alert-danger p-3 m-2>Error: {$recordID->error}</div>";
                }
            }
            
           if(isset($_POST['update']))
           {
                // write a prepare statement to update data
                $update = $connection->prepare("update SCP_Data set Item=?, Class=?, Description=?, Image=?  where ID=?");
            
                $update->bind_param("ssssi", $_POST['Item'], $_POST['Class'], $_POST['Description'], $_POST['Image'], $_POST['ID']);
                
                if($update->execute())
                {
                    echo "<div class='alert alert-success p-3 m-2'>Record updated successfully</div>";
                }
                else
                {
                    echo "<div class='alert alert-danger p-3 m-2'>Error: {$update->error}</div>";
                }
           }
           
        

      ?>
      
      
        <h1>Update Record</h1>
        <p><a href="index.php" class="btn btn-dark">Back to index page.</a></p>
           
        <div class="p-3 rounded border border-secondary shadow bg-dark text-light">
            <form method="post" action="update.php" class="form-group">
                <input type="hidden" name="ID" value="<?php echo isset($row['ID']) ? $row['ID'] : ''; ?>">
           
                <label>Enter New SCP:</label>
                <br>
                <input type="text" name="Item" placeholder="SCP Designation..." class="form-control bg-secondary text-white" value="<?php echo isset($row['Item']) ? $row['Item'] : ''; ?>" required>
                <br><br>
           
                <label>Enter Class:</label>
                <br>
                <select name="Class" class="form-control bg-secondary text-white" required>
                    <option value="" disabled>Select Class...</option>
                    <option value="Safe" <?php if(isset($row['Class']) && $row['Class'] === "Safe") echo "selected"; ?>>Safe</option>
                    <option value="Euclid" <?php if(isset($row['Class']) && $row['Class'] === "Euclid") echo "selected"; ?>>Euclid</option>
                    <option value="Keter" <?php if(isset($row['Class']) && $row['Class'] === "Keter") echo "selected"; ?>>Keter</option>
                </select>
                <br><br>
           
                <label>Enter image:</label>
                <br>
                <input type="text" name="Image" placeholder="images/nameofimage.png..." class="form-control bg-secondary text-white" value="<?php echo isset($row['Image']) ? $row['Image'] : ''; ?>">
                <br><br>
           
                <label>Enter description:</label>
                <br>
                <textarea name="Description" class="form-control bg-secondary text-white" placeholder="Enter description:"><?php echo isset($row['Description']) ? $row['Description'] : ''; ?></textarea>
                <br><br>
           
                <input type="submit" name="update" value="Update Record" class="btn btn-warning mt-3">
            </form>
        </div>

        

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>