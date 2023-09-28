<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Http\ApiBlogResponse;
use Admin\ApiBolg\Http\Requests\Category\CategoryRequest;
use Admin\ApiBolg\Http\Requests\Setting\ShowRequest;
use Admin\ApiBolg\Http\Requests\Setting\StoreRequest;
use Admin\ApiBolg\Models\Category;
use Admin\ApiBolg\Models\Setting;
use Admin\ApiBolg\Services\SettingService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class SettingController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }


    /**
     * @OA\Post(
     *      path="/blog-api/setting/v1/store",
     *      operationId="storeSetting",
     *      tags={"Setting"},
     *      summary="Store setting in DB",
     *      description="Store setting in DB",
     *      security={{ "apiAuth": {} }},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"app_name","local"},
     *            @OA\Property(property="app_name", type="string", format="string", example="name your app"),
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
     *                               "app_logo": "setting app_logo",
     *                               "app_name": "setting app_name",
     *                               "app_favicon": "setting app_favicon",
     *                               "app_description": "setting app_description",
     *                               "app_short_description": "setting app_short_description",
     *                               "app_keywords": "setting app_keywords",
     *                               "address": "setting address",
     *                               "phone": "setting phone",
     *                               "email": "setting email",
     *                               "mobile": "setting mobile",
     *                               "fax": "setting fax",
     *                               "telegram": "setting telegram",
     *                               "whatsapp": "setting whatsapp",
     *                               "facebook": "setting facebook",
     *                               "twitter": "setting twitter",
     *                               "instagram": "setting instagram",
     *                               "linkedin": "setting linkedin",
     *                               "youtube": "setting youtube",
     *                               "pinterest": "setting pinterest",
     *                               "github": "setting github",
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
                $this->settingService->storeSetting($input),
                'Setting created successfully',
                true,
                200
            );
        } catch (\Exception $exception) {

            return new ApiBlogResponse(null, $exception->getMessage(), false, (int)$exception->getCode());
        }
    }



    /**
     * @OA\Post (
     *      path="/blog-api/setting/v1/show",
     *      operationId="showSetting",
     *      tags={"Setting"},
     *      summary="show a setting in DB",
     *      description="get setting in DB",
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
     *                         "app_name": "App Name",
     *                         "app_logo": "Logo",
     *                         "app_favicon": "Favicon",
     *                         "app_description": "Description",
     *                         "app_short_description": "Short Description",
     *                         "app_keywords": "Keywords",
     *                         "address": "address",
     *                         "phone": "Phone",
     *                         "email": "Email",
     *                         "mobile": "Mobile",
     *                         "fax": "Fax",
     *                         "telegram": "Telegram",
     *                         "whatsapp": "Whatsapp",
     *                         "facebook": "Facebook",
     *                         "twitter": "Twitter",
     *                         "instagram": "Instagram",
     *                         "linkedin": "Linkedin",
     *                         "Youtube": "youtube",
     *                         "Pinterest": "pinterest",
     *                         "Github": "github",
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
                $this->settingService->show($request->validated(), Setting::class)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }
    }

}
