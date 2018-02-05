<?php

namespace App\Com\Traits;



use App\Vendor\Validators\Validator;

trait ValidatorTrait
{
    public function com_validate(array $data, array $rules, array $messages = [])
    {
        $validator = Validator::getInstance()->make($data, $rules, $messages);

        if ($validator->fails()) {

            return [
                'is_valid'  => false,
                'errors'    => $validator->errors(),
            ];
        }

        return [
            'is_valid'  => true,
            'errors'    => []
        ];
    }
}