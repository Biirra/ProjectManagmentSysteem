<?php
if (!isset($_SESSION)) {
    session_start();
}

if (empty($_GET)) {
    $_GET['state'] = "";
}

error_reporting (0); // zet alle warnings uit die anders in de applicatie te zien zijn. uit commenten wanneer je verder gaat met programeren.

include_once("includes/database.php"); // include de database connectie. $db om met de database te communiceren.
include_once("includes/functions.php");
include_once("login/session.php");
include_once("config/config.php");
?>

    <html>
    <head>
        <link rel="stylesheet" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" href="css/stylesheet.css">


        <script src="jquery/jquery-3.1.1.min.js"></script>
        <script src="jquery/jquery-ui.min.js"></script>
        <script src="lijsten/js/lijst_sortable.js"></script>
        <script src="js/javascript.js"></script>

        <script>
            $(function(){

                var testdatepicker=document.createElement("input")
                testdatepicker.setAttribute("type", "date") //set INPUT element to the type we're testing for
                if (testdatepicker.type=="text"){ //if browser doesn't support INPUT type="calender"
                    $('input[type=date]').datepicker({
                            // Consistent format with the HTML5 picker
                            dateFormat : 'yy-mm-dd'
                        },
                        // Localization
                        $.datepicker.regional['it']
                    );
                }else{

                }
            });
        </script>
    </head>
    <body>
<?php
include("header/header.php"); //header kopje. zit een logo in en de input velden om in te loggen.
include("navbar/navbar.php");

?>
