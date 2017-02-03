<?php
// require('../private/initialize.php');
// require('/var/www/html/private/SMB-master/vendor/autoload.php');
//
// use Icewind\SMB\Server;
//
// $nas = new Server('10.4.4.250', 'portale', '76837683');

// $shares = $nas->listShares();
// foreach ($shares as $share) {
//   // echo $share->getName() . "\n";
//   echo $share->getName() . "<br>";
// }
//////////////////////////////////////////////////
// $share = $nas->getShare('DEPT_OPS');
// $contents = $share->dir('AGENZIE/OPS_ANCONA/2016.09.19');
//
// foreach ($contents as $item) {
//     echo $item->getName();
//     echo " size :" . $item->getSize();
//     echo " path :" . $item->getPath();
//     echo " dir :" . $item->isDirectory();
//     echo " time :" . $item->getMTime();
//     echo '<br>';
// }
//////////////////////////////////////////////////
// $fileToUpload = __FILE__;
// $fileToUpload = '/var/www/html/private/uploads/caio.pdf';
//
// $share = $nas->getShare('DEPT_OPS');
// $share->put($fileToUpload, 'PROVA_FTP/OPS_bari/example.txt');

$bella = base64_encode('ciao');
echo $bella;
$yo = base64_decode($bella);
echo $yo;
?>
