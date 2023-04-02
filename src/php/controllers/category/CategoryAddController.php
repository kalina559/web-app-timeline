<?php
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../categories/CategoriesService.php';

class CategoryAddController extends BaseController
{
    function execute()
    {
        $name = new ControllerInputField('Name');
        $colorHex = new ControllerInputField('ColorHex');

        $categoryService = new CategoriesService();
        $categoryService->addCategory($name->get(), $colorHex->get());
    }
}

$controller = new CategoryAddController(requiresArguments: true, validateUserLoggedIn: true);