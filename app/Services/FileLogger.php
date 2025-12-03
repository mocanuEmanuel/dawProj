<?php
namespace App\Services;

use App\Interfaces\LoggableInterface;

class FileLogger implements LoggableInterface {
    private $logFile;

    public function __construct($filename = 'app.log') {
        $this->logFile = __DIR__ . '/../../' . $filename;
    }

    public function log(string $message): void {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($this->logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
    }
}
