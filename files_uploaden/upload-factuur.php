<?php
/*
 * code snippet van w3school
 * werkt nog niet naar behoren. moet nog aangepast worden zodat het gebruikt kan worden.
 */
global $config;

if (isset($_POST["saveChanges"])) {

    if ($_FILES["factuurToUpload"]["size"] > 0) {
        $temp_target = $config['uploadURL'] . $opdrachtNaam . "/";
        if (is_dir($temp_target)) {
            $target_dir = $temp_target;
        } else {
            mkdir($temp_target, 0777, true);
            $target_dir = $temp_target;
        }
        $file_naam = basename($_FILES["factuurToUpload"]["name"]);
        $target_file = $target_dir . $file_naam;
        $uploadOk = 1;
        $fileType = pathinfo($target_file, PATHINFO_EXTENSION);


// Check file size
        if ($_FILES["factuurToUpload"]["size"] > 1000000) {
            echo "Sorry, Je file is te groot.";
            $uploadOk = 0;
        }
// Allow certain file formats
        if ($fileType != "jpg" && $fileType != "jpeg"
            && $fileType != "png" && $fileType != "tif" && $fileType != "tiff"
            && $fileType != "txt" && $fileType != "doc" && $fileType != "pps"
            && $fileType != "pdf" && $fileType != "zip" && $fileType != "rar"
            && $fileType != "odt" && $fileType != "ods"
        ) {
            echo "Sorry, je probeert een file te uploaden die niet is toegestaan.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, Je file is niet geupload.";
// if everything is ok, try to upload file
        } else {
            $sql = "SELECT factuur_file_url FROM opdrachten WHERE opdracht_id = $opdrachtId";
            $result = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $oldTargetFile = $row['factuur_file_url'];
            if (file_exists($oldTargetFile)) {
                unlink($oldTargetFile);
            }
            if (move_uploaded_file($_FILES["factuurToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["factuurToUpload"]["name"]) . " has been uploaded.";
                $sql = "UPDATE opdrachten 
                    SET factuur_file_url = '$target_file', 
                    factuur_file_naam = '$file_naam'
                    WHERE opdracht_id = $opdrachtId";
                mysqli_query($db, $sql);
            } else {
                echo "Sorry, er was een probleem met je upload.";
            }
        }
    }else{
    }
}
?>