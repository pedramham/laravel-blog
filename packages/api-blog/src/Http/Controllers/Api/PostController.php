<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Http\ApiBlogResponse;
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

}
