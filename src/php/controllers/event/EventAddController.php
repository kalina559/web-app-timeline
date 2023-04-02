<?php
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../event/EventService.php';

class EventAddController extends BaseController
{
    function execute()
    {
        $name = new ControllerInputField('Name');
        $title = new ControllerInputField('Title');
        $description = new ControllerInputField('Description');
        $startDate = new ControllerInputField('StartDate');
        $endDate = new ControllerInputField('EndDate');
        $categoryId = new ControllerInputField('CategoryId');
        $imageFile = new ControllerInputField('ImageFile');

        $eventService = new EventService();
        
        $eventService->addEvent($name->get(), $title->get(), $description->get(), $startDate->get(), $endDate->get(), $categoryId->get(), $imageFile->get());
    }
}

$controller = new EventAddController(requiresArguments: true, validateUserLoggedIn: true);
