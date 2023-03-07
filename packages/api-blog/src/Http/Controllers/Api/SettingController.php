<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Http\ApiBlogResponse;
use Admin\ApiBolg\Http\Requests\Setting\StoreRequest;
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
     *            required={"app_logo", "app_name"},
     *            @OA\Property(property="app_name", type="string", format="string", example="Test app_name"),
     *            @OA\Property(property="app_logo", type="string", format="string", example="Test app_logo"),
     *            @OA\Property(property="app_favicon", type="string", format="string", example="Test app_favicon"),
     *            @OA\Property(property="app_description", type="string", format="string", example="Test app_description"),
     *            @OA\Property(property="app_short_description", type="string", format="string", example="Test app_short_description"),
     *            @OA\Property(property="app_keywords", type="string", format="string", example="Test app_keywords"),
     *            @OA\Property(property="address", type="string", format="string", example="Test address"),
     *            @OA\Property(property="phone", type="string", format="string", example="Test phone"),
     *            @OA\Property(property="email", type="string", format="string", example="Test email"),
     *            @OA\Property(property="mobile", type="string", format="string", example="Test mobile"),
     *            @OA\Property(property="fax", type="string", format="string", example="Test fax"),
     *            @OA\Property(property="telegram", type="string", format="string", example="Test telegram"),
     *            @OA\Property(property="whatsapp", type="string", format="string", example="Test whatsapp"),
     *            @OA\Property(property="facebook", type="string", format="string", example="Test facebook"),
     *            @OA\Property(property="twitter", type="string", format="string", example="Test twitter"),
     *            @OA\Property(property="instagram", type="string", format="string", example="Test instagram"),
     *            @OA\Property(property="linkedin", type="string", format="string", example="Test linkedin"),
     *            @OA\Property(property="youtube", type="string", format="string", example="Test youtube"),
     *            @OA\Property(property="pinterest", type="string", format="string", example="Test pinterest"),
     *            @OA\Property(property="github", type="string", format="string", example="Test github"),
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

}
