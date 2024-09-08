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

        div {
            margin: 0px;
            padding: 0px;
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

        .folderName {
            color: #f7db9e !important;
        }
    </style>

    <script>
        function showHide(e) {

            var folder = document.getElementById(e);
            var icon = document.getElementById(`icon-${e}`);
            if (folder.style.display === "none") {
                folder.style.display = "block";
                icon.style.display = "none";
            } else {
                folder.style.display = "none";
                icon.style.display = "inline";
            }
        }
    </script>

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
        $excludedFilenames = array('.', '..', 'gabesFileLister.php', 'gabesFileListerMin.php', 'files.php', 'index.php', '.DS_Store', '.git');

        // Remove excluded files
        $files = array_diff($files, $excludedFilenames);


        // Sort the files by if they are directories or not, using is_dir($filePath)
        usort($files, function ($a, $b) {
            return is_dir($b) <=> is_dir($a);
        });


        // Check if the root directory is empty, if so then show a message
        if (count($files) == 0 && $indent == 0) {
            echo "<br />No files found in this directory.";
            return;
        }

        // Loop through the files
        foreach ($files as $file) {

            // Get the full path of the file
            $filePath = $dir . '/' . $file;


            // Replace all instances of . / \ with dashes
            $dirID = "dir" . preg_replace('/[.\/\\ ]/', '-', $filePath);

            // Initialize variables
            $newPrefix = "";
            $newRepitition = $repitition;

            // If not in the root directory then generate the lines
            if ($indent > 0) {

                // Check if the current file is the last file
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

                // Padding so folders aren't touching
                echo ($indent == 0) ? "<br />" : $repitition . "│&nbsp;&nbsp;&nbsp; <br/>";

                // Folder name + show/hide button
                echo $repitition . $newPrefix . '<a href="#link-' . $dirID . '" class="folderName" id="link-' . $dirID . '" onClick="showHide(\'' . $dirID . '\')">' . $file . '<span style="display:none"id="icon-' . $dirID . '"> (-)</span></a><br/><div id="' . $dirID . '">';

                // Recursively call the function
                listFiles($filePath, $indent + 1, $newRepitition);

                echo '</div>';
            }
            // If the current file is a file
            else {

                // File name
                echo $repitition . $newPrefix .  '<a href="' . $filePath . '">' . $file . '</a> <br/>';
            }
        }
    }


    listFiles('.', 0, '');

    ?>

    <br /><br />-------<br />
    Displayed with <a href="https://github.com/gabrielchantayan/gabesFileLister">gabesFileLister</a> v1.4
    <br><br>


</body>

</html>