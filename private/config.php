<?php
defined("DB_SERVER") ? null : define("DB_SERVER", "127.0.0.1");
defined("DB_USER") ? null : define("DB_USER", "natterbox");
defined("DB_PASS") ? null : define("DB_PASS", "q1w2e3r4");
defined("DB_DB") ? null : define("DB_DB", "nca");

defined("FP_SERVER") ? null : define("FP_SERVER", "79.2.100.143");
defined("FP_USER") ? null : define("FP_USER", "Auto1user");
defined("FP_PASS") ? null : define("FP_PASS", "vyHuY6D6");
defined("FP_DB") ? null : define("FP_DB", `migrazione3`);

$upload_errors = array(
	// http://www.php.net/manual/en/features.file-upload.errors.php
	UPLOAD_ERR_OK 				=> "No errors.",
	UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
  UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
  UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
  UPLOAD_ERR_NO_FILE 		=> "Nessun file selezionato.",
  UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
  UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
  UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
);
?>
