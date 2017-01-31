<?php
//Dies ist eine PHP lib die als REST Client genutzt werden kann.
//Mehr Informationen unter:http://phphttpclient.com/
include_once 'httpful.phar';
//Konfig aus Datei laden und als Array bereitstellen.
$file = file_get_contents('./config', true);
$conf =unserialize($file);

/*
 * Diese Funktion lädt alle URLs von Hochschulen die im Datastore gespeichert
 * sind. Das Suchmuster ist ?hochschule <http://akwi.de/ns/bise#:srcURL> ?ExtraktorUrl.
 *
 * Die Funktion ist in extract.php genutzt,
 *
 * @return Objekt(ExtraktorUrl, hochschule und hash)
 */
function getURLs (){
    //Globales Konfigurationsarray
    global $conf;

    //Sparqlabfrage aus Konfig laden.
    $sparql=$conf['getURLs'];
    //Datastore aus Konfig laden.
    $server=$conf['sparqlStoreRead'];
    $output="&output=json";
    //Abfrage zusammenstellen.
    $uri=$server.urlencode($sparql).$output;
    //Abfrage senden
    $response = \Httpful\Request::get($uri)->send();

    return $response->body->results->bindings;;
}
/*
 * Die Funktion nutzt einen Webservice der Tripel aus einer JSON Datei
 * extrahieren kann.
 *
 * In diesem Beispiel http://rdf-translator.appspot.com.
 *
 * @return Objekt
 */
function extractRdfFromUrl($url){
    //Globales Konfigurationsarray
    global $conf;
     //Service aus Konfig laden.
    $service = $conf['extraktorURL'];
    //Abfrage zusammenstellen.
    $uri = "$service/".urlencode($url);
    //Abfrage senden
    $response = \Httpful\Request::get($uri)->send();

    return $response;
}
/*
 * Diese Funktion speichert die Ergebnisse der Services extrakt.php, analyse.php
 * und enrichment.php in eine Datei.
 * Hierzu wird das Array serialisiert um es in eine Datei zu schreiben.
 */
function saveExtract2File($array) {
    //Globales Konfigurationsarray
    global $conf;
    $file=$conf['saveFile'];
    //Array speicherbar machen.
    $array= serialize($array);

    //In vorhandene Datei oder neuerstellen und schreiben.
    if(file_exists($file)){
        // Lesen der Datei
        $handle = fopen ($file, 'r');
        $inhalt = fread ($handle, filesize ($file));
        fclose ($handle);

        $inhalt = $array;
    }else{
        $inhalt = $array;

        $handle = fopen ($file, 'w');
        fwrite ($handle, $inhalt);
        fclose ($handle);
    }
    // Schreiben des neuen Wertes
    $handle = fopen ($file, 'w');
    fwrite ($handle, $inhalt);
    fclose ($handle);
}
/*
 * Die Funktion schreibt das Extrakt in den Datastore (DS). Als erstes werden die
 * alten Tripel aus den Datastore gelöscht. Im Anschluss wird das neue Extrakt
 * in den DS importiert.
 * Zuletzt werden die Metadaten Hash und srcURL hinzugefügt.
 */
function saveTriple ($extrakt, $uri, $extraktURL, $hash){
    //Globales Konfigurationsarray
    global $conf;

    $search=array('!uri!','!hash!','!extraktURL!');
    $replace=array($uri,$hash,$extraktURL);
    //Begriffe die im $search sind durch Variablen im $replace Array ersetzen.
    $sparql=str_replace($search,$replace,$conf['saveTrippel']);
    //Abfrage senden
    \Httpful\Request::post($conf['sparqStoreWrite'])->body($sparql)->sendsType("application/x-www-form-urlencoded")->send();
    //Extraktion senden
    \Httpful\Request::post($conf['sparqStoreImport'])
            ->addHeader('Content-Type', 'text/turtle')
            ->body($extrakt)
            ->send();
    //Begriffe die im $search sind durch Variablen im $replace Array ersetzen.
    $sparql=str_replace($search,$replace,$conf['insertHash+srcURL']);
    //Abfrage senden
    \Httpful\Request::post($conf['sparqStoreWrite'])->body($sparql)->sendsType("application/x-www-form-urlencoded")->send();
}
/*
 * Diese Funktion schreibt die drei Werte des Webservices in den DS.
 * Als erstes werden die vorhanden Werte aus dem DS gelöscht und im Anschluss die neuen
 * Werte in den DS geschrieben.
 */
function saveEnrichment($uri, $BAM, $BIS, $CSC){
    //Globales Konfigurationsarray
    global $conf;

    $search=array('!uri!','!BAM!','!BIS!','!CSC!');
    $replace=array($uri,$BAM,$BIS,$CSC);
    //Begriffe die im $search sind durch Variablen im $replace Array ersetzen.
    $sparql=str_replace($search,$replace,$conf['insertEnrichment']);
    //Abfrage senden
    \Httpful\Request::post($conf['sparqStoreWrite'])->body($sparql)->sendsType("application/x-www-form-urlencoded")->send();
}
/*
 * Die Funktion ruft die Werte aus den Anreicherungsservice ab. Diese wird in
 * enrichment.php genutzt.
 *
 * @return Array (wi, inf und bwl)
 */
function enrichService($schlüssel){
    //Globales Konfigurationsarray
    global $conf;
    //Server aus Konfig laden.
    $server=$conf['enrichService'];

    //Falls HS nicht im Service gelistet ist.
    try{
       $response = \Httpful\Request::get($server.$schlüssel)->send();
    }catch (Exception $e){

    }
    //Prüfen ob ein Ergebnis vorhanden ist und Werte enthält
    if(isset ($response) && $response != "Katalog not Found"){
        return json_decode($response, true);
    }
    else return false;
}
?>