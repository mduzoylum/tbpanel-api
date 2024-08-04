<?php


namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;

trait ApiResponser
{



    /**
     * Build success response
     *
     * @param JsonResource $resource
     * @param Paginator $data
     * @return JsonResponse
     */
    public function paginateResponse(
        $resource = null,
        $data = null
    ): JsonResponse
    {
        $data = $data->toArray();
        $response = [
            'data' => $resource,
            'paging' => [
                'total' => $data["total"],
                'limit' => $data["per_page"],
                'current_page' => $data["current_page"],
                'total_pages' => $data["last_page"],
            ],
        ];
        return response()->json($response, 200);
    }

    /**
     * Build success response
     *
     * @param array|null $message
     * @param array|null $data
     * @param string|null $summary
     * @return JsonResponse
     */
    public function successResponse(
        $message = null
    ): JsonResponse
    {
        return response()->json([
            'message' => $message
        ], 200);
    }

    /**
     * Build valide response
     *
     * @param string|array $message
     * @param int $code
     * @return Response
     */
    public function successMessage($message, $code): Response
    {
        return response($message, $code);
    }

    /**
     * Build error response
     *
     * @param array|null $message
     * @param array|null $data
     * @param string|null $summary
     * @return JsonResponse
     */
    public function errorResponse(
        $message = null,
        $data = null,
        $summary = null
    ): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
            'summary' => $summary,
        ], 200);
    }

    /**
     * Build error response
     *
     * @param string|array $message
     * @param int $code
     * @return Response
     */
    public function errorMessage($message, int $code): Response
    {
        return response($message, (int)$code);
    }


    /**
     * Build success response
     *
     * @param array|null $message
     * @param array|null $data
     * @param string|null $summary
     * @return JsonResponse
     */
    public function successResponseMovement(
        $message = null,
        $data = null
    ): JsonResponse
    {
        return response()->json([
            'success' => 1,
            'message' => [$message],
            'data' => [$data]
        ], 200);
    }

    /**
     * @param $message
     * @param $data
     * @return JsonResponse
     */
    public function successResponseMovementForGroup(
        $message = null,
        $data = null
    ): JsonResponse
    {
        return response()->json([
            'success' => 1,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    /**
     * Build valide response
     *
     * @param string|array $message
     * @param int $code
     * @return Response
     */
    public function successMessageMovement
    ($message, $code): Response
    {
        return response($message, $code);
    }
}

