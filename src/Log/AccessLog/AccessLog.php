<?php

namespace src\Log\AccessLog;


use src\Log\LogDataManagerInterface;
use src\Log\LogInterface;

class AccessLog implements LogInterface
{

    /**
     * @var LogDataManagerInterface
     */
    private $dataManager;
    /**
     * @var $data
     */
    private $data;
    /**
     * @var $errors
     */
    private $haveErrors;

    /**
     * AccessLog constructor.
     * @param LogDataManagerInterface $dataManager
     */
    public function __construct(LogDataManagerInterface $dataManager)
    {
        $this->dataManager = $dataManager;
    }

    /**
     * @param $filePath
     * @throws \Exception
     */
    public function load($filePath)
    {
        foreach($this->readLog($filePath) as $line) {
            try {
                $this->dataManager->executeParams($line);
            } catch (\Exception $e) {
                if(!$this->haveErrors) {
                    $this->haveErrors = true;
                }
            }
        }

        if($this->haveErrors) {
            echo "Не вся статистика была загружена\n";
        }

        $this->data = $this->dataManager->loadData();
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param string $filePath
     * @return \Generator
     * @throws \Exception
     */
    private function readLog(string $filePath) : \Generator
    {
        if(!file_exists($filePath)) {
            throw new \Exception('File not found');
        }

        $accessLog = fopen($filePath, 'r');

        while(!feof($accessLog)) {
            yield fgets($accessLog);
        }
    }

}
