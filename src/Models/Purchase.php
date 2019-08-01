<?php

namespace OntraportAPI\Models;

use OntraportAPI\Exceptions as Exceptions;

class Transaction
{
    /**
     * Transaction constructor
     *
     * @param int $contact_id The id of the contact this transaction is for.
     * @param int $charge_now Binary integer flag indicating if this transaction should be charged now or logged
     * @param int $gateway_id The id of the gateway this transaction is going through.
     *
     * @throws Exceptions\OntraportAPIException No gateway id provided for charge now
     *
     */
    public function __construct()
    {
    }

}
