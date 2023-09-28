<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Helper\FileHelper;
use Admin\ApiBolg\Http\ApiBlogResponse;
use Admin\ApiBolg\Http\Requests\Video\DeleteVideo;
use Admin\ApiBolg\Http\Requests\Video\EditRequest;
use Admin\ApiBolg\Http\Requests\Video\ListRequest;
use Admin\ApiBolg\Http\Requests\Video\VideoRequest;
use Admin\ApiBolg\Http\Requests\Video\SoftDeleteVideo;
use Admin\ApiBolg\Http\Requests\Video\StoreRequest;
use Admin\ApiBolg\Models\Post;
use Admin\ApiBolg\Models\Video;
use Admin\ApiBolg\Services\VideoService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Log;
use Symfony\Component\HttpFoundation\Response;

class VideoController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private VideoService $videoService;

    public function __construct(VideoService $videoService, FileHelper $fileService)
    {
        $this->videoService = $videoService;
    }


    /**
     * @OA\Post(
     *      path="/blog-api/video/v1/store",
     *      operationId="storeVideo",
     *      tags={"Post"},
     *      summary="Store video in DB",
     *      description="Store video in DB",
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
     *            @OA\Property(property="duration", type="string", format="string", example="3:40"),
     *            @OA\Property(property="video_url", type="string", format="string", example="https://www.example.com/1.jpg"),
     *            @OA\Property(property="video_file_type", type="string", format="string", example="mp4"),
     *            @OA\Property(property="video_number", type="string", format="string", example="Video33#r"),
     *            @OA\Property(property="priority", type="integer", format="integer", example="100"),
     *            @OA\Property(property="video_course_id", type="integer", format="integer", example="1"),
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
     *                 @OA\Property(description="file to upload", property="thumbnail", type="file", format="file"),
     *                 @OA\Property(description="file to upload", property="video_file", type="file", format="file"),
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
     *                         "priority": 133,
     *                         "video_url": "https://www.example.comc/video_url.jpg",
     *                         "video_file": "video_file.mp4",
     *                         "video_file_type": "mp4",
     *                         "video_number": "video3334",
     *                         "video_course_id": 1,
     *                         "updated_at": "2023-02-19T07:39:12.000000Z",
     *                         "created_at": "2023-02-19T07:39:12.000000Z",
     *                         "id": "1",
     *                         "tags": {
     *                                "id": "1",
     *                                "name": "tag Video",
     *                              }
     *                         },
     *                     ),
     *          )
     *     ),
     *     @OA\Response(response="401", description="Create a Video post"),
     *  )
     */
    public function store(StoreRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->videoService->storeVideo($input),
                'Video created successfully',
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {

            return new ApiBlogResponse(null, $exception->getMessage(), false, (int)$exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *      path="/blog-api/video/v1/list",
     *      operationId="list-video",
     *      tags={"Video"},
     *      summary="list Video",
     *      description="list Video",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         @OA\JsonContent(
     *            required={"local"},
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
     *                         example="200"
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
     *                            "name": "Video",
     *                            "title": "title Video",
     *                            "slug": "slug Video",
     *                            "description": "description Post",
     *                            "thumbnail": "thumbnail video",
     *                            "updated_at": "2023-02-19T07:39:12.000000Z",
     *                            "created_at": "2023-02-19T07:39:12.000000Z",
     *                            "id": "1",
     *                          },
     *                          {
     *                            "name": "Video",
     *                            "title": "title Video",
     *                            "slug": "slug Videi",
     *                            "description": "description Post",
     *                            "thumbnail": "thumbnail video",
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
                $this->videoService->listPagination(Video::class, $input),
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Post(
     *      path="/blog-api/video/v1/show",
     *      operationId="show-video",
     *      tags={"Post"},
     *      summary="Store video in DB",
     *      description="Store video in DB",
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
     *                         example="200"
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
     *                         "name": "vidoe name",
     *                         "title": "video post",
     *                         "status": "Publish",
     *                         "slug": "slug-video",
     *                         "description": "description video",
     *                         "meta_description": "meta_description video",
     *                         "meta_keywords": "meta_keywords video",
     *                         "priority": "133",
     *                         "thumbnail": "thumbnail.jpg",
     *                         "video_url": "https:wwww.example.com/video.mp4",
     *                         "video_file_type": "mp4",
     *                         "video_number": "video#ygvv",
     *                         "video_course_id": "1",
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
    public function show(VideoRequest $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->videoService->show($input, Video::class)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Put(
     *      path="/blog-api/video/v1/edit",
     *      operationId="edit-video",
     *      tags={"Video"},
     *      summary="Edit video in DB",
     *      description="Edit video in DB",
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
     *            @OA\Property(property="duration", type="string", format="string", example="3:40"),
     *            @OA\Property(property="video_url", type="string", format="string", example="www.fff.mp4"),
     *            @OA\Property(property="video_file_type", type="string", format="string", example="mp4"),
     *            @OA\Property(property="video_number", type="string", format="string", example="#video33"),
     *            @OA\Property(property="priority", type="integer", format="integer", example="100"),
     *            @OA\Property(property="video_course_id", type="integer", format="integer", example="1"),
     *            @OA\Property(
     *               property="tags",
     *               type="array",
     *                  @OA\Items,
     *                     example={
     *                     "id": "1",
     *                     "name": "tag video",
     *                   },
     *                )
     *           ),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(description="file to upload", property="thumbnail", type="file", format="file"),
     *                 @OA\Property(description="file to upload", property="video_file", type="file", format="file"),
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
                $this->videoService->updateVideo($input),
                'Post edit successfully',
                true,
                Response::HTTP_OK,

            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }


    /**
     * @OA\Delete(
     *      path="/blog-api/vidoe/v1/soft-delete",
     *      operationId="soft-delete-video",
     *      tags={"Video"},
     *      summary="Soft-delete video in DB",
     *      description="Soft-delete video in DB",
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
    public function softDelete(SoftDeleteVideo $request): ApiBlogResponse
    {

        $input = $request->validated();

        try {
            return new ApiBlogResponse(
                $this->videoService->softDelete($input, Video::class),
                'Video soft delete successfully',
                true,
                Response::HTTP_OK,

            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Delete(
     *      path="/blog-api/video/v1/delete",
     *      operationId="delete-video",
     *      tags={"Video"},
     *      summary="delete video in DB",
     *      description="delete video in DB",
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
     *                         description="Video delete successfully"
     *                     ),
     *                    @OA\Property(
     *                         property="data",
     *                         type="string",
     *                         example="Video delete successfully"
     *                     ),
     *          )
     *     ),
     *     @OA\Response(response="401", description="Error Delete"),
     *  )
     */
    public function delete(DeleteVideo $request): ApiBlogResponse
    {

        try {
            return new ApiBlogResponse(
                $this->videoService->deleteVideo($request),
                'Video delete successfully',
                true,
                Response::HTTP_OK,
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

    /**
     * @OA\Put(
     *      path="/blog-api/video/v1/restore-delete",
     *      operationId="restore-delete-video",
     *      tags={"Video"},
     *      summary="restore delete video in DB",
     *      description="when you delete video in DB you can restore it",
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
    public function restoreDelete(SoftDeleteVideo $request): ApiBlogResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->videoService->restoreDelete($input, Video::class),
                'Video restore successfully',
                true,
                Response::HTTP_OK,
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }
}
