<?php
include 'EventRepository.php';

class EventService
{
    private $repository;

    function __construct() {
        $this->repository = new EventRepository();
    }

    function __destruct() {
        unset($this->repository);
    }

    public function getEvents()
    {
        return $this->repository->getEvents();
    }

    public function addEvent($name, $title, $description, $startDate, $endDate, $categoryId, $imageFile)
    {
        $this->repository->addEvent($name, $title, $description, $startDate, $endDate, $categoryId, $imageFile);
    }

    public function editEvent($name, $id, $title, $description, $startDate, $endDate, $categoryId, $imageFile)
    {
        $this->repository->editEvent($name, $id, $title, $description, $startDate, $endDate, $categoryId, $imageFile);
    }

    public function deleteEvent($id)
    {
        $this->repository->deleteEvent($id);
    }
}
