<?php
include_once('../../includes/database.php');
global $config;

$filepath = $_POST['deleteFile'];
$history = $_POST['lastHistory'];

unlink("../../" . $filepath);
$sql = "DELETE FROM opdracht_document WHERE file_path = '$filepath'";
$result = mysqli_query($db, $sql);
header("location: ".$history);

?>