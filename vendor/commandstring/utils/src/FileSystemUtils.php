<?php

namespace CommandString\Utils;

use LogicException;

class FileSystemUtils
{
    protected static function checkDirectoriesExistence(string $directory): void
    {
        $directory = realpath($directory);

        if (!$directory) {
            throw new LogicException("The directory provided does not exist");
        }
    }

    public static function getAllFiles(string $directory, bool $recursive = false): array
    {
        self::checkDirectoriesExistence($directory);

        $directory = realpath($directory);

        $files = [];

        foreach (scandir($directory) as $file) {
            if ($file == "." || $file == "..") {
                continue;
            }

            $file_path = "$directory" . DIRECTORY_SEPARATOR . "$file";

            if (is_dir($file_path)) {
                if ($recursive) {
                    $files = array_merge($files, self::getAllFiles($file_path, true));
                }

                continue;
            }

            $files[] = $file_path;
        }

        return $files;
    }

    public static function getAllSubDirectories(string $directory, bool $recursive = false): array
    {
        self::checkDirectoriesExistence($directory);

        $directory = realpath($directory);

        $directories = [];

        foreach (scandir($directory) as $file) {
            if ($file == "." || $file == "..") {
                continue;
            }

            $file_path = "$directory" . DIRECTORY_SEPARATOR . "$file";

            if (is_dir($file_path)) {
                if ($recursive) {
                    $directories = array_merge($directories, self::getAllSubDirectories($file_path, true));
                }

                $directories[] = $file_path;
            }
        }

        return $directories;
    }

    public static function getAllFilesWithExtensions(
        string $directory,
        array $extensionsToFind,
        bool $recursive = false
    ): array {
        $files = [];

        foreach (self::getAllFiles($directory, $recursive) as $file) {
            $file_extension = str_replace(".", "", strchr($file, "."));

            if (in_array($file_extension, $extensionsToFind)) {
                $files[] = $file;
            }
        }

        return $files;
    }
}
