<?php
$user_check="";
if(!empty($_SESSION['login_user'])){
    $user_check = $_SESSION['login_user'];
}else{
    $_SESSION['login_user'] = "";
}
// vraagt informatie op over de gebruiker.
$ses_sql = mysqli_query($db,"select users.id, email, bevoegdheid, naam from users, user_info where users.email = '$user_check' AND users.id = user_info.user_id");

$row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);

$login_id = $row['id'];
$login_session = $row['email'];             // bevat de email die gevraagd wordt van de database.
$functie_bevoegdheid = $row['bevoegdheid']; // geeft aan of de persoon bevoegd is om hier te komen.
$userNaam = $row['naam'];







