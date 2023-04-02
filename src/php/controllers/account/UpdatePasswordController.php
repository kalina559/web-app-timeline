<?php
include __DIR__ . '/../BaseController.php';

class UpdatePasswordController extends BaseController
{
    function execute()
    {
        $oldPassword = new ControllerInputField('OldPassword');
        $newPassword = new ControllerInputField('NewPassword');

        $accountService = new AccountService();
        
        $result =  $accountService->updateUsersPassword($oldPassword->get(), $newPassword->get());
        $this->response = $result;
    }
}

$controller = new UpdatePasswordController(requiresArguments: true, validateUserLoggedIn: true);
