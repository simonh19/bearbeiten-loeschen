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
    $vorname = getValue($conn,$tableName,'zim_nr','zim_id',$zim_id);
    $nachname = getValue($conn,$tableName,'zim_etage','zim_id',$zim_id);
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

    //Hier werden die Daten von dem Formular in die Datenbank gespeichert.
    //Falls den Eintrag noch nicht gibt, dann gehe in die IF
    //Hier wird überprüft OB es einen Wert schon in der Datenbank gibt.
   //Hier wird der Wert erstellt
    if (!recordExists2($conn, 'zimmer', 'zim_nr','zim_etage', $zimmernummer, $zimmeretage) && !str_contains($site,"?")) {
        //addRecord speichert die Daten von neuesOrt,neuesPlz dann in die Datenbank.'ort' ist der Name der Tabelle.
        $newZimmerData = $zimmernummerValueDb + $zimmeretageValueDb;
        $newZimmerId = addRecord($conn, 'zimmer', $newZimmerData );//Hier bekommt man die Id von den eingefügten Daten zurück.
        //Durch getValue bekommt man einen einzigen Wert von einer Datenbank. 'plz' ist der Tabellenname. plz_id ist das was ich suche. plz_nr ist die Spalte für die where-
        //bedingung. $plz ist der Wert für die where-bedingung.
        //select pLz_id from plz where plz_nr = 4020

        showAlertSuccess("Zimmer wurde hinzugefügt.");
        //Daten von Formular wurde in die Datenbank gespeichert.
        $stateChanged = true;
    }
    //Hier wird der Wert upgedatet
    else
    {
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