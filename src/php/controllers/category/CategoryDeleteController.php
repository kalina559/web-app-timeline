<?php
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../categories/CategoriesService.php';

class CategoryDeleteController extends BaseController
{
    function execute()
    {
        $id = new ControllerInputField('Id');

        $categoryService = new CategoriesService();

        $categoryService->deleteCategory($id->get());
    }
}

$controller = new CategoryDeleteController(requiresArguments: true, validateUserLoggedIn: true);
