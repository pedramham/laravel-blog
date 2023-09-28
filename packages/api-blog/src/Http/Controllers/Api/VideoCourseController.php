<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Http\ApiBlogResponse;
use Admin\ApiBolg\Http\Requests\VideoCourse\DeleteVideoCourse;
use Admin\ApiBolg\Http\Requests\VideoCourse\EditRequest;
use Admin\ApiBolg\Http\Requests\VideoCourse\ListRequest;
use Admin\ApiBolg\Http\Requests\VideoCourse\ShowRequest;
use Admin\ApiBolg\Http\Requests\VideoCourse\SoftDeleteVideoCourse;
use Admin\ApiBolg\Http\Requests\VideoCourse\StoreRequest;
use Admin\ApiBolg\Models\VideoCategory;
use Admin\ApiBolg\Models\VideoCourse;
use Admin\ApiBolg\Services\VideoCourseService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Log;
use Symfony\Component\HttpFoundation\Response;

class VideoCourseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private VideoCourseService $videoCourseService;

    public function __construct(VideoCourseService $videoCourseService)
    {
        $this->videoCourseService = $videoCourseService;
    }

    /**
     * @OA\Post(
     *      path="/blog-api/video-course/v1/list",
     *      operationId="list-video-course",
     *      tags={"VideoCourse"},
     *      summary="list Video Course",
     *      description="list Video Course",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *            required={"local"},
     *            @OA\Property(property="status", type="string", format="string", example="publish"),
     *            @OA\Property(property="local", type="string", format="string", example="en"),
     *            @OA\Property(property="list_trash", type="string", format="string", example="list_trash"),
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
     *                            "price": "100",
     *                            "sell_count": "1000",
     *                            "updated_at": "2023-02-19T07:39:12.000000Z",
     *                            "created_at": "2023-02-19T07:39:12.000000Z",
     *                            "id": "1",
     *                          },
     *                          {
     *                            "name": "Post",
     *                            "title": "title Post",
     *                            "slug": "slug Post",
     *                            "subject": "subject Post",
     *                            "price": "100",
     *                            "sell_count": "1000",
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
                $this->videoCourseService->listPagination(VideoCourse::class, $input)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *      path="/blog-api/video-course/v1/store",
     *      operationId="store-video-course",
     *      tags={"VideoCourse"},
     *      summary="Store viedo course in DB",
     *      description="Store video course in DB",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(description="Course name", property="name", type="string"),
     *                 @OA\Property(description="Course title", property="title", type="string"),
     *                 @OA\Property(description="Course slug", property="slug", type="string"),
     *                 @OA\Property(description="Course status", property="status", type="string", example="draft"),
     *                 @OA\Property(description="Folder Name", property="folder_name", type="string", example="videoCourse"),
     *                 @OA\Property(description="Categry video Id", property="category_video_id", type="integer", example="1"),
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
     *                         "name": "course video name",
     *                         "title": "course video title",
     *                         "status": "True",
     *                         "slug": "slug category",
     *                         "description": "description video course",
     *                         "meta_description": "meta_description video course",
     *                         "meta_keywords": "meta_keywords video course",
     *                         "price": "80000",
     *                         "discount": "2500",
     *                         "is_free": "true",
     *                         "sell_count": "50000",
     *                         "priority": "1",
     *                         "pic_small": "pic_small video course",
     *                         "pic_large": "pic_large video course",
     *                         "menu_status": True,
     *                         "visible_index_status": True,
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
                $this->videoCourseService->storeVideoCourse($input),
                'Video Course created successfully',
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {

            return new ApiBlogResponse(null, $exception->getMessage(), false, (int)$exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *      path="/blog-api/video-course/v1/show",
     *      operationId="show-video-course",
     *      tags={"VideoCourse"},
     *      summary="Store video course in DB",
     *      description="Store video course in DB",
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
     *                         "name": "video course name",
     *                         "title": "video course post",
     *                         "status": "True",
     *                         "slug": "slug video course",
     *                         "description": "description video course",
     *                         "meta_description": "meta_description video course",
     *                         "meta_keywords": "meta_keywords video course",
     *                         "meta_language": "meta_language video course",
     *                         "price": "80000",
     *                         "discount": "2500",
     *                         "is_free": "true",
     *                         "sell_count": "50000",
     *                         "menu_order": "menu_order video course",
     *                         "priority": "133",
     *                         "pic_small": "pic_small video course",
     *                         "pic_large": "pic_large video course",
     *                         "menu_status": "True",
     *                         "visible_index_status": "True",
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
    public function show(ShowRequest $request): ApiBlogResponse
    {

        try {
            return new ApiBlogResponse(
                $this->videoCourseService->show($request->validated(), VideoCourse::class)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Put(
     *      path="/blog-api/video-course/v1/edit",
     *      operationId="edit-video-course",
     *      tags={"VideoCourse"},
     *      summary="Edit video course in DB",
     *      description="Edit video course in DB",
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
     *            @OA\Property(property="menu_order", type="integer", format="integer", example="1"),
     *            @OA\Property(property="priority", type="integer", format="integer", example="1"),
     *            @OA\Property(property="price", type="float", format="float", example="200"),
     *            @OA\Property(property="discount", type="float", format="float", example="2"),
     *            @OA\Property(property="sell_count", type="float", format="float", example="500"),
     *            @OA\Property(property="is_free", type="boolean", format="boolean", example="false"),
     *            @OA\Property(property="menu_status", type="boolean", format="boolean", example="true"),
     *            @OA\Property(property="visible_index_status", type="boolean", format="boolean", example="true"),
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
                $this->videoCourseService->updateVideoCourse($input),
                'Video Course update successfully',
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }


    /**
     * @OA\Delete(
     *      path="/blog-api/video-course/v1/soft-delete",
     *      operationId="soft-delete-video-course",
     *      tags={"VideoCourse"},
     *      summary="Soft-delete video course in DB",
     *      description="Soft-delete video course in DB",
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
    public function softDelete(SoftDeleteVideoCourse $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->videoCourseService->softDelete($input, VideoCourse::class),
                'Video Course Soft delete successfully',
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Delete(
     *      path="/blog-api/video-course/v1/delete",
     *      operationId="delete-video-course",
     *      tags={"VideoCourse"},
     *      summary="delete video course in DB",
     *      description="delete video course in DB",
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
    public function delete(DeleteVideoCourse $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->videoCourseService->deleteVideoCourse($input),
                'Video Course delete successfully',
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Put(
     *      path="/blog-api/video-course/v1/restore-delete",
     *      operationId="restore-delete-video-course",
     *      tags={"VideoCourse"},
     *      summary="restore delete video course in DB",
     *      description="when you delete video course in DB you can restore it",
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
    public function restoreDelete(SoftDeleteVideoCourse $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->videoCourseService->restoreDelete($input, VideoCourse::class),
                'Video course restore successfully',
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }
}
