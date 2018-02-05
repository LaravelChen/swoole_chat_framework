<?php

namespace App\Com\Response;

class FrameWorkCode
{
    public function range()
    {
        return [1, 999];
    }

    const  FLAG_SUCCESS = 'success';
    const  FLAG_NOTICE = 'notice';
    const FLAG_FAIL = 'fail';

    const ERROR_TOKEN = ['code' => 1000, 'message' => 'Token Miss!', 'flag' => self::FLAG_NOTICE];  #未登录
    const PARAMETER_ERROR = ['code' => 1101, 'message' => '参数错误', 'flag' => self::FLAG_NOTICE];
    const PARAMETER_ERROR_WITH_MESSAGE = ['code' => 1101, 'message' => '#{message}', 'flag' => self::FLAG_NOTICE];
    const NOT_FOUND = ['code' => 1102, 'message' => '查询不到相关数据', 'flag' => self::FLAG_NOTICE];
    const SYSTEM_BUSY = ['code' => 1103, 'message' => '系统繁忙', 'flag' => self::FLAG_NOTICE];
    const NO_AUTH = ['code' => 1104, 'message' => '无权限访问', 'flag' => self::FLAG_NOTICE];
    const EXISTS_DATA = ['code' => 1105, 'message' => '数据已存在', 'flag' => self::FLAG_NOTICE];
    const ADD_ERROR = ['code' => 1106, 'message' => '新增失败', 'flag' => self::FLAG_NOTICE];
    const UPDATE_ERROR = ['code' => 1107, 'message' => '更新失败', 'flag' => self::FLAG_NOTICE];
    const NOT_REPEAT = ['code' => 1108, 'message' => '请勿频繁操作', 'flag' => self::FLAG_NOTICE];
    const DELETE_ERROR = ['code' => 1109, 'message' => '删除失败', 'flag' => self::FLAG_NOTICE];
    const SYSTEM_ERROR = ['code' => 1110, 'message' => '系统错误', 'flag' => self::FLAG_NOTICE];
    const STOP_C5 = ['code' => 1111, 'message' => '此功能暂时关闭，请到122系统录入学员信息。', 'flag' => self::FLAG_NOTICE];
    const NOT_EXISTS_DATA = ['code' => 1112, 'message' => '数据不存在', 'flag' => self::FLAG_NOTICE];
    const NOT_EXISTS_DATA_WITH_MESSAGE = ['code' => 1112, 'message' => '#{message}', 'flag' => self::FLAG_NOTICE];
    const UPDATE_DATE_EXIST = ['code' => 1113, 'message' => '更新数据已存在', 'flag' => self::FLAG_NOTICE];
    const STATUS_EXCEPTION = ['code' => 1114, 'message' => '状态异常', 'flag' => self::FLAG_NOTICE];
    const ERROR_WITH_MESSAGE = ['code' => 1115, 'message' => '#{message}', 'flag' => self::FLAG_NOTICE];
    const ERROR_LOGIN = ['code' => 1116, 'message' => '账号与密码不匹配!', 'flag' => self::FLAG_NOTICE];
    const ERROR_SIGN = ['code' => 1117, 'message' => '签名不匹配!', 'flag' => self::FLAG_NOTICE];
    const ERROR_CODE = ['code' => 1118, 'message' => '验证码不正确!', 'flag' => self::FLAG_NOTICE];
}