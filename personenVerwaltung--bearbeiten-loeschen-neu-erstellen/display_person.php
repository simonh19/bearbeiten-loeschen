<?php 
global $conn;
require_once 'conf.php';
require_once 'helper/form_functions.php';
$selectPersonenQuery = "select per_vname,per_nname,per_id from person";


echo generateTableFromQuery($conn, $selectPersonenQuery,'per_id','person');
?>