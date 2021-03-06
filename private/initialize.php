<?php
// setlocale(LC_TIME, 'it_IT');
// setlocale(LC_ALL, 'it_IT');

// DIRECTORY_SEPARATOR is / for unix and the other slash for windows
defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);
defined("SITE_ROOT") ? null : define("SITE_ROOT", DS."var".DS."www".DS."html");
defined("LIB_PATH") ? null : define("LIB_PATH", SITE_ROOT.DS."private");
defined("TMP_PATH") ? null : define("TMP_PATH", SITE_ROOT.DS."public".DS."tmp");

// the order of the requires IS VERY IMPORTANT
require_once(LIB_PATH.DS."config.php");
require_once(LIB_PATH.DS."functions.php");

require_once(LIB_PATH.DS."class.Database.inc");

require_once(LIB_PATH.DS."class.Session.inc");
require_once(LIB_PATH.DS."class.User.inc");
require_once(LIB_PATH.DS."class.Branch.inc");
require_once(LIB_PATH.DS."class.Dept.inc");

require_once(LIB_PATH.DS."class.Chiamata.inc");
require_once(LIB_PATH.DS."class.SqlTable.inc");

require_once(LIB_PATH.DS."class.Dropdown.inc");
require_once(LIB_PATH.DS."class.Form.inc");

require_once(LIB_PATH.DS."class.Validation.inc");

require_once(LIB_PATH.DS."class.Time.inc");
require_once(LIB_PATH.DS."class.String.inc");

require_once(LIB_PATH.DS."class.CellStats.inc");
?>
