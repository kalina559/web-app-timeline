<?php
include_once __DIR__.'/common/CommonFunctions.php';

class BaseRepository
{
    protected $con;

    function __construct() {
        $className = get_class($this);
        log_message(LogModes::Info->name, "Creating $className");
        $this->con = getDbConnection();
    }

    function __destruct() {
        $className = get_class($this);
        log_message(LogModes::Info->name, "Deleting $className");
        $this->con->close();
        unset($this->con);
    }
}
