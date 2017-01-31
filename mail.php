<?php
$file = file_get_contents('./config', true);
$conf =unserialize($file);

$msg;

if (isset($_GET['error'])){
    $case= $_GET['error'];

    switch ($case){
        case "extractFailed":
            $msg=$conf['extractFailed'];
            sendMail($msg);
            break;
        case "analyseFailed":
            $msg=$conf['analyseFailed'];
            sendMail($msg);
            break;
        case "enrichFailed":
            $msg=$conf['enrichFailed'];
            sendMail($msg);
            break;
        case "storeFailed":
            $msg=$conf['storeFailed'];
            sendMail($msg);
            break;
        DEFAULT:
            $msg="Irgendwas antwortet nicht";
            sendMail($msg);
    }
}


function sendMail($msg){
    global $conf;
    $empfaenger = $conf["mailTO"]; 
    $betreff = "Fehler im Extraktionsprozess";
    $from = "From: Extraktionsprozess <noreply@th-brandenburg.de>";
    $text = $msg;

    mail($empfaenger, $betreff, $text, $from);

    $msg=urlencode($msg);
    $empfaenger=urlencode($empfaenger);
    fopen("http://extraktor.next-lvl-service.de/mailNLS.php?msg=$msg&empf=$empfaenger", 'r');
}
?>
