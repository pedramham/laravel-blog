<?php
namespace Admin\ApiBolg\Helper;

use Symfony\Component\HttpFoundation\FileBag;

interface FileHelperInterface
{
    public function storeFile(FileBag $request, array $input, string $path): array;
    public function deleteFile(string $typeFolder, array $filenames): void;
}
