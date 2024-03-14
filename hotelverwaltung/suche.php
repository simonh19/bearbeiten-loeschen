<?php

require_once 'conf.php';
include_once 'helper/form_functions.php'

//Dadurch bekommme ich die Zimmer der jeweiligen Etage
function processForm($data) {
    global $conn;
    $query = "select zim.zim_etage as Etage,rt.raty_name as Raumtyp,zim.zim_nr as Zimmernummer,zim.zim_id
    from zimmer zim
    join raumtyp rt on zim.raty_id = rt.raty_id
    where zim.zim_etage = ?";
    
    $suchbegriff = $data['selectedEtage'] ?? '';
    //$startDatumVorhanden = isset($data['date-start']) && !empty($data['date-start']);
    
    $stmt = executeQuery($conn,$query);
    return $stmt;
}