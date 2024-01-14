<?php

namespace App\Traits;

trait HasFiles
{
    private function renameFile($prefix, $name, $file, $other = null)
    {
        $newFileName = str_replace([' ', '%'], '', $prefix.'-'.$name.'-'.$other).'.'.$file->extension();

        return $newFileName;
    }

    private function moveFileToStorage($location, $file, $name)
    {
        $path = $file->storeAs($location, $name);

        return $path;
    }
}
