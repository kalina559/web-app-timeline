<?php
include __DIR__ . '/../BaseController.php';

class LogoutController extends BaseController
{
    function execute()
    {
        $accountService = new AccountService();

        $accountService->logout();
    }
}

$controller = new LogoutController();
