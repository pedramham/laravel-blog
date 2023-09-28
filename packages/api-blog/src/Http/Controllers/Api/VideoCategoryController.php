<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Http\ApiBlogResponse;
use Admin\ApiBolg\Http\Requests\VideoCategory\DeleteVideoCategory;
use Admin\ApiBolg\Http\Requests\VideoCategory\EditRequest;
use Admin\ApiBolg\Http\Requests\VideoCategory\CategoryRequest;
use Admin\ApiBolg\Http\Requests\VideoCategory\ListRequest;
use Admin\ApiBolg\Http\Requests\VideoCategory\StoreRequest;
use Admin\ApiBolg\Models\Category;
use Admin\ApiBolg\Models\VideoCategory;
use Admin\ApiBolg\Services\CategoryService;
use Admin\ApiBolg\Services\VideoCategoryService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Log;
use Symfony\Component\HttpFoundation\Response;

class VideoCategoryController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private VideoCategoryService $videoCategoryService;

    public function __construct(VideoCategoryService $videoCategoryService)
    {
        $this->videoCategoryService = $videoCategoryService;
    }

    /**
     * @OA\Post(
     *      path="/blog-api/video-category/v1/list",
     *      operationId="list-video-category",
     *      tags={"VideoCategory"},
     *      summary="list Video Category",
     *      description="list Video Category",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *            required={"local"},
     *            @OA\Property(property="status", type="string", format="string", example="publish"),
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
                $this->videoCategoryService->listPagination(VideoCategory::class, $input)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *      path="/blog-api/video-category/v1/store",
     *      operationId="store-video-category",
     *      tags={"VideoCategory"},
     *      summary="Store viedo category in DB",
     *      description="Store video category in DB",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(description="Category name", property="name", type="string"),
     *                 @OA\Property(description="Category slug", property="slug", type="string"),
     *                 @OA\Property(description="Category status", property="status", type="string", example="draft"),
     *                 @OA\Property(description="Folder Name", property="folder_name", type="string", example="news"),

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
     *                         type="Create new Video Category",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         description="The response data",
     *                         @OA\Items,
     *                         example={
     *                         "name": "category video name",
     *                         "title": "category video title",
     *                         "status": "True",
     *                         "slug": "slug category",
     *                         "description": "description video category",
     *                         "meta_description": "meta_description video category",
     *                         "meta_keywords": "meta_keywords video category",
     *                         "meta_language": "meta_language video category",
     *                         "tweet_text": "tweet_text video category",
     *                         "tweet_text": "tweet_text video category",
     *                         "menu_order": 1,
     *                         "priority": "133",
     *                         "pic_small": "pic_small video category",
     *                         "pic_large": "pic_large video category",
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
                $this->videoCategoryService->storeCategory($input),
                'Video Category created successfully',
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {

            return new ApiBlogResponse(null, $exception->getMessage(), false, (int)$exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *      path="/blog-api/video-category/v1/show",
     *      operationId="show-video-category",
     *      tags={"VideoCategory"},
     *      summary="Store video category in DB",
     *      description="Store video category in DB",
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
     *                         "name": "video category name",
     *                         "title": "video category post",
     *                         "status": "True",
     *                         "slug": "slug video category",
     *                         "description": "description video category",
     *                         "meta_description": "meta_description video category post",
     *                         "meta_keywords": "meta_keywords video category post",
     *                         "meta_language": "meta_language video categoy post",
     *                         "tweet_text": "tweet_text video category",
     *                         "tweet_text": "tweet_text video category",
     *                         "issue_type": "article",
     *                         "menu_order": "menu_order video category",
     *                         "priority": "133",
     *                         "pic_small": "pic_small video category",
     *                         "pic_large": "pic_large video category",
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
    public function show(CategoryRequest $request): ApiBlogResponse
    {
        try {
            return new ApiBlogResponse(
                $this->videoCategoryService->show($request->validated(), VideoCategory::class)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Put(
     *      path="/blog-api/video-category/v1/edit",
     *      operationId="edit-video-category",
     *      tags={"VideoCategory"},
     *      summary="Edit video category in DB",
     *      description="Edit video category in DB",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"id","folder_name"},
     *            @OA\Property(property="id", type="integer", format="integer", example="2"),
     *            @OA\Property(property="folder_name", type="string", format="string", example="video"),
     *            @OA\Property(property="name", type="string", format="string", example="Test video Category Name"),
     *            @OA\Property(property="title", type="string", format="string", example="Test video Title"),
     *            @OA\Property(property="slug", type="string", format="string", example="test-video-title"),
     *            @OA\Property(property="subject", type="string", format="string", example="Test video Subject"),
     *            @OA\Property(property="description", type="string", format="string", example="Test video Description"),
     *            @OA\Property(property="meta_description", type="string", format="string", example="Test video Meta Description"),
     *            @OA\Property(property="meta_keywords", type="string", format="string", example="Test video Meta Keywords"),
     *            @OA\Property(property="meta_language", type="string", format="string", example="Test video Meta Language"),
     *            @OA\Property(property="tweet_text", type="string", format="string", example="Test video Tweet Text"),
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
     *                         "name": "video category",
     *                         "title": "title video category",
     *                         "slug": "slug video category",
     *                         "description": "description v category",
     *                         "pic_small": "pic_small video category",
     *                         "pic_large": "pic_large video category",
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
                $this->videoCategoryService->updateVideoCategory($input),
                'Category ٰVideo update successfully',
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }


    /**
     * @OA\Delete(
     *      path="/blog-api/video-category/v1/soft-delete",
     *      operationId="soft-delete-video-category",
     *      tags={"VideoCategory"},
     *      summary="Soft-delete video category in DB",
     *      description="Soft-delete video category in DB",
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
    public function softDelete(CategoryRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->videoCategoryService->softDelete($input, VideoCategory::class),
                'Video Category Soft delete successfully',
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Delete(
     *      path="/blog-api/video-category/v1/delete",
     *      operationId="delete-video-category",
     *      tags={"VideoCategory"},
     *      summary="delete video category in DB",
     *      description="delete video category in DB",
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"id"},
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
    public function delete(DeleteVideoCategory $request): ApiBlogResponse
    {

        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->videoCategoryService->deleteCategory($input),
                'Video Category delete successfully',
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Put(
     *      path="/blog-api/video-category/v1/restore-delete",
     *      operationId="restore-delete-video-category",
     *      tags={"VideoCategory"},
     *      summary="restore delete video category in DB",
     *      description="when you delete video category in DB you can restore it",
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
     *     @OA\Response(response="401", description="Error video category delete"),
     *  )
     */
    public function restoreDelete(CategoryRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->videoCategoryService->restoreDelete($input, VideoCategory::class),
                'Video Category restore successfully',
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }
}
