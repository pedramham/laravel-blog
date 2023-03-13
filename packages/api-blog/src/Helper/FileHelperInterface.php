<?php

namespace Admin\ApiBolg\Helper;

interface FileHelperInterface
{
    public function storeFile(array $input, string $path): array;

    public function deleteFile(string $typeFolder, array $filenames): void;
}
