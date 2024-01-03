<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cystema";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Handle file upload
        $targetDirectory = "Uploads/"; // Create a directory to store uploaded images
        $targetFile = $targetDirectory . basename($_FILES["file"]["name"]);

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            $message = $_POST["message"]; 

            // Insert data into the database
            $sql = "INSERT INTO freedom_wall (design, message) VALUES (:design, :message)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':design', $targetFile);
            $stmt->bindParam(':message', $message);
            $stmt->execute();

            // Redirect to a success page or display a success message
            header("Location: freedomwall.php");
            exit();
        } else {
            // Handle upload errors
            echo "File upload failed.";
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
} finally {
    $conn = null;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYSTEMA</title>
    <link rel="stylesheet" type="text/css" href="freedomwall.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


 

</head>
<body>
    <div class="freedomwall">
        <h2>Freedom Wall</h2>
        <div class="wall">
            <?php
            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "cystema";

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Fetch text from the database
                $textQuery = "SELECT message FROM freedom_wall";
                $textResult = $conn->query($textQuery);


                if ($textResult) {
                    if ($textResult->rowCount() > 0) {
                        while ($row = $textResult->fetch(PDO::FETCH_ASSOC)) {
                            // Generate random positions for each sticky note
                            $randomLeft = rand(0, 800); // Adjust based on your box width
                            $randomTop = rand(0, 500); // Adjust based on your box height

                            echo '<div class="sticky-note" style="left:' . $randomLeft . 'px; top:' . $randomTop . 'px;">' . $row['message'] . '</div>';
                        }
                    } else {
                        echo "No messages found.";
                    }
                } else {
                    echo "Query failed: " . $conn->errorInfo()[2];
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            } finally {
                $conn = null;
            }
            ?>
        </div>
    </div>

    <br>
            <div class="line"></div>
    <br>

    <div class="content">
        <form id="uploadForm" action="freedomwall.php" method="post" enctype="multipart/form-data">
            <h3> Want to share designs? </h3>
            <div class="file-input-container">
            <label for="file" class="file-button">Choose File</label>
            <input type="file" name="file" id="file" class="file-input" accept="image/*" required>
        </div>
            <h3> Want to share some message for us? </h3>
            <textarea name="message" rows="4" cols="50" placeholder="Add your message..." required></textarea>
            <br>
            <button type="submit">Upload</button>
        </form>
    </div>

    <footer>
        <p>Follow Us</p>
        <ul class="links">
            <li><a href="https://www.tiktok.com/@cystemaph?_t=8gZhWSjUwTp&_r=1"><i class="fa-brands fa-tiktok"></i></a></li>
            <li><a href="https://instagram.com/cystemaph?igshid=NTc4MTIwNjQ2YQ=="><i class="fa-brands fa-instagram"></i></a></li>
            <li><a href="https://www.facebook.com/cystemaph?mibextid=ZbWKwL"><i class="fa-brands fa-facebook"></i></a></li>
        </ul>
    </footer>
  
    <script>
$(document).ready(function() {
    $(".sticky-note").draggable({ containment: "parent" });
});
</script>
</body>
</html>
