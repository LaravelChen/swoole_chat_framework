<?php

namespace App\Bll\UserCenter;

use App\Com\Response\FrameWorkCode;
use App\Com\Response\MessageType;
use App\Com\Traits\AbstractControllerTraits;


use App\Bll\IndexController;
use App\Repository\UserCenter\Contracts\UserContract;
use Core\Component\Logger;
use Core\Swoole\Server;
use Illuminate\Support\Arr;

class UserCenterController extends IndexController
{
    use AbstractControllerTraits;

    /**
     * 测试数据
     * @return bool
     */
    public function POSTData()
    {
        $params = request_data();
        return response()->writeJson(200, ['phone' => $params['phone']], 'success');
    }

    /**
     * 登录
     * @return bool
     */
    public function POSTLogin()
    {
        $params = request_data();
        $rule = [
            'type' => 'required|in:account,phone',
            'email' => 'required_without:phone|email',
            'phone' => 'required_without:email|regex:"^\d{11}$"',
            'password' => 'required|min:3|max:15',
        ];
        $valid = $this->com_validate($params, $rule);
        if ( !$valid['is_valid']) {
            Logger::getInstance()->console($valid['errors']);
            return response()->exception(FrameWorkCode::PARAMETER_ERROR);
        }
        #邮箱登录
        if ($params['type'] == 'account') {
            $args = [
                'where' => [
                    'email' => Arr::get($params, 'email', ''),
                ],
            ];
        }
        #手机号码
        if ($params['type'] == 'phone') {
            $args = [
                'where' => [
                    'phone' => Arr::get($params, 'phone', ''),
                ],
            ];
        }
        $user = app(UserContract::class)->getOne($args);
        if ( !is_null($user) && password_verify($params['password'], $user['password'])) {
            $token = sign($user['phone']);
            $user['token'] = $token;
            return response()->success($user);
        }
        return response()->exception(FrameWorkCode::ERROR_LOGIN);
    }

    /**
     * 注册用户
     * @return bool
     */
    public function POSTRegister()
    {
        $params = request_data();
        $rule = [
            'email' => 'required|email',
            'password' => 'required|min:6|max:15',
            'phone' => 'required|regex:"^\d{11}$"',
            'name' => 'required|min:1|max:15',
            'code' => 'required',
        ];
        $valid = $this->com_validate($params, $rule);
        if ( !$valid['is_valid']) {
            Logger::getInstance()->console($valid['errors']);
            return response()->exception(FrameWorkCode::PARAMETER_ERROR);
        }
        $key = MessageType::REGISTER_SEND_CODE . $params['phone'];
        $code = cache()->get($key);
        if ($code != $params['code']) {
            return response()->writeJson(FrameWorkCode::ERROR_CODE);
        }
        #插入用户数据
        $args = [
            'email' => $params['email'],
            'password' => $params['password'],
            'phone' => $params['phone'],
            'name' => $params['name'],
        ];
        $result = app(UserContract::class)->insert($args);
        return $result;
    }

    /**
     * 发送短信验证码
     * @return bool
     */
    public function POSTSendCode()
    {
        $params = request_data();
        $rule = [
            'phone' => 'required',
            'type' => 'required|in:register,login',
        ];
        $valid = $this->com_validate($params, $rule);
        if ( !$valid['is_valid']) {
            Logger::getInstance()->console("参数错误" . $valid['errors']);
            return response()->exception(FrameWorkCode::PARAMETER_ERROR);
        }
        $result = app(UserContract::class)->sendCode($params);
        return $result;
    }

    /**
     * 退出登录
     * @return bool
     */
    public function POSTLogout()
    {
        $params = request_data();
        $rule = [
            'email' => 'required|email',
        ];
        $valid = $this->com_validate($params, $rule);
        if ( !$valid['is_valid']) {
            Logger::getInstance()->console($valid['errors']);
            return response()->exception(FrameWorkCode::PARAMETER_ERROR);
        }
        return response()->success(true);
    }

    /**
     * post请求(文件)
     * @return bool
     */
    public function POSTShowPostFile()
    {
        $params = request()->getUploadedFiles();
        $rule = [
            'file' => 'required',
        ];
        $valid = $this->com_validate($params, $rule);
        if ( !$valid['is_valid']) {
            Logger::getInstance()->console($valid['errors']);
            return response()->exception(FrameWorkCode::PARAMETER_ERROR);
        }
        $file = $params['file'];
        $file->moveTo(ROOT . "/Resource/images/" . $file->getClientFilename());
        response()->success(['title' => "{$file->getSize()}"]);
    }

    /**
     *所有用户的fd
     */
    function POSTConnectionList()
    {
        $list = [];
        foreach (Server::getInstance()->getServer()->connections as $connection) {
            $data = '{
                    "action": "public",
                    "content": {
                        "name": "LaravelChen",
                        "email": "848407695@qq.com",
                        "message": "Hello!",
                        "avatar": "https://photo.laravelchen.cn/avataravatar.jpeg"
                    }
                }';
            Server::getInstance()->getServer()->push($connection, $data);
        }
        $this->response()->writeJson(200, $list, "this is all websocket list");
    }

}