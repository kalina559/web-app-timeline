<?php
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../event/EventService.php';

class EventUpdateController extends BaseController
{
    function execute()
    {
        $eventService = new EventService();

        $id = new ControllerInputField('Id');
        $name = new ControllerInputField('Name');
        $title = new ControllerInputField('Title');
        $description = new ControllerInputField('Description');
        $startDate = new ControllerInputField('StartDate');
        $endDate = new ControllerInputField('EndDate');
        $categoryId = new ControllerInputField('CategoryId');
        $imageFile = new ControllerInputField('ImageFile');

        $eventService->editEvent($id->get(), $name->get(), $title->get(), $description->get(), $startDate->get(), $endDate->get(), $categoryId->get(), $imageFile->get());
    }
}

$controller = new EventUpdateController(requiresArguments: true, validateUserLoggedIn: true);
