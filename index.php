<?php

$host= 'ftp.iron-devs.com';
$user = 'teamiron@iron-devs.com';
$password = '5YNvFOCA)4ji';

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
        var_dump($pathInfo);
        echo 'File name - ' . $pathInfo['basename'] . ' - Last modified on - ' . filemtime($pathInfo['dirname'].$pathInfo['basename']) . ' Item Path - ' . $pathInfo['dirname'] . '<br>';

    }else{
        echo '<br> <hr> this is a folder <hr> <br>';
    }
    
}

//fitemtime - gives last modified time





ftp_close($ftpConnection);

?>