<?php


namespace src\Log;


interface LogAnalyzerInterface
{
    public function loadLog(string $filePath);
    public function toJson();
}