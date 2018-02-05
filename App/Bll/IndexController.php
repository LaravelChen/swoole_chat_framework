<?php

namespace App\Bll;


use App\Com\Traits\ValidatorTrait;
use App\Vendor\Blade\Blade;
use Core\AbstractInterface\AbstractREST;

abstract class IndexController extends AbstractREST
{
    use ValidatorTrait;

    function View($tplName, $tplData = [])
    {
        $viewTemplate = Blade::getInstance()->render($tplName, $tplData);
        $this->response()->write($viewTemplate);
    }
}