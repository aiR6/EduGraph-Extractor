<?php
    header('Content-type: text/plain; charset=utf-8');
    include_once 'functions.php';
    include_once 'mail.php';
    //Konfig aus Datei laden und als Array bereitstellen.
    $file = file_get_contents('./config', true);
    $conf =unserialize($file);

    //Datei von extrakt.php lesen.
    $file = file_get_contents('./'.$conf['saveFile'], true);
    //Array aufbauen.
    $extrakArray= unserialize($file);
    $update=false;
    //Standard Fehlermeldung des RDF Extraktors
    $stdFehlerMeldung=$conf['extraktorErrorString'];
    $errorMsg;

    foreach ($extrakArray as $key =>$value){
        //Prüfen auf veränderungen
        if($value['oldHash']!=$value['newHash']){
            //Fehlermeldung schen
            if(strpos($value['extrakt'], $stdFehlerMeldung) !== false){
                $extrakArray[$key]['error']=$value['extrakt'];
                //Extrak löschen um fehler beim speicher zu verhindern
                $extrakArray[$key]['extrakt']="";
                $extrakArray[$key]['update']=false;
                $errorMsg.=$extrakArray[$key]['error']."\n";

            }else{
                //Bei Veränderung ohne Fehler
                $extrakArray[$key]['update']=true;
                $update=true;
            }
            //Aus Array löschen wenn keine Änderung vorhanden ist.
        }else unset ($extrakArray[$key]);
    }

    //Ergebnis in Datei speichern
    saveExtract2File($extrakArray);
    //Fehlermeldungen senden
    if($errorMsg != ""){
        sendMail($errorMsg);
    }

    if ($update){
        echo "true";
    }else echo "false";
?>