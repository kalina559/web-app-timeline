<?php
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../event/EventService.php';

class EventDeleteController extends BaseController
{
    function execute()
    {
        $eventService = new EventService();

        $id = new ControllerInputField('Id');
        
        $eventService->deleteEvent($id->get());
    }
}

$controller = new EventDeleteController(requiresArguments: true, validateUserLoggedIn: true);
