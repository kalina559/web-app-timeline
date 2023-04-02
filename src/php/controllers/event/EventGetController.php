<?php
include __DIR__.'/../BaseController.php';
include __DIR__.'/../../event/EventService.php';

class EventGetController extends BaseController
{
    function execute()
    {
        $eventService = new EventService();
        $this->response = $eventService->getEvents();
    }
}

$controller = new EventGetController();

