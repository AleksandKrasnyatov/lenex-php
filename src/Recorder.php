<?php

namespace leonverschuren\Lenex;

use SimpleXMLElement;
use ZipArchive;

class Recorder
{
    public function record(SimpleXMLElement $xml, string $path): bool|string
    {
        return $xml->asXML($path);
    }
    
    public function archive(string $path, string $archivePath = 'archive.lxf'): bool
    {
        $zip = new ZipArchive();

        if ($zip->open($archivePath, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($path, basename($path));
            $zip->close();
            return true;
        }
        return false;
    }
}