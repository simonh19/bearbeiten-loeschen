<?php
include_once 'helper/database_functions.php';
require_once 'conf.php';

global $conn;
$getAvailableRooms = "select zim.zim_nr as Zimmernummer
from zimmer zim
join raumtyp rt on zim.raty_id = rt.raty_id
left join buchung buch on zim.zim_id = buch.zim_id
where buc_id is null";
$tableName="zimmer";
$zimmernummer ="zimmernummer";
$zimmeretage="zimmeretage";
$zimmerListe = getValuesByQuery($conn,$getAvailableRooms);

$stateChanged = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $selectedEtageValue = getPostParameter("zimmernummer","");
    $zimmernummer = $zimmerListe[$selectedEtageValue];
    $queryZimmerId = "Select zim_id from zimmer where zim_nr = ?";
    $stmt = $conn->prepare($queryZimmerId);
    $stmt->execute([$zimmernummer]);
    $zimmerId = $stmt->fetch(PDO::FETCH_COLUMN);
    $datumVon = $_POST['datum-von'];
    $datumBis = $_POST['datum-bis'];
    $query = "Insert into buchung (zim_id,buc_von,buc_bis) 
    values (?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->execute([$zimmerId,$datumVon,$datumBis]);
    showAlertSuccess("Die Buchung von $datumVon bis $datumBis mit der Zimmernummer $zimmernummer wurde eingetragen.");
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
    <h2>Zimmerbuchung</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="ort">VON</label>
            <input type="date" class="form-control" id="datum-von" name="datum-von" required>
        </div>
        <div class="form-group">
            <label for="ort">BIS</label>
            <input type="date" class="form-control" id="datum-bis" name="datum-bis" required>
        </div>
        <div class="form-group">
            <label for="etage">verfügbare Zimmern</label>
            <?php echo createDropdown('zimmernummer', $zimmerListe); ?>
        </div>
        <button type="submit" class="btn btn-primary">Speichern</button>
    </form>
    <div>
        <?php if ($stateChanged) { echo generateTableFromQuery($conn,$selectPersonenQuery,'per_id',"person"); } ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>