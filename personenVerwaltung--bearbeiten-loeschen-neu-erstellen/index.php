<?php
if (session_id() == '') {
    session_start();   
}
include 'helper/form_functions.php';
require_once 'conf.php';
include_once 'helper/delete.php';
global $conn;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="node_modules/@popperjs/core/dist/umd/popper.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Zimmerverwaltung</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-item nav-link" href="index.php">Willkommensseite</a>
            <a class="nav-item nav-link" href="index.php?site=manage_address">Zimmer je Etage</a>
        </div>
    </div>
</nav>
<div class='container d-flex align-items-center flex-column mt-4 gap-4'>
    <h3>Willkommen</h3>
    <?php

        $selectOrteQuery = "select o.ort_id, p.plz_nr as plz, o.ort_name as ort, s.str_name as strasse from ort_plz op
        join ort o on op.ort_id = o.ort_id
        join plz p on op.plz_id = p.plz_id
        left join strasse_ort_plz sop on op.orpl_id = sop.orpl_id
        left join strasse s on sop.str_id = s.str_id;";

        if (isset($_GET["site"])) {
            $fullUrl = $_GET["site"];
            if (str_contains($fullUrl, "?")) {
                $separator = "?";
                $parts = explode($separator, $fullUrl);
                $_GET['urlParam'] = $parts;
                $site = $parts[0];
                include_once($site . ".php");
            } else {
                include_once($fullUrl . ".php");
            }
        } else {
            echo generateTableFromQuery($conn, $selectOrteQuery,"ort_id","ort");
        }
    ?>
</div>
</body>
</html>