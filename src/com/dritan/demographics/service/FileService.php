<?php

namespace Com\Dritan\Demographics\Service;

use Com\Dritan\Demographics\Exception\FileNotFoundException;


class FileService
{
    public static function listXmlFiles($directory)
    {
        if (!is_dir($directory)) {
            throw new FileNotFoundException($directory);
        }

        $allFiles = scandir($directory);
        $candidateFiles = [];

        foreach ($allFiles as $file) {
            $fileNameLength = strlen($file);
            if ($fileNameLength > 4 && substr($file, $fileNameLength - 4) == '.xml') {
                $candidateFiles[] = $file;
            }
        }

        return $candidateFiles;
    }
}