<?php


$host= 'ftp.iron-devs.com';
$user = 'teamiron@iron-devs.com';
$password = '5YNvFOCA)4ji';

$allowedExtensions = array('php','js','css','tpl','twig');

$ftpConnection = ftp_connect($host);
$ftpLogin = ftp_login($ftpConnection,$user,$password);

// check connection
if ((!$ftpConnection) || (!$ftpLogin)) {
 echo 'FTP connection has failed! Attempted to connect to '. $host. ' for user '.$user.'.';
} else{
 echo 'FTP connection was a success.';
}

$file_list = ftp_nlist($ftpConnection, "/public_html");

foreach ($file_list as $list){

    $fileInfo = pathinfo($list); 
    $fileStat = stat($list[0]);

    $fileName = $fileInfo['basename'];
    $filePath = $fileInfo['dirname'];
    $fileModifiedTime = date("F d Y", $fileStat['mtime']);

    echo '<pre>';
    var_dump($fileName,$filePath,$fileModifiedTime);
    echo '</pre>';
}


//Get all items from the directory

// foreach ($all_paths as $path){
    
//     $pathInfo = pathinfo($path);

//     if(array_key_exists('extension', $pathInfo)){

//         if(in_array($pathInfo['extension'],  $allowedExtensions)){
//                 echo '<pre>';
//                 var_dump($_SERVER['DOCUMENT_ROOT']);
//                 echo '</pre>';

//                 $pathStats = stat($path);

//                 echo 'File name - ' . $pathInfo['basename'] 
//                 . ' - Last modified on - ' . date ("F d Y", $pathStats['mtime']) 
//                 . ' Item Path - ' . $pathInfo['dirname'] . '<br>';
//             }
            
//     }else{
//         echo '<br> <hr> this is a folder <hr> <br>';
//     }
    
// }


ftp_close($ftpConnection);

?>