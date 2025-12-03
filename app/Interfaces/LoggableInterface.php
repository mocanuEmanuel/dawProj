<?php
namespace App\Interfaces;

interface LoggableInterface {
    public function log(string $message): void;
}
