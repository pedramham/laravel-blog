<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Http\ApiBlogResponse;
use Admin\ApiBolg\Http\Requests\Category\DeleteRequest;
use Admin\ApiBolg\Http\Requests\Category\EditRequest;
use Admin\ApiBolg\Http\Requests\Category\CategoryRequest;
use Admin\ApiBolg\Http\Requests\Category\ListRequest;
use Admin\ApiBolg\Http\Requests\Category\SoftDeleteRequest;
use Admin\ApiBolg\Http\Requests\Category\StoreRequest;
use Admin\ApiBolg\Models\Category;
use Admin\ApiBolg\Services\CategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Log;

class CategoryController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @OA\Post(
     *      path="/blog-api/category/v1/list",
     *      operationId="list",
     *      tags={"Category"},
     *      summary="list Category",
     *      description="list Category",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *            required={"local"},
     *            @OA\Property(property="status", type="string", format="string", example="publish"),
     *            @OA\Property(property="issue_type", type="string", format="string", example="article"),
     *            @OA\Property(property="parent_id", type="int", example="1"),
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
     *                            "subject": "subject Post",
     *                            "pic_small": "pic_small Post",
     *                            "updated_at": "2023-02-19T07:39:12.000000Z",
     *                            "created_at": "2023-02-19T07:39:12.000000Z",
     *                            "id": "1",
     *                          },
     *                          {
     *                            "name": "Post",
     *                            "title": "title Post",
     *                            "slug": "slug Post",
     *                            "subject": "subject Post",
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
                $this->categoryService->listPagination(Category::class, $input)
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
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(description="Category name", property="name", type="string"),
     *                 @OA\Property(description="Category type", property="issue_type", type="string", example="news"),
     *                 @OA\Property(description="Folder Name", property="folder_name", type="string", example="news"),
     *                 @OA\Property(description="Category slug", property="slug", type="string"),
     *                 @OA\Property(description="Category status", property="status", type="string", example="draft"),
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
     *                         type="Create new Category",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items,
     *                         example={
     *                         "name": "category name",
     *                         "title": "category post",
     *                         "status": "True",
     *                         "slug": "slug category",
     *                         "description": "description post",
     *                         "meta_description": "meta_description post",
     *                         "meta_keywords": "meta_keywords post",
     *                         "meta_language": "meta_language post",
     *                         "tweet_text": "tweet_text post",
     *                         "tweet_text": "tweet_text post",
     *                         "issue_type": "type",
     *                         "menu_order": 1,
     *                         "priority": "133",
     *                         "pic_small": "pic_small post",
     *                         "pic_large": "pic_large post",
     *                         "menu_status": True,
     *                         "visible_index_status": True,
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
                $this->categoryService->storeCategory($input),
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
     *                         "name": "category name",
     *                         "title": "category post",
     *                         "status": "True",
     *                         "slug": "slug category",
     *                         "description": "description post",
     *                         "meta_description": "meta_description post",
     *                         "meta_keywords": "meta_keywords post",
     *                         "meta_language": "meta_language post",
     *                         "tweet_text": "tweet_text post",
     *                         "tweet_text": "tweet_text post",
     *                         "issue_type": "article",
     *                         "menu_order": "menu_order category",
     *                         "priority": "133",
     *                         "pic_small": "pic_small post",
     *                         "pic_large": "pic_large post",
     *                         "menu_status": "True",
     *                         "visible_index_status": "True",
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
    public function show(CategoryRequest $request)
    {
        try {
            return new ApiBlogResponse(
                $this->categoryService->show($request->validated(), Category::class)
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
     *            required={"id","issue_type","folder_name"},
     *            @OA\Property(property="id", type="integer", format="integer", example="2"),
     *            @OA\Property(property="folder_name", type="string", format="string", example="news"),
     *            @OA\Property(property="name", type="string", format="string", example="Test Category Name"),
     *            @OA\Property(property="title", type="string", format="string", example="Test Article Title"),
     *            @OA\Property(property="slug", type="string", format="string", example="test-article-title"),
     *            @OA\Property(property="subject", type="string", format="string", example="Test Article Subject"),
     *            @OA\Property(property="description", type="string", format="string", example="Test Article Description"),
     *            @OA\Property(property="meta_description", type="string", format="string", example="Test Article Meta Description"),
     *            @OA\Property(property="meta_keywords", type="string", format="string", example="Test Article Meta Keywords"),
     *            @OA\Property(property="meta_language", type="string", format="string", example="Test Article Meta Language"),
     *            @OA\Property(property="tweet_text", type="string", format="string", example="Test Article Tweet Text"),
     *            @OA\Property(property="issue_type", type="string", format="string", example="article"),
     *            @OA\Property(property="menu_order", type="integer", format="integer", example="1"),
     *            @OA\Property(property="priority", type="integer", format="integer", example="1"),
     *            @OA\Property(property="menu_status", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="visible_index_status", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="parent_id", type="integer", format="integer", example="1"),
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
    public function edit(EditRequest $request): ApiBlogResponse
    {
        $input = $request->validated();

        try {
            return new ApiBlogResponse(
                $this->categoryService->updateCategory($input),
                'Category update successfully',
                true,
                200
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
    public function softDelete(SoftDeleteRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->categoryService->softDelete($input, Category::class),
                'Category Soft delete successfully',
                true,
                200
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
     *            required={"id","issue_type","folder_name"},
     *            @OA\Property(property="id", type="integer", format="integer", example="2"),
     *            @OA\Property(property="issue_type", type="string", format="string", example="name category"),
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
    public function delete(DeleteRequest $request): ApiBlogResponse
    {
        $input = $request->validated();

        try {
            return new ApiBlogResponse(
                $this->categoryService->deleteCategory($input),
                'Category delete successfully',
                true,
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
    public function restoreDelete(SoftDeleteRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->categoryService->restoreDelete($input, Category::class),
                'Category restore successfully',
                true,
                200
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }
}
