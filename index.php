<?php
$file = file_get_contents('./config', true);
$conf =unserialize($file);


if(isset($_POST['save'])){
    unset($_POST['save']);
    foreach ($_POST as $key =>$value){
        $conf[$key]=$value;
    }
     saveConf2File();
}

function saveConf2File() {
    global $conf;

    $file="config";
    //Array speicherbar machen.
    $config= serialize($conf);

    //In vorhandene Datei oder neuerstellen und schreiben.
    if(file_exists($file)){
        // Lesen der Datei
        $handle = fopen ($file, 'r');
        $inhalt = fread ($handle, filesize ($file));
        fclose ($handle);

        $inhalt = $config;
    }else{
        $inhalt = $config;

        $handle = fopen ($file, 'w');
        fwrite ($handle, $inhalt);
        fclose ($handle);
    }
    // Schreiben des neuen Wertes
    $handle = fopen ($file, 'w');
    fwrite ($handle, $inhalt);
    fclose ($handle);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title>Extraktor</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/main.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <style>
             label{ display: block}
            input{width: 70%; margin: auto;}
            textarea{margin: auto;width: 70%; height: 150px;}
        </style>

    <script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/Chart.js"></script>
	<script src="assets/js/modernizr.custom.js"></script>



    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
      <script src="assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body data-spy="scroll" data-offset="0" data-target="#theMenu">

	<!-- ========== HEADER SECTION ========== -->
	<section id="home" name="home"></section>
	<div id="headerwrap">
		<div class="container">
			<br>
			<h1>Extraktor</h1>
			<h2>Ein Service der TH-Brandenburg</h2>
			<div class="row">
				<br>
				<br>
				<br>
				<div class="col-lg-6 col-lg-offset-3">
				</div>
			</div>
		</div><!-- /container -->
	</div><!-- /headerwrap -->


	<!-- ========== WHITE SECTION ========== -->
	<div id="w">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
				<h3>
                                    Willkommen auf <bold>der</bold> <bold>Administrationsoberfäche</bold> useres Extraktor<bold> Service</bold>.
				</h3>
				</div>
			</div>
		</div><!-- /container -->
	</div><!-- /w -->

	<!-- ========== SERVICES - GREY SECTION ========== -->
	<section id="services" name="services"></section>
	<div id="g">
		<div class="container">
			<div class="row">
				<h3>Server und Services</h3>

                                <form action="" method="post">
                                    <label for="sparqlStoreRead">SPARQL Store (Lesen)</label>
                                    <input type="text" name="sparqlStoreRead" value="<?php echo $conf["sparqlStoreRead"]?>"/>
                                    <label for="sparqStoreWrite">SPARQL Store (Schreiben)</label>
                                    <input type="text" name="sparqStoreWrite" value="<?php echo $conf["sparqStoreWrite"]?>"/>
                                    <label for="sparqStoreImport">SPARQL Store (Import)</label>
                                    <input type="text" name="sparqStoreImport" value="<?php echo $conf["sparqStoreImport"]?>"/>
                                    <label for="extraktorURL">RDF Extractor</label>
                                    <input type="text" name="extraktorURL" value="<?php echo $conf["extraktorURL"]?>"/>
                                    <label for="extraktorErrorString">RDF Extractor Fehlermeldung</label>
                                    <input type="text" name="extraktorErrorString" value="<?php echo $conf["extraktorErrorString"]?>"/>

                                    <label for="enrichService">Analye Tool</label>
                                    <input type="text" name="enrichService" value="<?php echo $conf["enrichService"]?>"/>

                                    <label for="saveFile">Name der Sicherungsdatei</label>
                                    <input type="text" name="saveFile" value="<?php echo $conf["saveFile"]?>"/>
                                    <br/><br/>
                                    <input type="submit" name="save" value="Speichern"/>
                                </form>
			</div>
		</div><!-- /container -->
	</div><!-- /g -->

	<!-- ========== CHARTS - DARK GREY SECTION ========== -->
	<div id="dg">
		<div class="container">
                    <h3>SPARQL Queries</h3>
                    <form action="" method="post">
                        <label for="getURLs">URLs für den Extractor holen</label>
                        <textarea name="getURLs"><?php echo $conf["getURLs"]?></textarea>
                        <label for="saveTrippel">Alle vorhanden Daten aus Store löschen</label>
                        <textarea name="saveTrippel"><?php echo $conf["saveTrippel"]?></textarea>
                        <label for="saveTrippel">Hashwert und QuellURL speichern</label>
                        <textarea name="insertHash+srcURL"><?php echo $conf["insertHash+srcURL"]?></textarea>
                        <label for="insertEnrichment">Anreicherung</label>
                        <textarea name="insertEnrichment"><?php echo $conf["insertEnrichment"]?></textarea>
                        <br/><br/>
                        <input type="submit" name="save" value="Speichern"/>
                    </form>
		</div><!-- /container -->
	</div><!-- /dg -->

	<!-- ========== WHITE SECTION ========== -->
	<div id="w">
		<div class="container">
                    <h3>E-Mail Fehlermeldungen</h3>
                    <form action="" method="post">
                        <label for="mailTO">Empfänger</label>
                        <input type="text" name="mailTO" value="<?php echo $conf["mailTO"]?>"/>

                        <label for="extractFailed">Extraktionsservice fehlgeschlagen</label>
                        <textarea name="extractFailed"><?php echo $conf["extractFailed"]?></textarea>
                        <label for="analyseFailed">Analyseservice fehlgeschlagen</label>
                        <textarea name="analyseFailed"><?php echo $conf["analyseFailed"]?></textarea>
                        <label for="enrichFailed">Anreicherungsservice fehlgeschlagen</label>
                        <textarea name="enrichFailed"><?php echo $conf["enrichFailed"]?></textarea>
                        <label for="enrichFailed">Speicherservice fehlgeschlagen</label>
                        <textarea name="storeFailed"><?php echo $conf["storeFailed"]?></textarea>
                        <br/><br/>
                        <input type="submit" name="save" value="Speichern"/>
                    </form>


		</div><!-- /container -->
	</div><!-- /w -->

	<!-- ========== FOOTER SECTION ========== -->
	<section id="contact" name="contact"></section>
	<div id="f">
		<div class="container">
			<div class="row">
					<h3><b>Kontakt</b></h3>
					<br>
					<div class="col-lg-4">
						<h3><b>E-Mail:</b></h3>
						<h3>vera.meister@th-brandenburg.de</h3>
						<br>
					</div>
                                        <div class="col-lg-4">
                                                <h3><b>Adresse:</b></h3>
                                                <h3>Magdeburger Straße 50</h3>
                                                <h3>14770 Brandenburg an der Havel </h3>
                                        </div>
					<div class="col-lg-4">
						<h3><b>Telefon:</b></h3>
						<h3>+49 3381 355 - 297</h3>
						<br>
					</div>
			</div>
		</div><!-- /container -->
	</div><!-- /f -->




    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="assets/js/classie.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/smoothscroll.js"></script>
	<script src="assets/js/main.js"></script>
</body>
</html>
