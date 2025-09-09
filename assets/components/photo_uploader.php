<?php
function uploadFile($file, $folder, $prefix)
        {
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
            $filename = $prefix . ".png";
            $targetPath = $folder . "/" . $filename;
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                return true;
            } else {
                return false;
            }
        }
?>