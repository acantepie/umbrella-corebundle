<?php

namespace Umbrella\CoreBundle\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUtils
 */
class FileUtils
{
    /**
     * @param string ...$parts
     * @return string
     */
    public static function resolvePath(...$parts) : string
    {
        $parts = func_get_args();
        $parts = array_filter($parts);
        $path = '';

        $i = 0;
        foreach ($parts as $part) {
            $i++;

            if (1 === $i) { // first element of array
                $path .= rtrim($part, '/');
                continue;
            }

            $path .= '/' . trim($part, '/');
        }

        return $path;
    }
}
