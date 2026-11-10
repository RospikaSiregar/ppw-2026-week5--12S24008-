<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    // Format respons sukses umum (200 OK)
    public function successResponse(mixed $data = null, string $message = 'Permintaan berhasil diproses.', int $status = 200, array $meta = []): JsonResponse
    {
        $payload = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if (!empty($meta)) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    // Format respons entitas baru berhasil dibuat (201 Created)
    public function createdResponse(mixed $data = null, string $locationUrl = '', string $message = 'Resource baru berhasil dibuat.'): JsonResponse
    {
        $response = response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], 201);

        if (!empty($locationUrl)) {
            $response->header('Location', $locationUrl);
        }

        return $response;
    }

    // Format respons penghapusan tanpa body (204 No Content)
    public function noContentResponse(): JsonResponse
    {
        return response()->json(null, 204);
    }

    // Format respons galat standar RFC 7807 (application/problem+json)
    public function problemResponse(string $title, string $detail, int $status, string $errorSlug = 'general-error', array $invalidParams = []): JsonResponse
    {
        $payload = [
            'type' => url("/docs/errors/{$errorSlug}"),
            'title' => $title,
            'status' => $status,
            'detail' => $detail,
            'instance' => request()->getRequestUri(),
        ];

        if (!empty($invalidParams)) {
            $payload['invalid_params'] = $invalidParams;
        }

        return response()->json($payload, $status)
            ->header('Content-Type', 'application/problem+json');
    }
}