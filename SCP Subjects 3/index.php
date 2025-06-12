<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SCP Foundation</title>
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


            <div>
                <?php

                    if(isset($_GET['link'])){
                        $SCP = $_GET['link'];

                        $stmt = $connection->prepare("SELECT * from SCP_Data where Item = ?");
                        if(!$stmt)
                        {
                            echo "<p>Error in preparing SQL statement</p>";
                            exit;
                        }
                        $stmt->bind_param("s", $SCP);

                        if($stmt->execute())
                        {
                            $result = $stmt->get_result();

                            if($result->num_rows > 0)
                            {
                                $array = array_map('htmlspecialchars', $result->fetch_assoc());
                                $update = "update.php?update=" . $array['ID'];
                                $delete = "index.php?delete=" . $array['ID'];

                                // Decide CSS classes based on object class
                                if ($array['Class'] === "Euclid") {
                                    $classStyleAlert = "alert alert-warning";
                                    $classStyleBoarder = "p-3 rounded border border-warning-subtle mt-3";
                                    $classHeader = "text-warning";
                                } elseif($array['Class'] === "Safe") {
                                    $classStyleAlert = "alert alert-primary";
                                    $classStyleBoarder = "p-3 rounded border border-primary-subtle mt-3";
                                    $classHeader = "text-info";
                                } else {
                                    $classStyleAlert = "alert alert-danger";
                                    $classStyleBoarder = "p-3 rounded border border-danger-subtle mt-3";
                                    $classHeader = "text-danger";
                                }

                                // Display data
                                echo "
                                <h1><b>Item #: </b>{$array['name']}</h1>
                                <h2 class='$classStyleAlert'>Object Class: {$array['Class']}</h2>
                                <p class='text-center'><img src='{$array['Image']}' class='img-fluid rounded shadow' alt='image of {$array['Item']}'></p>
                                <div class='$classStyleBoarder'>
                                    <h3 class='$classHeader'>Description</h3>
                                    <p id='scp-description'>{$array['Description']}</p>
                                </div>
                                <p>
                                    <a href='{$update}' class='btn btn-info'>Update Record</a>
                                    <a href='{$delete}' class='btn btn-warning'>Delete Record</a>
                                </p>
                                ";
                            }
                            else
                            {
                                echo "<p>No record found for: {$array['Name']}</p>";
                            }
                        }
                        else
                        {
                           echo "<p>Error executing the statement,  {$stmt->error}</p>"; 
                        }

                    }
                    else
                    {
                       echo '
                       <br><br><br><br><br><br><br><br>
                    <img src="images/page logo.png" class="rounded mx-auto d-block" alt="SCP logo">

                    <!--This text is not original. This text is taken from a wallpaper i found. That wallpaper can be found in images/scp-text.jpg-->
                    <div class="text-center new-roman">
                        <h1>SPECIAL CONTAINMENT PROCEDURES FOUNDATION</h1>
                        <h5 class="text-dark-emphasis">SECURE, CONTAIN, PROTECT</h5>
                        <br>
                        <p class="text-light-emphasis">The Foundation is an international secret society, consisting of a scientific research institution with a paramilitary intelligence agency to support their goals. Despite its secretive premise, the Foundation is entrusted by governments around the world to capture and contain various unexplained phenomena that defy the known laws of nature (referred to as “anomalies,” “SCP objects,” “SCPs,” or colloquially “skips”). They include living beings and creatures, artifacts and objects, locations and places, abstract concepts, and incomprehensible entities which display supernatural abilities or other extremely unusual properties. If left uncontained, many of the more dangerous anomalies will pose a serious threat to humans or even all life on Earth. Their existence is hidden and withheld from the general public in order to prevent mass hysteria, and allow human civilization to continue functioning normally.</p>
                        <br><br>                   
                        <h2 class="text-dark-emphasis">We died in the darkness so you <br>can live in the light</h2>
                        <br><br>
                    </div>
                         ' ;
                    }

                    // Delete Record
                    if(isset($_GET['delete'])) {
                        // Sanitize and ensure the ID is an integer
                            $delID = intval($_GET['delete']);
                            echo "Trying to delete ID: $delID";
                    
                        // Prepare the delete statement
                        $delete = $connection->prepare("DELETE FROM SCP_Data WHERE ID = ?");
                        if (!$delete) {
                            echo "Prepare failed: " . $connection->error;
                            exit;
                        }
                    
                        // Bind the parameter as an integer
                        $delete->bind_param("i", $delID);
                    
                        // Execute and check
                        if ($delete->execute()) {
                            if ($delete->affected_rows > 0) {
                                echo "<div class='alert alert-warning'>Record Deleted</div>";
                            } else {
                                echo "<div class='alert alert-danger'>No record found with ID $delID.</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Error deleting record: {$delete->error}</div>";
                        }
                    }
                ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>

    