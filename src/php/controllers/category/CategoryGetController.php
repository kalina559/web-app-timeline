<?php
include __DIR__.'/../BaseController.php';
include __DIR__.'/../../categories/CategoriesService.php';

class CategoryGetController extends BaseController
{
    function execute()
    {        
        $categoryService = new CategoriesService();
        $this->response = $categoryService->getCategories();
    }
}

$controller = new CategoryGetController();
