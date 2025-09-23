<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\ClientInvoice;
use App\Models\ClientLead;
use App\Models\ClientTransaction;
use App\Models\Plan;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample clients
        $clients = [
            [
                'name' => 'João Silva',
                'email' => 'joao@example.com',
                'password' => Hash::make('password123'),
                'cpf' => '12345678901',
                'phone' => '(11) 99999-9999',
                'address' => 'Rua das Flores, 123',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01234-567',
                'country' => 'Brasil',
                'birth_date' => '1990-01-15',
                'gender' => 'male',
                'facebook_pixel_id' => '123456789012345',
                'google_tag_manager_id' => 'GTM-XXXXXXX',
                'google_analytics_id' => 'GA-XXXXXXXXX',
                'is_active' => true,
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'password' => Hash::make('password123'),
                'cpf' => '98765432109',
                'phone' => '(11) 88888-8888',
                'address' => 'Avenida Paulista, 456',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01310-100',
                'country' => 'Brasil',
                'birth_date' => '1985-05-20',
                'gender' => 'female',
                'facebook_pixel_id' => '987654321098765',
                'is_active' => true,
            ],
        ];

        foreach ($clients as $clientData) {
            $client = Client::create($clientData);

            // Create active invoice for first client
            if ($client->email === 'joao@example.com') {
                $plan = Plan::first();
                if ($plan) {
                    $invoice = ClientInvoice::create([
                        'client_id' => $client->id,
                        'plan_id' => $plan->id,
                        'invoice_number' => 'INV-' . strtoupper(uniqid()),
                        'amount' => $plan->price,
                        'status' => 'active',
                        'payment_method' => 'pix',
                        'payment_reference' => 'PIX-' . strtoupper(uniqid()),
                        'paid_at' => now(),
                        'expires_at' => now()->addDays(30),
                        'notes' => 'Pagamento via PIX',
                    ]);

                    // Create sample transactions
                    ClientTransaction::create([
                        'client_id' => $client->id,
                        'invoice_id' => $invoice->id,
                        'type' => 'credit',
                        'amount' => 1000.00,
                        'description' => 'Comissão de vendas - Janeiro',
                        'status' => 'completed',
                        'reference' => 'COM-' . strtoupper(uniqid()),
                        'payment_method' => 'commission',
                        'processed_at' => now()->subDays(5),
                    ]);

                    ClientTransaction::create([
                        'client_id' => $client->id,
                        'type' => 'credit',
                        'amount' => 750.00,
                        'description' => 'Comissão de vendas - Fevereiro',
                        'status' => 'completed',
                        'reference' => 'COM-' . strtoupper(uniqid()),
                        'payment_method' => 'commission',
                        'processed_at' => now()->subDays(2),
                    ]);

                    ClientTransaction::create([
                        'client_id' => $client->id,
                        'type' => 'debit',
                        'amount' => 500.00,
                        'description' => 'Saque processado',
                        'status' => 'completed',
                        'reference' => 'SAQ-' . strtoupper(uniqid()),
                        'payment_method' => 'bank_transfer',
                        'processed_at' => now()->subDays(1),
                    ]);
                }
            }

            // Create sample leads
            $leads = [
                [
                    'name' => 'Carlos Oliveira',
                    'email' => 'carlos@email.com',
                    'phone' => '(11) 77777-7777',
                    'source' => 'website',
                    'status' => 'active',
                    'contacted' => true,
                    'contacted_at' => now()->subDays(3),
                    'contact_notes' => 'Cliente interessado no produto premium',
                ],
                [
                    'name' => 'Ana Costa',
                    'email' => 'ana@email.com',
                    'phone' => '(11) 66666-6666',
                    'source' => 'facebook',
                    'status' => 'pending',
                    'contacted' => false,
                ],
                [
                    'name' => 'Pedro Lima',
                    'email' => 'pedro@email.com',
                    'phone' => '(11) 55555-5555',
                    'source' => 'instagram',
                    'status' => 'active',
                    'contacted' => true,
                    'contacted_at' => now()->subDays(1),
                    'converted' => true,
                    'converted_at' => now()->subHours(12),
                    'conversion_value' => 299.00,
                ],
            ];

            foreach ($leads as $leadData) {
                ClientLead::create(array_merge($leadData, ['client_id' => $client->id]));
            }
        }

        $this->command->info('Client data seeded successfully!');
    }
}
