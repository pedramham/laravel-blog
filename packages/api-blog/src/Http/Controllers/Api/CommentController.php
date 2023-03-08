<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Http\ApiBlogResponse;
use Admin\ApiBolg\Http\Requests\Comment\CommentRequest;
use Admin\ApiBolg\Http\Requests\Comment\EditRequest;
use Admin\ApiBolg\Http\Requests\Comment\ListRequest;
use Admin\ApiBolg\Http\Requests\Comment\StoreRequest;
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
     *      tags={"Comment"},
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
    public function store(StoreRequest $request): ApiBlogResponse
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

    /**
     * @OA\Put(
     *      path="/blog-api/comment/v1/edit",
     *      operationId="edit-comment",
     *      tags={"Comment"},
     *      summary="Edit comment in DB",
     *      description="Edit comment in DB",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"id"},
     *            @OA\Property(property="name", type="string", format="string", example="Test name"),
     *            @OA\Property(property="email", type="string", format="string", example="Test email"),
     *            @OA\Property(property="id", type="integer", format="integer", example="1"),
     *            @OA\Property(property="comments", type="string", format="string", example="Test comments"),
     *            @OA\Property(property="status", type="boolean", format="boolean", example="true"),
     *            @OA\Property(
     *               property="tags",
     *               type="array",
     *                  @OA\Items,
     *                     example={
     *                     "id": "1",
     *                     "name": "tag post",
     *                   },
     *                )
     *           ),
     *       ),
     *      @OA\Response(
     *          response="200",
     *          description="An example resource",
     *          @OA\JsonContent(
     *              type="object",
     *                   @OA\Property(
     *                       property="success",
     *                       type="integer",
     *                       example="1"
     *                     ),
     *                   @OA\Property(
     *                       property="message",
     *                       type="string",
     *                       example="edit success"
     *                    ),
     *                    @OA\Property(
     *                        property="data",
     *                        type="boolean",
     *                        example="true"
     *                    ),
     *          )
     *     ),
     *     @OA\Response(response="401", description="Error  edit"),
     *  )
     */
    public function edit(EditRequest $request) : ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->commentService->edit($input,Comment::class),
                'Post edit successfully',
                true,
                200,

            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *      path="/blog-api/comment/v1/list",
     *      operationId="list-comment",
     *      tags={"Comment"},
     *      summary="list comment",
     *      description="list comment",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"skip","take"},
     *            @OA\Property(property="skip", type="integer", format="integer", example="0"),
     *            @OA\Property(property="take", type="integer", format="integer", example="10"),
     *         ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="An example resource",
     *          @OA\JsonContent(
     *              type="object",
     *                     @OA\Property(
     *                         property="success",
     *                         type="integer",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items,
     *                         example={
     *                          {
     *                            "name": "Post Comment",
     *                            "email": "test@test.test",
     *                            "post_id": "1",
     *                            "comments": "comment Post",
     *                            "status": "0",
     *                            "created_at": "2023-02-19T07:39:12.000000Z",
     *                            "id": "1",
     *                          },
     *                         },
     *                     ),

     *          )
     *     ),
     *     @OA\Response(response="401", description="Error  edit"),
     *  )
     */
    public function list(ListRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->commentService->listPagination(Comment::class, $input),
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Delete(
     *      path="/blog-api/comment/v1/soft-delete",
     *      operationId="soft-delete-comment",
     *      tags={"Comment"},
     *      summary="Soft-delete Comment in DB",
     *      description="Soft-delete Comment in DB",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"id"},
     *            @OA\Property(property="id", type="integer", format="integer", example="2")
     *         ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="An example resource",
     *          @OA\JsonContent(
     *              type="object",
     *                     @OA\Property(
     *                         property="success",
     *                         type="integer",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                    @OA\Property(
     *                         property="data",
     *                         type="string",
     *                         example="true"
     *                     ),

     *          )
     *     ),
     *     @OA\Response(response="401", description="Error  soft delete"),
     *  )
     */
    public function softDelete(CommentRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->commentService->softDelete($input,Comment::class),
                'Comment soft deleted successfully',
                200
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Put(
     *      path="/blog-api/comment/v1/restore-delete",
     *      operationId="restore-delete-comment",
     *      tags={"Comment"},
     *      summary="restore delete comment in DB",
     *      description="when you delete comment in DB you can restore it",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"id"},
     *            @OA\Property(property="id", type="integer", format="integer", example="2")
     *         ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="An example resource if success",
     *          @OA\JsonContent(
     *              type="object",
     *                     @OA\Property(
     *                         property="success",
     *                         type="integer",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                    @OA\Property(
     *                         property="data",
     *                         type="string",
     *                         example="true"
     *                     ),
     *          )
     *     ),
     *     @OA\Response(response="401", description="Error Restore delete"),
     *  )
     */
    public function restoreDelete(CommentRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->commentService->restoreDelete($input,Comment::class),
                'Comment restore successfully',
                200
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }
}
