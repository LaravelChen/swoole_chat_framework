<?php

namespace App\Com\Response;


class MessageType
{
    #发送短信的redis的prefix
    const  REGISTER_SEND_CODE = 'register_send_code_';
    const  LOGIN_SEND_CODE = 'login_send_code_';


    #发送短信的模板
    const SEND_MESSAGE_TEMPLATE='SMS_121905108';
}