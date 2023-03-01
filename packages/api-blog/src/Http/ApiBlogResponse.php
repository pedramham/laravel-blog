<?php

namespace Admin\ApiBolg\Http;

use Illuminate\Http\JsonResponse as BaseJsonResponse;

class ApiBlogResponse extends BaseJsonResponse
{
    public function __construct(
        mixed $data = null,
        string $message = null,
        bool $success = true,
        int|string $status = 200,
        array $headers = [],
        int $options = 0,
        bool $json = false
    ) {
        $data = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];

        $this->statusCode = $status;
        if ($this->isInvalid()) {
            $status = self::HTTP_INTERNAL_SERVER_ERROR;
        }

        parent::__construct($data, $status, $headers, $options, $json);
    }
}
