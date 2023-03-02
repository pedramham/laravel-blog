<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Http\ApiBlogResponse;
use Admin\ApiBolg\Http\Requests\Category\EditRequest;
use Admin\ApiBolg\Http\Requests\Category\ShowRequest;
use Admin\ApiBolg\Http\Requests\Category\StoreRequest;
use Admin\ApiBolg\Models\Category;
use Admin\ApiBolg\Services\CategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @OA\Get(
     *      path="/blog-api/category/v1/list",
     *      operationId="list",
     *      tags={"Category"},
     *      summary="list Category",
     *      description="list Category",
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
     *                         "name": "category",
     *                         "title": "title category",
     *                         "slug": "slug category",
     *                         "description": "description category",
     *                         "pic_small": "pic_small category",
     *                         "pic_large": "pic_large category",
     *                         "parent_id": "1",
     *                         "updated_at": "2023-02-19T07:39:12.000000Z",
     *                         "created_at": "2023-02-19T07:39:12.000000Z",
     *                         "id": "1",
     *                          },
     *                          {
     *                          "name": "category",
     *                         "title": "title category",
     *                         "slug": "slug category",
     *                         "description": "description category",
     *                         "pic_small": "pic_small category",
     *                         "pic_large": "pic_large category",
     *                         "parent_id": "1",
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
                $this->categoryService->list(Category::class)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *      path="/blog-api/category/v1/store",
     *      operationId="store",
     *      tags={"Category"},
     *      summary="Store category in DB",
     *      description="Store category in DB",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"title", "slug"},
     *            @OA\Property(property="title", type="string", format="string", example="Test Article Title"),
     *            @OA\Property(property="slug", type="string", format="string", example="test-article-title"),
     *            @OA\Property(property="subject", type="string", format="string", example="Test Article Subject"),
     *            @OA\Property(property="description", type="string", format="string", example="Test Article Description"),
     *            @OA\Property(property="pic-small", type="string", format="string", example="Test Article Pic Small"),
     *            @OA\Property(property="pic-large", type="string", format="string", example="Test Article Pic Large"),
     *            @OA\Property(property="parent_id", type="integer", format="integer", example="1"),
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
     *                         type="Create new Category",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items,
     *                         example={
     *                         "name": "category",
     *                         "title": "title category",
     *                         "slug": "slug category",
     *                         "description": "description category",
     *                         "pic_small": "pic_small category",
     *                         "pic_large": "pic_large category",
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
    public function store(StoreRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->categoryService->store($input,Category::class),
                'Category created successfully',
                true,
                200
            );
        } catch (\Exception $exception) {

            return new ApiBlogResponse(null, $exception->getMessage(), false, (int)$exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *      path="/blog-api/category/v1/show",
     *      operationId="show",
     *      tags={"Category"},
     *      summary="Store category in DB",
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
     *                         "name": "category",
     *                         "title": "title category",
     *                         "slug": "slug category",
     *                         "description": "description category",
     *                         "pic_small": "pic_small category",
     *                         "pic_large": "pic_large category",
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
                $this->categoryService->show($input,Category::class)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Put(
     *      path="/blog-api/category/v1/edit",
     *      operationId="edit",
     *      tags={"Category"},
     *      summary="Edit category in DB",
     *      description="Edit category in DB",
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
     *            @OA\Property(property="parent_id", type="integer", format="integer", example="1"),
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
     *                         "name": "category",
     *                         "title": "title category",
     *                         "slug": "slug category",
     *                         "description": "description category",
     *                         "pic_small": "pic_small category",
     *                         "pic_large": "pic_large category",
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
                $this->categoryService->edit($input,Category::class)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }


    /**
     * @OA\Delete(
     *      path="/blog-api/category/v1/soft-delete",
     *      operationId="soft-delete-category",
     *      tags={"Category"},
     *      summary="Soft-delete category in DB",
     *      description="Soft-delete category in DB",
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
     *     @OA\Response(response="401", description="Error soft delete"),
     *  )
     */
    public function softDelete(ShowRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->categoryService->softDelete($input,Category::class)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Delete(
     *      path="/blog-api/category/v1/delete",
     *      operationId="delete-category",
     *      tags={"Category"},
     *      summary="delete category in DB",
     *      description="delete category in DB",
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
                $this->categoryService->delete($input,Category::class),
                200
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Put(
     *      path="/blog-api/category/v1/restore-delete",
     *      operationId="restore-delete-category",
     *      tags={"Category"},
     *      summary="restore delete category in DB",
     *      description="when you delete category in DB you can restore it",
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
     *     @OA\Response(response="401", description="Error category delete"),
     *  )
     */
    public function restoreDelete(ShowRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->categoryService->restoreDelete($input,Category::class),
                200
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }
}
