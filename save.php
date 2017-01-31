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
        $uri= $extrakArray[$key]['uri'];
        $extrakt=$extrakArray[$key]['extrakt'];
        $extraktURL=$extrakArray[$key]['extraktURL'];
        $hash= $extrakArray[$key]['newHash'];
        $BAM=$extrakArray[$key]['BAM'];
        $BIS=$extrakArray[$key]['BIS'];
        $CSC=$extrakArray[$key]['CSC'];

        //Neues Extrakt speichern
        saveTriple($extrakt, $uri, $extraktURL, $hash);
        //Nur speichern wenn Werte vorhanden sind.
        if($BAM!="" && $BIS!=""&& $CSC!=""){
            saveEnrichment($uri, $BAM, $BIS, $CSC);
        }
    }
    echo "Trippel gespeichert";
?>