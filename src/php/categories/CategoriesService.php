<?php
include 'CategoriesRepository.php';

class CategoriesService
{  
    private $repository;

    function __construct() {
        $this->repository = new CategoriesRepository();
    }

    function __destruct() {
        unset($this->repository);
    }

    public function getCategories()
    {
        return $this->repository->getCategories();
    }

    public function addCategory($name, $colorHex)
    {
        return $this->repository->addCategory($name, $colorHex);
    }

    public function editCategory($id, $name, $colorHex)
    {
        return $this->repository->editCategory($id, $name, $colorHex);
    }

    public function deleteCategory($id)
    {
        return $this->repository->deleteCategory($id);
    }
}
