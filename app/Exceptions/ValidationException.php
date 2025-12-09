<?php
namespace App\Exceptions;

use Exception;

class ValidationException extends Exception {
    protected $errors;

    public function __construct($errors = [], $message = "Validation Error", $code = 400, Exception $previous = null) {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function getErrors() {
        return $this->errors;
    }
}
