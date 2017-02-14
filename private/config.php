<?php
defined("DB_SERVER") ? null : define("DB_SERVER", "127.0.0.1");
defined("DB_USER") ? null : define("DB_USER", "natterbox");
defined("DB_PASS") ? null : define("DB_PASS", "q1w2e3r4");
defined("DB_DB") ? null : define("DB_DB", "nca");

// defined("FP_SERVER") ? null : define("FP_SERVER", "79.2.100.143"); // ADSL Tim
defined("FP_SERVER") ? null : define("FP_SERVER", "93.42.121.142"); // FTTH Fastweb
defined("FP_USER") ? null : define("FP_USER", "Auto1user");
defined("FP_PASS") ? null : define("FP_PASS", "vyHuY6D6");
defined("FP_DB") ? null : define("FP_DB", "migrazione3");

defined("NAS_SERVER") ? null : define("NAS_SERVER", "10.4.4.250");
defined("NAS_USER") ? null : define("NAS_USER", "portale");
defined("NAS_PASS") ? null : define("NAS_PASS", "76837683");
defined("NAS_SHARE") ? null : define("NAS_SHARE", "DEPT_OPS");

$upload_errors = array(
	// http://www.php.net/manual/en/features.file-upload.errors.php
	UPLOAD_ERR_OK 				=> "This should NOT display as only errors should be treated",
	UPLOAD_ERR_INI_SIZE  	=> "File troppo pesante (upload_max_filesize).",
  UPLOAD_ERR_FORM_SIZE 	=> "File troppo pesante (MAX_FILE_SIZE impostato del form).",
  UPLOAD_ERR_PARTIAL 		=> "Invio parziale.",
  UPLOAD_ERR_NO_FILE 		=> "Selezionare un file prima di inviare.",
  UPLOAD_ERR_NO_TMP_DIR => "Temp directory mancante.",
  UPLOAD_ERR_CANT_WRITE => "Impossibile scrivere il FS.",
  UPLOAD_ERR_EXTENSION 	=> "Errore di estensione."
);
?>
