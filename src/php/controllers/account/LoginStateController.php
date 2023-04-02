<?php
include __DIR__.'/../BaseController.php';

class LoginStateController extends BaseController
{
    function execute()
    {
        $accountService = new AccountService();

        $result =  $accountService->getLoggedInUser();
        $this->response = $result;
    }
}

$controller = new LoginStateController();