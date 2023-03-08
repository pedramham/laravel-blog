<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Http\ApiBlogResponse;
use Admin\ApiBolg\Http\Requests\Comment\CommentRequest;
use Admin\ApiBolg\Models\Comment;
use Admin\ApiBolg\Services\CommentService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * @OA\Post(
     *      path="/blog-api/comment/v1/store",
     *      operationId="storeComment",
     *      tags={"Setting"},
     *      summary="Store comment in DB",
     *      description="Store comment in DB",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"name", "email", "post_id", "comments"},
     *            @OA\Property(property="name", type="string", format="string", example="Test name"),
     *            @OA\Property(property="email", type="string", format="string", example="Test email"),
     *            @OA\Property(property="post_id", type="integer", format="integer", example="1"),
     *            @OA\Property(property="comments", type="string", format="string", example="Test comments"),
     *            @OA\Property(property="status", type="boolean", format="boolean", example="true"),
     *         ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="An example resource",
     *          @OA\JsonContent(
     *              type="object",
     *                     @OA\Property(
     *                         property="success",
     *                         type="boolaen",
     *                         example="true"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="Create new Comment",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items,
     *                         example={
     *                               "name": "name",
     *                               "email": "email",
     *                               "post_id": 1,
     *                               "comments": "comments",
     *                               "status": 1,
     *                               "updated_at": "2021-09-01T09:12:12.000000Z",
     *                               "created_at": "2021-09-01T09:12:12.000000Z",
     *                               "id": 1
     *                         },
     *                     ),
     *          )
     *     ),
     *     @OA\Response(response="401", description="Error  edit"),
     *  )
     */
    public function store(CommentRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->commentService->store($input, Comment::class),
                'Comment created successfully',
                true,
                200
            );
        } catch (\Exception $exception) {

            return new ApiBlogResponse(null, $exception->getMessage(), false, (int)$exception->getCode());
        }
    }

}
