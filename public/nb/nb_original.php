<?php
$callerNumber = $_GET['callernumber'];
$query = "SELECT `Extension` FROM `CallerMap` WHERE `CallerNumber`='$callerNumber' LIMIT 1;";
$dbCon = odbc_connect("MyDatabase", "DBUser", "DBPass");

header("HTTP/1.1 200 OK");
header("content-type: application/xml; charset=UTF-8");

echo "<records>\n";
if ($dbCon) {
  $dbRes = odbc_exec($dbCon, $query);
  if ($dbRes) {
    while (odbc_fetch_row($dbRes)) {
      echo "<record>\n";
      echo "  <Extension>" . odbc_result($dbRes, "Extension") . "</Extension>\n";
      echo "</record>\n";
    }
  }
  odbc_close($dbCon);
}
echo "</records>\n";

////////////////// below is the mysqli version

// $foo = $_GET['f']; // cosa me ne faccio?
// $query = "SELECT * FROM chiamate_bc WHERE id = 1045 LIMIT 1;";
// $dbCon = mysqli_connect("localhost", "nax", "q4", "nca");
//
// echo "<records>\n";
// if ($dbCon) {
//   $dbRes = mysqli_query($dbCon, $query);
//   if ($dbRes) {
//     while ($row = mysqli_fetch_assoc($dbRes)) {
//       echo "  <record>\n" . "    <Utente>" . $row["utente"] . "</Utente>\n" . "  </record>\n";
//     }
//   }
//   mysqli_free_result($dbRes);
//   mysqli_close($dbCon);
// }
// echo "</records>\n";

?>
