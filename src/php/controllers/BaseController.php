<?php
include __DIR__ . '/../account/AccountService.php';
include_once __DIR__ . '/ControllerInputField.php';
include_once __DIR__ . '/../exceptions/InvalidCredentialsException.php';

abstract class BaseController
{
    private $service;
    protected $response;
    protected $output = array();


    function __construct($requiresArguments = false, $validateUserLoggedIn = false)
    {
        session_start();

        $className = get_class($this);
        log_message(LogModes::Info->name, "Creating $className");

        $this->service = new AccountService();

        if ($requiresArguments && !isset($_POST['arguments'])) {
            throw new Exception("No request arguments were provided.");
        }

        header('Content-Type: application/json');
        try {

            if ($validateUserLoggedIn && !$this->service->userIsLoggedIn()) {
                throw new InvalidCredentialsException("User is not logged in.");
            }
            $this->execute();
            echo json_encode($this->response);
            http_response_code(200);
        } catch (Exception $e) {
            log_message(LogModes::Error->name, "{$e->getMessage()}: {$e->getTraceAsString()}");

            $output = array();
            $output['errorMessage'] = $e->getMessage();
            $output['stackTrace'] = $e->getTraceAsString();

            echo json_encode($output);
            http_response_code(403);
        } catch (Exception $e) {
            log_message(LogModes::Error->name, "{$e->getMessage()}: {$e->getTraceAsString()}");

            $output = array();
            $output['errorMessage'] = $e->getMessage();
            $output['stackTrace'] = $e->getTraceAsString();

            echo json_encode($output);
            http_response_code(500);
        }
    }

    function __destruct()
    {
        $className = get_class($this);
        log_message(LogModes::Info->name, "Deleting $className");
        unset($this->service);
    }

    abstract function execute();
}
