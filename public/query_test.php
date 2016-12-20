<?php
// 1. connect "mysqli_connect()"
$handle = mysqli_connect("127.0.0.1", "federico.rizzo", "aqswdefr", "globale");

// 2. query "mysqli_query()"
$id = '1';
$safe_user_id = mysqli_real_escape_string($handle, $id);;
$query = "SELECT * FROM proposte WHERE id = {$safe_user_id} LIMIT 1;";
$result = mysqli_query($handle, $query);

// 3. fetch results "mysqli_fetch_assoc()"
$array = mysqli_fetch_assoc($result);

echo '<pre>'; print_r($array); echo '</pre>';
echo '<br>';
echo $array["id"];

// 4. release results "mysqli_free_result()"
mysqli_free_result($result);

// 5. disconnect "mysqli_close()"
mysqli_close($handle);
?>
