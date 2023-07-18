<?php

namespace Admin\ApiBolg\Helper;

interface FileHelperInterface
{
    public function storeFile(array $input): array;

    public function deleteFile(array $input, string $issueType): void;
}
