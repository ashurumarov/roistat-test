<?php


namespace src\Log\Analyzer;

use src\Log\LogAnalyzerInterface;
use src\Log\LogInterface;

class LogAnalyzer implements LogAnalyzerInterface
{
    /**
     * @var LogInterface
     */
    private $logger;

    /**
     * LogAnalyzer constructor.
     * @param LogInterface $logger
     */
    public function __construct(LogInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $filePath
     * @return $this
     */
    public function loadLog(string $filePath)
    {
        $this->logger->load($filePath);
        return $this;
    }

    /**
     * @return null|string
     */
    public function toJson()
    {
        return json_encode($this->logger->data());
    }

}