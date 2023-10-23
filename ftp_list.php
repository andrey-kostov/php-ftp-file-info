<?php
// Get form input values
$ftp_server = $_POST['ftp_server'];
$ftp_username = $_POST['ftp_username'];
$ftp_password = $_POST['ftp_password'];
$start_date = strtotime($_POST['start_date']);
$end_date = strtotime($_POST['end_date']);
$directory = $_POST['directory']; // Get the directory name from the form input

// Function to recursively list files in a specific directory and its subdirectories
function listFilesRecursively($ftp_connection, $directory, $start_date, $end_date) {
    // Change the working directory to the specified directory
    ftp_chdir($ftp_connection, $directory);

    // Get a list of files and directories in the current directory
    $items = ftp_rawlist($ftp_connection, '.');

    if ($items !== false) {
        foreach ($items as $item) {
            $parts = preg_split("/\s+/", $item);
            $item_name = end($parts);

            // Check if the item is a directory and if so, recursively search it
            if ($item_name !== '.' && $item_name !== '..' && $parts[0][0] === 'd') {
                listFilesRecursively($ftp_connection, $item_name, $start_date, $end_date);
            } else {
                // Get the last modification timestamp of the file
                $file_info = ftp_mdtm($ftp_connection, $item_name);

                if ($file_info >= $start_date && $file_info <= $end_date) {
                    $file_location = ftp_pwd($ftp_connection) . '/' . $item_name;
                    echo "File Name: $item_name<br>";
                    echo "File Location: $file_location<br>";
                    echo "Date of Modification: " . date('Y-m-d H:i:s', $file_info) . "<br>";
                    echo "<hr>";
                }
            }
        }
    }

    // Return to the parent directory after processing
    ftp_cdup($ftp_connection);
}

// Create an FTP connection
$ftp_connection = ftp_connect($ftp_server);
if (!$ftp_connection) {
    die("Unable to connect to the FTP server.");
}

// Login to the FTP server
$login_result = ftp_login($ftp_connection, $ftp_username, $ftp_password);
if (!$login_result) {
    die("Login failed.");
}

echo "<h2>Files modified between " . date('Y-m-d', $start_date) . " and " . date('Y-m-d', $end_date) . " in directory: $directory</h2>";

// Start the recursive search from the specified directory
listFilesRecursively($ftp_connection, $directory, $start_date, $end_date);

// Close the FTP connection
ftp_close($ftp_connection);
?>
