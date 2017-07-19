<?php
include_once("includes/functions.php");
sendVerifyMail($db, $_GET['id']);
?>
<label> Er is een verificatie link gestuurd naar uw e-mail.<br/> klik op de link om uw account te verifiëren.</label>
