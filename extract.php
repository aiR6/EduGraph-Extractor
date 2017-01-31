<?php
    header('Content-type: text/plain; charset=utf-8');
    include 'functions.php';

    $urls=getURLs();
    $extrakArray;

    //Alle URLs durchgehen.
    foreach ($urls as $key){
        $extrakt=extractRdfFromUrl($key->ExtraktorUrl->value);
        $extrakArray[]=array(   "uri" => $key->hochschule->value,
                                "oldHash" =>$key->hash->value,
                                "newHash" => md5($extrakt),
                                "extrakt" => $extrakt->body,
                                "extraktURL" => $key->ExtraktorUrl->value);
    }
    //Ergebnis in Datei speichern
    saveExtract2File($extrakArray);

    echo"Extraktion erfolgreich";
?>