<?php

namespace App\Features\Shipment\Exception;

use Exception;

class SaveShipmentException extends Exception
{
    public function __construct($message = "Shipment or events save failed.")
    {
        parent::__construct($message);
    }
}