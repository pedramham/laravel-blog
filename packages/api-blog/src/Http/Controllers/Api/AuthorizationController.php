<?php

namespace Admin\ApiBolg\Http\Controllers\Api;

use Admin\ApiBolg\Http\Requests\AuthorizationSystem\LoginRequest;
use Admin\ApiBolg\Http\Requests\AuthorizationSystem\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Admin\ApiBolg\Http\ApiBlogResponse;
use Admin\ApiBolg\Services\Authorization\AuthorizationService;
use OpenApi\Annotations as OA;

class AuthorizationController extends Controller
{

    private AuthorizationService $authorizationService;

    public function __construct(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Laravel OpenApi Demo Documentation",
     *      description="L5 Swagger OpenApi description",
     *      @OA\Contact(
     *          email="admin@admin.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Demo API Server"
     * )
     * @OA\SecurityScheme(
     *     type="http",
     *     description="Login with email and password to get the authentication token",
     *     name="Token based Based",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="apiAuth",
     *     ),
     *
     * @OA\Tag(
     *     name="Blog API",
     *     description="API Endpoints of Projects"
     * )
     */

    public function index()
    {
    }
    /**
     * @OA\Post(
     *     path="/blog-api/auth/v1/register",
     *     tags={"Blog API"},
     *     summary="Register",
     *     description="Register",
     *     operationId="register",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"name", "email", "password"},
     *            @OA\Property(property="name", type="string", format="string", example="admin"),
     *            @OA\Property(property="email", type="string", format="string", example="test@,test.com"),
     *            @OA\Property(property="password", type="string", format="string", example="123456"),
     *         ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="An example resource",
     *          @OA\JsonContent(
     *              type="object",
     *                     @OA\Property(
     *                         property="success",
     *                         type="boolean",
     *                         example="true"
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
     *                         @OA\Items
     *                     ),
     *                     example={
     *                         "name": "admin",
     *                         "email": "test@test.com",
     *                         "updated_at": "2023-02-19T07:39:12.000000Z",
     *                         "created_at": "2023-02-19T07:39:12.000000Z",
     *                         "id": "1",
     *                     }
     *          )
     *     ),
     *     @OA\Response(response="401", description="Error  Unauthorized"),
     * )
     */
    public function createUser(RegisterRequest $request): JsonResponse
    {
        $input = $request->validated();
        try {
            return new ApiBlogResponse(
                $this->authorizationService->register($input)
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }

    }

    /**
     * @OA\Post(
     *     path="/blog-api/auth/v1/login",
     *     tags={"Blog API"},
     *     summary="Login user",
     *     description="user login",
     *     operationId="loginUser",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"email", "password"},
     *            @OA\Property(property="email", type="string", format="string", example="test@,test.com"),
     *            @OA\Property(property="password", type="string", format="string", example="123456"),
     *         ),
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="An example resource",
     *          @OA\JsonContent(
     *              type="object",
     *                     @OA\Property(
     *                         property="success",
     *                         type="boolean",
     *                         example="true"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="The response message"
     *                     ),
     *                     @OA\Property(
     *                         property="data",
     *                         type="string",
     *                         example="LWHIXc57VJ7rONJOMORO5D9jvJ84h1p1yrUhbBR2"
     *                     )
     *          )
     *     ),
     *     @OA\Response(response="401", description="Error  Unauthorized"),
     * )
     */
    public function loginUser(LoginRequest $request): JsonResponse
    {

        $input = $request->validated();
        $user = User::where('email', $input['email'])->first();

        try {
            return new ApiBlogResponse(
                $user->createToken("API_TOKEN_BOLG")->plainTextToken,
                'token'
            );
        } catch (\Exception $exception) {
            return new ApiBlogResponse(null, $exception->getMessage(), false, $exception->getCode());
        }

    }
}
