<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\ClientLead;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientLeadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the client can view the lead.
     */
    public function view(Client $client, ClientLead $lead)
    {
        return $client->id === $lead->client_id;
    }

    /**
     * Determine whether the client can update the lead.
     */
    public function update(Client $client, ClientLead $lead)
    {
        return $client->id === $lead->client_id;
    }

    /**
     * Determine whether the client can delete the lead.
     */
    public function delete(Client $client, ClientLead $lead)
    {
        return $client->id === $lead->client_id;
    }
}
