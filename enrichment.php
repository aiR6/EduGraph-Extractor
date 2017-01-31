<?php
    header('Content-type: text/plain; charset=utf-8');
    include_once 'functions.php';
    //Konfig aus Datei laden und als Array bereitstellen.
    $file = file_get_contents('./config', true);
    $conf =unserialize($file);

    //Datei von extrakt.php lesen.
    $file = file_get_contents('./'.$conf['saveFile'], true);
    //Array aufbauen.
    $extrakArray= unserialize($file);

    foreach ($extrakArray as $key =>$value){
        //Schlüssel aufbauen
        $schlüssel= str_replace("/", "",str_replace("http://", "", $extrakArray[$key]['uri']))."%2Fwirtschaftsinformatik";
        //Daten holen
        $piller= enrichService($schlüssel);

        //Ins Array hinzufügen wenn Werte vorhanden sind.
        if($piller != false){
            $extrakArray[$key]['BAM']=$piller["bwl"];
            $extrakArray[$key]['BIS']=$piller["wi"];
            $extrakArray[$key]['CSC']=$piller["inf"];
        }
    }
    //Ergebnis in Datei speichern
    saveExtract2File($extrakArray);

    echo "Anreicherung erfolgreich";
?>