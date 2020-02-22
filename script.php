<?
require_once "vendor/autoload.php";

use src\Log\AccessLog\AccessLogDataManager;
use src\Log\AccessLog\AccessLog;
use src\Log\Analyzer\LogAnalyzer;   

$logManager = new AccessLogDataManager();
$accessLogger = new AccessLog($logManager);
$logAnalyzer = new LogAnalyzer($accessLogger);

if($_SERVER['argv']) {
    echo $logAnalyzer->loadLog($_SERVER['argv'][1])->toJson();
    echo "\n";
}