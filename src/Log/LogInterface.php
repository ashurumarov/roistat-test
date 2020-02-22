<?php


namespace src\Log;


interface LogInterface
{
    public function load($filePath);
    public function data();
}