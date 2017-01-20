 <?php
$upload_errors = array(
	UPLOAD_ERR_OK 				=> "No errors.",
	UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
  UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
  UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
  UPLOAD_ERR_NO_FILE 		=> "No file.",
  UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
  UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
  UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
);

if(isset($_POST['submit'])) {

  echo '<pre>';
  print_r($_FILES['file_upload']);
  echo '</pre>';
	// process the form data
	$tmp_file = $_FILES['file_upload']['tmp_name'];
	$target_file = basename($_FILES['file_upload']['name']);
	$upload_dir = "uploads";

	// You will probably want to first use file_exists() to make sure
	// there isn't already a file by the same name.

	// move_uploaded_file will return false if $tmp_file is not a valid upload file
	// or if it cannot be moved for any other reason
	if(move_uploaded_file($tmp_file, $upload_dir."/".$target_file)) {
		$message = "File uploaded successfully.";
	} else {
		$error = $_FILES['file_upload']['error'];
		$message = $upload_errors[$error];
	}

}

?>
<html>
	<head>
		<title>Upload</title>
	</head>
	<body>

		<?php if(!empty($message)) { echo "<p>{$message}</p>"; } ?>
		<form action="upload.php" enctype="multipart/form-data" method="POST">

		  <input type="hidden" name="MAX_FILE_SIZE" value="8000000" />
			<!-- The maximum file size (in bytes) must be declared before the file input field
			and can't be larger than the setting for upload_max_filesize in php.ini.

			This form value can be manipulated. You should still use it, but you rely
			on upload_max_filesize as the absolute limit.

			Think of it as a polite declaration: "Hey PHP, here comes a file less than X..."
			PHP will stop and complain once X is exceeded.

			1 megabyte is actually 1,048,576 bytes.
			You can round it unless the precision matters. -->
			<input type="file" name="file_upload" />

		  <input type="submit" name="submit" value="Upload" />
		</form>

	</body>
</html>
