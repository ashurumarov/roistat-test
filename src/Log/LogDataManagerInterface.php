<?php

namespace src\Log;

interface LogDataManagerInterface
{
    public function loadData();
    public function executeParams(string $line);
}