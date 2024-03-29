<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Helper\FileHelper;
use Admin\ApiBolg\Http\ApiBlogResponse;
use Admin\ApiBolg\Http\Requests\Post\DeletePost;
use Admin\ApiBolg\Http\Requests\Post\DeletePostRequest;
use Admin\ApiBolg\Http\Requests\Post\EditRequest;
use Admin\ApiBolg\Http\Requests\Post\ListRequest;
use Admin\ApiBolg\Http\Requests\Post\PostRequest;
use Admin\ApiBolg\Http\Requests\Post\SoftDeletePost;
use Admin\ApiBolg\Http\Requests\Post\StoreRequest;
use Admin\ApiBolg\Models\Post;
use Admin\ApiBolg\Services\PostService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Log;

class PostController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private PostService $postService;

    public function __construct(PostService $postService, FileHelper $fileService)
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
     *            required={"title", "slug", "issue_type","folder_name"},
     *            @OA\Property(property="name", type="string", format="string", example="Test post name"),
     *            @OA\Property(description="Folder Name", property="folder_name", type="string", example="post"),
     *            @OA\Property(property="title", type="string", format="string", example="Test Post Title"),
     *            @OA\Property(property="status", type="string", format="string", example="Test Post status"),
     *            @OA\Property(property="slug", type="string", format="string", example="Test Slug status"),
     *            @OA\Property(property="subject", type="string", format="string", example="Test Post Subject"),
     *            @OA\Property(property="description", type="string", format="string", example="Test Post Description"),
     *            @OA\Property(property="meta_description", type="string", format="string", example="Test Post Meta Description"),
     *            @OA\Property(property="meta_keywords", type="string", format="string", example="Test Post Meta Keywords"),
     *            @OA\Property(property="meta_language", type="string", format="string", example="Test Post Meta Language"),
     *            @OA\Property(property="tweet_text", type="string", format="string", example="Test Post Tweet Text"),
     *            @OA\Property(property="issue_type", type="string", format="string", example="Test Post Post Type"),
     *            @OA\Property(property="menu_order", type="integer", format="integer", example="1"),
     *            @OA\Property(property="priority", type="integer", format="integer", example="100"),
     *            @OA\Property(property="comment_status", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="menu_status", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="visible_index_status", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="category_id", type="integer", format="integer", example="1"),
     *            @OA\Property(
     *                  property="tags",
     *                  type="array",
     *                  @OA\Items,
     *                      example={
     *                      "id": "1",
     *                      "name": "tag post",
     *                   },
     *                )
     *         ),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(description="file to upload", property="pic_small", type="file", format="file"),
     *                 @OA\Property(description="file to upload", property="pic_large", type="file", format="file"),
     *             )
     *         )
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
     *                         "status": "draft",
     *                         "slug": "slug category",
     *                         "description": "description post",
     *                         "meta_description": "meta_description post",
     *                         "meta_keywords": "meta_keywords post",
     *                         "meta_language": "meta_language post",
     *                         "tweet_text": "tweet_text post",
     *                         "tweet_text": "tweet_text post",
     *                         "issue_type": "issue_type post",
     *                         "menu_order": "menu_order post",
     *                         "priority": 133,
     *                         "comment_status": True,
     *                         "menu_status": True,
     *                         "visible_index_status": True,
     *                         "category_id": 1,
     *                         "updated_at": "2023-02-19T07:39:12.000000Z",
     *                         "created_at": "2023-02-19T07:39:12.000000Z",
     *                         "id": "1",
     *                         "tags": {
     *                                "id": "1",
     *                                "name": "tag post",
     *                              }
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
                $this->postService->storePost($input),
                'Post created successfully',
                true,
                200
            );
        } catch (\Exception $exception) {

            return new ApiBlogResponse(null, $exception->getMessage(), false, (int)$exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *      path="/blog-api/post/v1/list",
     *      operationId="list-post",
     *      tags={"Post"},
     *      summary="list Post",
     *      description="list Post",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *            required={"local"},
     *            @OA\Property(property="issue_type", type="string", format="string", example="article"),
     *            @OA\Property(property="local", type="string", format="string", example="en"),
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
     *                            "name": "Post",
     *                            "title": "title Post",
     *                            "slug": "slug Post",
     *                            "description": "description Post",
     *                            "pic_small": "pic_small Post",
     *                            "updated_at": "2023-02-19T07:39:12.000000Z",
     *                            "created_at": "2023-02-19T07:39:12.000000Z",
     *                            "id": "1",
     *                          },
     *                          {
     *                            "name": "Post",
     *                            "title": "title Post",
     *                            "slug": "slug Post",
     *                            "description": "description Post",
     *                            "pic_small": "pic_small Post",
     *                            "updated_at": "2023-02-19T07:39:12.000000Z",
     *                            "created_at": "2023-02-19T07:39:12.000000Z",
     *                            "id": "2",
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
                $this->postService->listPagination(Post::class, $input),
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
     *                         "name": "post name",
     *                         "title": "title post",
     *                         "status": "True",
     *                         "slug": "slug category",
     *                         "description": "description post",
     *                         "meta_description": "meta_description post",
     *                         "meta_keywords": "meta_keywords post",
     *                         "meta_language": "meta_language post",
     *                         "tweet_text": "tweet_text post",
     *                         "tweet_text": "tweet_text post",
     *                         "issue_type": "issue_type post",
     *                         "menu_order": "menu_order post",
     *                         "priority": "133",
     *                         "pic_small": "pic_small post",
     *                         "pic_large": "pic_large post",
     *                         "comment_status": "True",
     *                         "menu_status": "True",
     *                         "visible_index_status": "True",
     *                         "category_id": "1",
     *                         "updated_at": "2023-02-19T07:39:12.000000Z",
     *                         "created_at": "2023-02-19T07:39:12.000000Z",
     *                         "id": "1",
     *                         "tags": {
     *                             "id": "1",
     *                             "name": "tag post",
     *                             }
     *                         },
     *                     ),
     *          )
     *     ),
     *     @OA\Response(response="401", description="Error  edit"),
     *  )
     */
    public function show(PostRequest $request)
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->postService->show($input, Post::class)
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
     *            required={"id", "issue_type","folder_name"},
     *            @OA\Property(property="folder_name", type="string", format="string", example="news"),
     *            @OA\Property(property="name", type="string", format="string", example="Test post name"),
     *            @OA\Property(property="title", type="string", format="string", example="Test Post Title"),
     *            @OA\Property(property="status", type="string", format="string", example="Test Post status"),
     *            @OA\Property(property="slug", type="string", format="string", example="Test Slug status"),
     *            @OA\Property(property="subject", type="string", format="string", example="Test Post Subject"),
     *            @OA\Property(property="description", type="string", format="string", example="Test Post Description"),
     *            @OA\Property(property="meta_description", type="string", format="string", example="Test Post Meta Description"),
     *            @OA\Property(property="meta_keywords", type="string", format="string", example="Test Post Meta Keywords"),
     *            @OA\Property(property="meta_language", type="string", format="string", example="Test Post Meta Language"),
     *            @OA\Property(property="tweet_text", type="string", format="string", example="Test Post Tweet Text"),
     *            @OA\Property(property="issue_type", type="string", format="string", example="Test Post Post Type"),
     *            @OA\Property(property="menu_order", type="integer", format="integer", example="1"),
     *            @OA\Property(property="priority", type="integer", format="integer", example="100"),
     *            @OA\Property(property="comment_status", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="menu_status", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="visible_index_status", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="category_id", type="integer", format="integer", example="1"),
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
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(description="file to upload", property="pic_small", type="file", format="file"),
     *                 @OA\Property(description="file to upload", property="pic_large", type="file", format="file"),
     *             )
     *         )
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
     *                        type="object",
     *                        example="{}"
     *                    ),
     *          )
     *     ),
     *     @OA\Response(response="401", description="Error  edit"),
     *  )
     */
    public function edit(EditRequest $request): ApiBlogResponse
    {

        $input = $request->validated();
        try {

            return new ApiBlogResponse(
                $this->postService->updatePost($input),
                'Post edit successfully',
                true,
                200,

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
    public function softDelete(SoftDeletePost $request): ApiBlogResponse
    {

        $input = $request->validated();

        try {
            return new ApiBlogResponse(
                $this->postService->softDelete($input, Post::class),
                'Post soft delete successfully',
                true,
                200,

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
     *            required={"id","issue_type"},
     *            @OA\Property(property="id", type="integer", format="integer", example="2"),
     *            @OA\Property(property="folder_name", type="string", format="string", example="folderName")
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
     *                         description="Post delete successfully"
     *                     ),
     *                    @OA\Property(
     *                         property="data",
     *                         type="string",
     *                         example="Post delete successfully"
     *                     ),
     *          )
     *     ),
     *     @OA\Response(response="401", description="Error Delete"),
     *  )
     */
    public function delete(DeletePost $request): ApiBlogResponse
    {

        try {
            return new ApiBlogResponse(
                $this->postService->deletePost($request),
                'Post delete successfully',
                true,
                200,
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
    public function restoreDelete(SoftDeletePost $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->postService->restoreDelete($input, Post::class),
                'Post restore successfully',
                true,
                200,
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }
}
