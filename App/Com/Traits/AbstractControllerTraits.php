<?php

namespace App\Com\Traits;

trait AbstractControllerTraits
{
    function index()
    {
        // TODO: Implement index() method.
    }

    function onRequest($actionName)
    {
        // TODO: Implement onRequest() method.
    }

    function actionNotFound($actionName = null, $arguments = null)
    {
        return $this->View('errors.404');
        // TODO: Implement actionNotFound() method.
    }

    function afterAction()
    {
        // TODO: Implement afterAction() method.
    }
}