<?php 

$allowedExtensions = array('php','js','css','tpl','twig','txt','ini','htaccess');

$documentRoot = $_SERVER['DOCUMENT_ROOT'];

function getFilesInDirectory ($directory){
    
    $files = scandir($directory);
    
    return $files;
}

function listFilesInformation ($files,$extensions,$directory){

    echo '<strong>This is the content of ' . $directory . ' directory:</strong><br><hr><br>';

    foreach ($files as $file){
        $pathInfo = pathinfo($file);
        
        if(in_array($pathInfo['extension'], $extensions)){
            $fileStat = stat($pathInfo['basename']);
            $lastModified = $fileStat['mtime'];
            echo 'File name - ' . $pathInfo['basename'] . '<br>Last modified on - ' . $lastModified . '<br>Path to file - ' . $pathInfo['dirname'] . '<hr>';
        }else{
            var_dump($pathInfo);
            echo '<hr>this is a folder<hr>';
        }
    }
    
}


echo '<pre>';
listFilesInformation(getFilesInDirectory($documentRoot),$allowedExtensions,$directory);
echo '</pre>';

?>