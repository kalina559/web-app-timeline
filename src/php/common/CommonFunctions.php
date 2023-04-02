<?php
include __DIR__.'/../../../config.php';

#region Logging to a file
enum LogModes
{
    case Debug;
    case Info;
    case Error;
}

function log_message($type, $message)
{
    $now = new DateTime();
    $timestamp = $now->format('Y-m-d H:i:s');
    $date = $now->format('Y-m-d');

    $fileDir = dirname(__FILE__);
    $logMessage = "$timestamp $type $message" . PHP_EOL;

    file_put_contents("$fileDir/../logs/log_$date.log", $logMessage, FILE_APPEND);
}
#endregion

#region Database operations
function getDbConnection()
{
    return mysqli_connect(DBHOST, DBUSER, DBPWD, DBNAME);
}

function executeQuery($con, $query)
{
    try {
        $con->query("SET NAMES 'utf8'");
        $smtp = $con->prepare("$query");
        $smtp->execute();

        $result = $smtp->get_result();
        return $result;
    } catch (Exception $e) {
        log_message(LogModes::Error->name, $e->getMessage());
    } catch (Error $e) {
        log_message(LogModes::Error->name, $e->getMessage());
    }
}

function executeQueryWithParams($con, $query, $paramTypes, ...$params)
{
    try {
        $con->query("SET NAMES 'utf8'");
        $smtp = $con->prepare("$query");
        $smtp->bind_param($paramTypes, ...$params);   // binding params to prevent sql injection
        $smtp->execute();

        $result = $smtp->get_result();
        return $result;
    } catch (Exception $e) {
        log_message(LogModes::Error->name, $e->getMessage());
    } catch (Error $e) {
        log_message(LogModes::Error->name, $e->getMessage());
    }
}
#endregion