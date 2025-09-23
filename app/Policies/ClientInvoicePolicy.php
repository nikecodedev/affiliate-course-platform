<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\ClientInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientInvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the client can view the invoice.
     */
    public function view(Client $client, ClientInvoice $invoice)
    {
        return $client->id === $invoice->client_id;
    }

    /**
     * Determine whether the client can update the invoice.
     */
    public function update(Client $client, ClientInvoice $invoice)
    {
        return $client->id === $invoice->client_id;
    }
}
