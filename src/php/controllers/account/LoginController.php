<?php
include __DIR__ . '/../BaseController.php';

class LoginController extends BaseController
{
    function execute()
    {
        $login = new ControllerInputField('Login');
        $password = new ControllerInputField('Password');

        $accountService = new AccountService();        

        $success =  $accountService->login($login->get(), $password->get());
        if ($success !== TRUE) {
            throw new Exception('User credentials are wrong!');
        }
    }
}

$controller = new LoginController(requiresArguments: true);
