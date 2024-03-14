<?php
include_once 'helper/database_functions.php';
require_once 'conf.php';

global $conn;
$tableName="zimmer";
$zimmernummer ="zimmernummer";
$zimmeretage="zimmeretage";

$stateChanged = false;

$query = "select zim_nr,zim_etage
from zimmer";

$site = $_GET['site'];
$parts = explode("?", $site);

if(!empty($parts))
{
    $paramValue = getUrlParam($parts[1]);
    $zim_id=$paramValue;
    $zimmernummer = getValue($conn,$tableName,'zim_nr','zim_id',$zim_id);
    $zimmeretage = getValue($conn,$tableName,'zim_etage','zim_id',$zim_id);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // Sammle und verarbeite hier die Formulardaten
    //Hier bekomme ich den Wert von dem Input-Attribut
    $zimmernummer = getPostParameter("zimmernummer","");
    //Hier bekomme ich den Wert von dem Plz-Attribut
    $zimmeretage = getPostParameter("zimmeretage","");

    //Der name der Spalte als Key-Attribut, der Value von name als Value-Atribut. Das ist ein Zwischenschritt, damit man die Daten in die Datenbank speichern kann.
    $zimmernummerValueDb = ['zim_nr' => $zimmernummer];
    $zimmeretageValueDb = ['zim_etage' => $zimmeretage];

    
            //Vorbereitung zur Speicherung HIER JEWEILIGE ID EINGEBEN
            $zimmerSuchBedienung = ['zim_id = ' . $zim_id];
            //PLZ bleibt gleich, Ort verändert sich HIER AKTUELLE DATEN EINGEBEN
            $zimmerData= $zimmernummerValueDb + $zimmeretageValueDb;
            updateRecord($conn, $tableName, $zimmerData, $zimmerSuchBedienung);
            //PLZ bleibt eigentlich gleich, wird hier aber nocheinmal zugewiesen.
            showAlertSuccess("Zimmer ist bereits vorhanden. $tableName wurde aktualisiert.");
            $stateChanged = true;

            //UPDATE RECORD DUPLIZIEREN WENN MAN MEHRERE TABELLEN BRAUCHT und bei tablename die entsprechende Tabelle reinschreiben

}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Zimmer bearbeiten</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Zimmer hinzufügen/bearbeiten</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="ort">Zimmeretage</label>
            <input type="number" class="form-control" id="zimmeretage" name="zimmeretage" placeholder="zimmeretage" value="<?php echo htmlspecialchars($zimmeretage); ?>" required>

        </div>
        <div class="form-group">
            <label for="ort">Zimmernummer</label>
            <input value="<?php echo htmlspecialchars($zimmernummer); ?>" type="number" class="form-control" id="zimmernummer" name="zimmernummer" placeholder="Zimmernummer" required>
        </div>
        <button type="submit" class="btn btn-primary">Speichern</button>
    </form>
    <div>
        <?php if ($stateChanged) { echo generateTableFromQuery($conn,$selectPersonenQuery,'per_id',"person"); } ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>