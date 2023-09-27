<?php

//НАПРАВИ ГО С КАЧВАНЕ НА ФАЙЛА В САМОТО ФТП

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

function changeDirectory ($dirName,$connection){


    //change directory
    ftp_chdir($connection, $dirName);

    //set directory to variable
    $directory = ftp_pwd($connection);

    //get all paths in the directory
    $paths = ftp_nlist($connection,$directory);

    return $paths;

}

$all_paths = changeDirectory('public_html',$ftpConnection);

//Get all items from the directory

foreach ($all_paths as $path){
    
    $pathInfo = pathinfo($path);

    if(array_key_exists('extension', $pathInfo)){

        if(in_array($pathInfo['extension'],  $allowedExtensions)){
                echo '<pre>';
                var_dump($_SERVER['DOCUMENT_ROOT']);
                echo '</pre>';

                $pathStats = stat($path);

                echo 'File name - ' . $pathInfo['basename'] 
                . ' - Last modified on - ' . date ("F d Y", $pathStats['mtime']) 
                . ' Item Path - ' . $pathInfo['dirname'] . '<br>';
            }
            
    }else{
        echo '<br> <hr> this is a folder <hr> <br>';
    }
    
}

//fitemtime - gives last modified time





ftp_close($ftpConnection);

?>