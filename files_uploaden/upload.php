<?php
/*
 * code snippet van w3school
 * werkt nog niet naar behoren. moet nog aangepast worden zodat het gebruikt kan worden.
 */
global $config;

if (isset($_POST["submit"])) {
    $temp_target = $config['uploadURL'].$opdrachtNaam."/";
    if (is_dir($temp_target)) {
        $target_dir = $temp_target;
    } else {
        mkdir($temp_target,0777,true);
        $target_dir = $temp_target;
    }
    $file_naam = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $file_naam;
    $uploadOk = 1;
    $fileType = pathinfo($target_file, PATHINFO_EXTENSION);



// Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, Deze file bestaat al.";
        $uploadOk = 0;
    }
// Check file size
    if ($_FILES["fileToUpload"]["size"] > 1000000) {
        echo "Sorry, Je file is te groot.";
        $uploadOk = 0;
    }
// Allow certain file formats
    if ($fileType != "jpg" && $fileType != "jpeg" && $fileType != "gif"
        && $fileType != "png" && $fileType != "tif" && $fileType != "tiff"
        && $fileType != "txt" && $fileType != "doc" && $fileType != "pps"
        && $fileType != "pdf" && $fileType != "mp3" && $fileType != "wav"
        && $fileType != "avi" && $fileType != "mpg" && $fileType != "mpeg"
        && $fileType != "wmv" && $fileType != "iso" && $fileType != "flv"
        && $fileType != "mov" && $fileType != "dvr-ms" && $fileType != "vob"
        && $fileType != "zip" && $fileType != "rar" && $fileType != "odt"
        && $fileType != "ods"
    ) {
        echo "Sorry, je probeert een file te uploaden die niet is toegestaan.";
        $uploadOk = 0;
    }
// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, Je file is niet geupload.";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            $sql = "INSERT INTO opdracht_document (opdracht_id, file_naam, file_path, user_id) 
                                VALUES ('$opdrachtId', '$file_naam', '$target_file', $login_id)";
            mysqli_query($db, $sql);
        } else {
            echo "Sorry, er was een probleem met je upload.";
        }
    }
}
?>