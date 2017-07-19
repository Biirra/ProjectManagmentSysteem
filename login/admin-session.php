<?php
include('session.php');

if(empty($_SESSION['login_user'])){
    header("location:../".$config['baseURL']);
}elseif($functie_bevoegdheid != 4){
    header("location:../".$config['baseURL']);
}
?>