<?php

    echo "<h1>File Uploader</h1>";
    echo "<h2>Select a file to upload</h2>";
    echo "<h3>Valid files to include jpg and gif</h3>";

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

//config-student.php

define("DB_DSN", "mysql:dbname=marcosri_grc");
define("DB_USERNAME", "marcosri_grcuser");
define("DB_PASSWORD", '2GMryy7d!gwy');

try {
    //Create a new PDO connection
    $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    //echo "Connected!";
} catch (PDOException $e) {
    echo $e->getMessage();
}

    $dirName = 'uploads';

?>

<form action="#" method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
</form>

<?php
    //var_dump($_FILES);

    if(isset($_FILES['fileToUpload'])) {
        $file = $_FILES['fileToUpload'];

        $validTypes = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
        /*echo $file['name'].'<br>';
        echo $file['type'].'<br>';
        echo $file['tmp_name'].'<br>';
        echo $file['error'].'<br>';
        echo $file['size'].'<br>';
        */

        if($_SERVER['CONTENT_LENGTH'] > 3000000) {
            echo "<p class='error'>File is too large. Maximum file size is 3MB.</p>";
        } else if(in_array($file['type'], $validTypes)) {
            if ($file['error'] > 0) {
                echo "<p class='error'>Return Code: {$file['error']}</p>";
            }
            if (file_exists($dirName . $file['name'])) {
                echo "<p class='error'>Error uploading: ";
                echo $file['name']."already exists.</p>";
            }

            else {
                move_uploaded_file($file['tmp_name'], $dirName . $file['name']);
                echo "<p class='success'>Uploaded {$file['name']} successfully!</p>";

                $sql = "INSERT INTO uploads(filename) VALUES ('{$file['name']}')";
                $dbh->exec($sql);
            }
        }
        else {
            echo "<p class='error'>Invalid file type. Allowed types: gif, jpg, png</p>";
        }
    }

    $dir = opendir($dirName);

    $sql = "SELECT * FROM `uploads`";
    $result = $dbh->query($sql);

    if(sizeof($result)>= 1) {
        foreach ($result as $row) {
            $img = $row['filename'];
            echo "<img src='$dirName$img' alt='uploadedImage'>";
        }
    }
?>