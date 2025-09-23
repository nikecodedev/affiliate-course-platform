<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\ClientWithdrawalRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientWithdrawalRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the client can view the withdrawal request.
     */
    public function view(Client $client, ClientWithdrawalRequest $withdrawalRequest)
    {
        return $client->id === $withdrawalRequest->client_id;
    }

    /**
     * Determine whether the client can update the withdrawal request.
     */
    public function update(Client $client, ClientWithdrawalRequest $withdrawalRequest)
    {
        return $client->id === $withdrawalRequest->client_id && 
               $withdrawalRequest->status === 'pending';
    }
}
