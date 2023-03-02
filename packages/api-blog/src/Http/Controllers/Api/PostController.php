<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Http\ApiBlogResponse;
use Admin\ApiBolg\Http\Requests\Post\EditRequest;
use Admin\ApiBolg\Http\Requests\Post\ShowRequest;
use Admin\ApiBolg\Http\Requests\Post\StoreRequest;
use Admin\ApiBolg\Models\Post;
use Admin\ApiBolg\Services\PostService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class PostController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }


    /**
     * @OA\Post(
     *      path="/blog-api/post/v1/store",
     *      operationId="storePost",
     *      tags={"Post"},
     *      summary="Store post in DB",
     *      description="Store post in DB",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"title", "slug"},
     *            @OA\Property(property="title", type="string", format="string", example="Test Article Title"),
     *            @OA\Property(property="slug", type="string", format="string", example="test-article-title"),
     *            @OA\Property(property="subject", type="string", format="string", example="Test Article Subject"),
     *            @OA\Property(property="description", type="string", format="string", example="Test Article Description"),
     *            @OA\Property(property="pic_small", type="string", format="string", example="Test Article Pic Small"),
     *            @OA\Property(property="pic_large", type="string", format="string", example="Test Article Pic Large"),
     *            @OA\Property(property="category_id", type="integer", format="integer", example="1"),
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
     *                         type="Create new Post",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items,
     *                         example={
     *                         "name": "post name",
     *                         "title": "title post",
     *                         "slug": "slug category",
     *                         "description": "description post",
     *                         "pic_small": "pic_small post",
     *                         "pic_large": "pic_large post",
     *                         "category_id": "1",
     *                         "updated_at": "2023-02-19T07:39:12.000000Z",
     *                         "created_at": "2023-02-19T07:39:12.000000Z",
     *                         "id": "1",
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
                $this->postService->store($input, Post::class),
                'Post created successfully',
                true,
                200
            );
        } catch (\Exception $exception) {

            return new ApiBlogResponse(null, $exception->getMessage(), false, (int)$exception->getCode());
        }
    }

    /**
     * @OA\Get(
     *      path="/blog-api/post/v1/list",
     *      operationId="list-post",
     *      tags={"Post"},
     *      summary="list Post",
     *      description="list Post",
     *      security={{ "apiAuth": {} }},
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
     *                         "name": "Post",
     *                         "title": "title Post",
     *                         "slug": "slug Post",
     *                         "description": "description Post",
     *                         "pic_small": "pic_small Post",
     *                         "pic_large": "pic_large Post",
     *                         "category_id": "1",
     *                         "updated_at": "2023-02-19T07:39:12.000000Z",
     *                         "created_at": "2023-02-19T07:39:12.000000Z",
     *                         "id": "1",
     *                          },
     *                          {
     *                          "name": "Post",
     *                         "title": "title Post",
     *                         "slug": "slug Post",
     *                         "description": "description Post",
     *                         "pic_small": "pic_small Post",
     *                         "pic_large": "pic_large Post",
     *                         "category_id": "1",
     *                         "updated_at": "2023-02-19T07:39:12.000000Z",
     *                         "created_at": "2023-02-19T07:39:12.000000Z",
     *                          },
     *                         },
     *                     ),

     *          )
     *     ),
     *     @OA\Response(response="401", description="Error  edit"),
     *  )
     */
    public function list(): ApiBlogResponse
    {
        try {
            return new ApiBlogResponse(
                $this->postService->list(Post::class)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *      path="/blog-api/post/v1/show",
     *      operationId="show-post",
     *      tags={"Post"},
     *      summary="Store post in DB",
     *      description="Store category in DB",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"id"},
     *            @OA\Property(property="id", type="integer", format="integer", example="1"),
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
     *                         "name": "post",
     *                         "title": "title post",
     *                         "slug": "slug post",
     *                         "description": "description post",
     *                         "pic_small": "pic_small post",
     *                         "pic_large": "pic_large post",
     *                         "parent_id": "1",
     *                         "updated_at": "2023-02-19T07:39:12.000000Z",
     *                         "created_at": "2023-02-19T07:39:12.000000Z",
     *                         "id": "1",
     *                         },
     *                     ),

     *          )
     *     ),
     *     @OA\Response(response="401", description="Error  edit"),
     *  )
     */
    public function show(ShowRequest $request)
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->postService->show($input,Post::class)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Put(
     *      path="/blog-api/post/v1/edit",
     *      operationId="edit-post",
     *      tags={"Post"},
     *      summary="Edit post in DB",
     *      description="Edit post in DB",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"id"},
     *            @OA\Property(property="id", type="integer", format="integer", example="2"),
     *            @OA\Property(property="title", type="string", format="string", example="Test Article Title"),
     *            @OA\Property(property="slug", type="string", format="string", example="test-article-title"),
     *            @OA\Property(property="subject", type="string", format="string", example="Test Article Subject"),
     *            @OA\Property(property="description", type="string", format="string", example="Test Article Description"),
     *            @OA\Property(property="pic-small", type="string", format="string", example="Test Article Pic Small"),
     *            @OA\Property(property="pic-large", type="string", format="string", example="Test Article Pic Large"),
     *            @OA\Property(property="category_id", type="integer", format="integer", example="1"),
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
     *                         "name": "post",
     *                         "title": "title post",
     *                         "slug": "slug post",
     *                         "description": "description post",
     *                         "pic_small": "pic_small post",
     *                         "pic_large": "pic_large post",
     *                         "parent_id": "1",
     *                         "updated_at": "2023-02-19T07:39:12.000000Z",
     *                         "created_at": "2023-02-19T07:39:12.000000Z",
     *                         "id": "1",
     *                         },
     *                     ),

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
                $this->postService->edit($input, Post::class)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }


    /**
     * @OA\Delete(
     *      path="/blog-api/post/v1/soft-delete",
     *      operationId="soft-delete-post",
     *      tags={"Post"},
     *      summary="Soft-delete post in DB",
     *      description="Soft-delete post in DB",
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
    public function softDelete(ShowRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->postService->softDelete($input,Post::class),
                200
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Delete(
     *      path="/blog-api/post/v1/delete",
     *      operationId="delete-post",
     *      tags={"Post"},
     *      summary="delete post in DB",
     *      description="delete post in DB",
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
     *     @OA\Response(response="401", description="Error Delete"),
     *  )
     */
    public function delete(ShowRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->postService->delete($input,Post::class),
                200
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Put(
     *      path="/blog-api/post/v1/restore-delete",
     *      operationId="restore-delete-post",
     *      tags={"Post"},
     *      summary="restore delete post in DB",
     *      description="when you delete post in DB you can restore it",
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
    public function restoreDelete(ShowRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->postService->restoreDelete($input,Post::class),
                200
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }
}
