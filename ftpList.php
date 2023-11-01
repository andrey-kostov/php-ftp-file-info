<?php
// Get form input values
$ftpServer = $_POST['ftpServer'];
$ftpUsername = $_POST['ftpUsername'];
$ftpPassword = $_POST['ftpPassword'];
$startDate = strtotime($_POST['startDate']);
$endDate = strtotime($_POST['endDate']);

// Get the directory name from the form input
$directory = $_POST['directory']; 

// Function to recursively list files in a specific directory and its subdirectories
function listFilesRecursively($ftpConnection, $directory, $startDate, $endDate) {
    // Change the working directory to the specified directory
    ftp_chdir($ftpConnection, $directory);

    // Get a list of files and directories in the current directory
    $items = ftp_rawlist($ftpConnection, '.');

    if ($items !== false) {
        foreach ($items as $item) {
            $parts = preg_split("/\s+/", $item);
            $itemName = end($parts);

            // Check if the item is a directory and if so, recursively search it
            if ($itemName !== '.' && $itemName !== '..' && $parts[0][0] === 'd') {
                listFilesRecursively($ftpConnection, $itemName, $startDate, $endDate);
            } else {
                // Get the last modification timestamp of the file
                $fileInfo = ftp_mdtm($ftpConnection, $itemName);

                if ($fileInfo >= $startDate && $fileInfo <= $endDate) {
                    $fileLocation = ftp_pwd($ftpConnection) . '/' . $itemName;
                    echo "File Name: $itemName<br>";
                    echo "File Location: $fileLocation<br>";
                    echo "Date of Modification: " . date('Y-m-d H:i:s', $fileInfo) . "<br>";
                    echo "<hr>";
                }
            }
        }
    }

    // Return to the parent directory after processing
    ftp_cdup($ftpConnection);
}

// Create an FTP connection
$ftpConnection = ftp_connect($ftpServer);
if (!$ftpConnection) {
    die("Unable to connect to the FTP server.");
}

// Login to the FTP server
$loginResult = ftp_login($ftpConnection, $ftpUsername, $ftpPassword);
if (!$loginResult) {
    die("Login failed.");
}

echo "<h2>Files modified between " . date('Y-m-d', $startDate) . " and " . date('Y-m-d', $endDate) . " in directory: $directory</h2>";

// Start the recursive search from the specified directory
listFilesRecursively($ftpConnection, $directory, $startDate, $endDate);

// Close the FTP connection
ftp_close($ftpConnection);
?>
