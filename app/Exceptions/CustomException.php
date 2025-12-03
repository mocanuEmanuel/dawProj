<?php
namespace App\Exceptions;

use Exception;

class CustomException extends Exception {
    public function errorMessage() {
        return "Error on line " . $this->getLine() . " in " . $this->getFile() . ": <b>" . $this->getMessage() . "</b>";
    }
}
