<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Files</title>
    <style>
        body {
            font-family: monospace;
            font-size: 14px;
            background-color: #131214;
            color: #f7db9e;
            padding: 10px;
        }

        a {
            text-decoration: none;
            color: #f7efd9;
        }

        a:visited {
            color: white;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>

    <?php

    /**
     * Function to list the contents of a directory in a tree-like structure
     * 
     * @param string $dir The directory to list the contents of
     * @param int $indent The number of levels of indentation
     * @param string $repitition A string to repeat for indentation
     * @return void
     */
    function listFiles($dir, $indent, $repitition)
    {

        // Get the list of files in the directory
        $files = scandir($dir);

        // Exclude some files
        $excludedFilenames = array('.', '..', 'gabesFileLister.php', 'gabesFileListerMin.php', 'files.php', 'index.php','.DS_Store', '.git');

        // Remove excluded files
        $files = array_diff($files, $excludedFilenames);

        if (count($files) == 0 && $indent == 0) {
            echo "<br />No files found in this directory.";
            return;
        }

        // Loop through the files
        foreach ($files as $file) {

            // Get the full path of the file
            $filePath = $dir . '/' . $file;

            // Initialize variables
            $newPrefix = "";
            $newRepitition = $repitition;

            if ($indent > 0) {
                if ($file == end($files)) {
                    $newPrefix =  "└─ "; // Last file in the list
                    $newRepitition = $repitition . (is_dir($filePath) ? "&nbsp;&nbsp;&nbsp;&nbsp;" : "│&nbsp;&nbsp;&nbsp;"); // Indentation for directories
                } else {
                    $newPrefix = "├─ "; // Not the last file
                    $newRepitition = $repitition . "│&nbsp;&nbsp;&nbsp;"; // Indentation for files
                }
            }


            // Check if the current file is a directory
            if (is_dir($filePath)) {

                echo ($indent == 0) ? "<br />" : $repitition . "│&nbsp;&nbsp;&nbsp; <br/>";

                echo $repitition . $newPrefix . $file .  ' <br/>';

                listFiles($filePath, $indent + 1, $newRepitition);
            } else {
                echo $repitition . $newPrefix .  '<a href="' . $filePath . '">' . $file . '</a> <br/>';
            }
        }
    }


    listFiles('.', 0, '');

    ?>

    <br /><br />-------<br />
    Displayed with <a href="https://github.com/gabrielchantayan/gabesFileLister">gabesFileLister</a> v1.2
    <br><br>


</body>

</html>