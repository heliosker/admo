<?php
/**
 *
 * User: bing <codingbing@163.com>
 * Date: 2023/5/22 23:38
 */

namespace App\Http\Controllers\API;


use App\Models\AdminUser;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Contracts\Auth\StatefulGuard;

class AuthenticationController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * User sign in logic
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sign(Request $request): JsonResponse
    {
        $credentials = $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => '请输入您的账户',
            'password.required' => '请输入您的密码',
        ]);

        $credentials['email'] = Arr::pull($credentials, 'username');

        $provider = Auth::getProvider();
        /**
         * 根据用户凭证获取用户信息
         *
         * @var AdminUser $user
         */
        $user = $provider->retrieveByCredentials($credentials);
        if (is_null($user)) {
            return error('用户不存在', 404);
        }

        if ($provider->validateCredentials($user, $credentials) == false) {
            return error('认证失败，用户名或密码不正确', 401);
        }

        try {
            if (!$token = $this->guard()->login($user)) {
                return error('认证失败', 401);
            }

        } catch (\Exception $exception) {
            return error('未知错误导致登录失败');

        }

        return result([
            'id' => $user->getAuthIdentifier(),
            'name' => $user->name,
            'email' => $user->email,
            'token_type' => 'Bearer',
            'access_token' => $token,
            'expires_in' => config('jwt.ttl') * 24 * 7, // 7days
        ]);
    }

    /**
     * User sign out logic
     *
     * @return JsonResponse
     */
    public function out(): JsonResponse
    {
        $this->guard()->logout();
        return result([], '注销成功');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return Guard|StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
