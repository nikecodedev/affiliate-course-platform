<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SupportController extends Controller
{
    /**
     * Show support dashboard
     */
    public function index()
    {
        $client = Auth::guard('client')->user();
        
        // Get client's tickets
        $tickets = $client->supportTickets()
            ->latest()
            ->paginate(15);
        
        // Calculate average response time (in hours)
        $avgResponseTime = $client->supportTickets()
            ->where('status', 'closed')
            ->whereNotNull('resolved_at')
            ->get()
            ->map(function ($ticket) {
                return $ticket->created_at->diffInHours($ticket->resolved_at);
            })
            ->avg();
        
        return view('client.support.index', compact('tickets', 'client', 'avgResponseTime'));
    }

    /**
     * Show create ticket form
     */
    public function create()
    {
        return view('client.support.create');
    }

    /**
     * Store new ticket
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|string|max:100',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $client = Auth::guard('client')->user();
        
        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support-attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                ];
            }
        }
        
        // Create ticket
        $ticket = $client->supportTickets()->create([
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
            'attachments' => $attachments,
            'status' => 'open',
        ]);

        return redirect()->route('client.support.ticket.show', $ticket)
            ->with('success', 'Ticket criado com sucesso! Número: ' . $ticket->ticket_number);
    }

    /**
     * Show specific ticket
     */
    public function show(SupportTicket $ticket)
    {
        $client = Auth::guard('client')->user();
        
        // Check if client owns this ticket
        if ($ticket->client_id !== $client->id) {
            abort(403, 'Você não tem permissão para visualizar este ticket.');
        }
        
        // Load ticket with responses
        $ticket->load(['responses' => function($query) {
            $query->orderBy('created_at');
        }]);
        
        return view('client.support.ticket-show', compact('ticket'));
    }

    /**
     * Store response to ticket
     */
    public function storeResponse(Request $request, SupportTicket $ticket)
    {
        $client = Auth::guard('client')->user();
        
        // Check if client owns this ticket
        if ($ticket->client_id !== $client->id) {
            abort(403, 'Você não tem permissão para responder este ticket.');
        }
        
        // Check if ticket is closed
        if ($ticket->status === 'closed') {
            return redirect()->back()
                ->with('error', 'Este ticket está fechado e não aceita novas respostas.');
        }
        
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:2000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('support-attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                ];
            }
        }
        
        // Create response
        $ticket->responses()->create([
            'client_id' => $client->id,
            'message' => $request->message,
            'attachments' => $attachments,
            'is_internal' => false,
        ]);
        
        // Update ticket status if it was pending
        if ($ticket->status === 'pending') {
            $ticket->update(['status' => 'open']);
        }

        return redirect()->back()
            ->with('success', 'Resposta enviada com sucesso!');
    }

    /**
     * Close ticket
     */
    public function closeTicket(SupportTicket $ticket)
    {
        $client = Auth::guard('client')->user();
        
        // Check if client owns this ticket
        if ($ticket->client_id !== $client->id) {
            abort(403, 'Você não tem permissão para fechar este ticket.');
        }
        
        if ($ticket->status === 'closed') {
            return redirect()->back()
                ->with('error', 'Este ticket já está fechado.');
        }
        
        $ticket->close();

        return redirect()->back()
            ->with('success', 'Ticket fechado com sucesso!');
    }

    /**
     * Show FAQ page
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'Como faço para solicitar um saque?',
                'answer' => 'Acesse a área Financeiro > Saques e preencha o formulário com seus dados bancários. Você precisará confirmar os últimos 4 dígitos do seu CPF para segurança.',
            ],
            [
                'question' => 'Qual é o prazo para processamento de saques?',
                'answer' => 'Os saques são processados em até 5 dias úteis após a aprovação. Você receberá uma notificação por email quando o saque for aprovado.',
            ],
            [
                'question' => 'Como acompanho meus leads?',
                'answer' => 'Na área de Leads, você pode visualizar todos os seus leads, filtrar por status, marcar como contatados e acompanhar conversões.',
            ],
            [
                'question' => 'Como acesso os cursos de treinamento?',
                'answer' => 'Você precisa ter uma fatura ativa para acessar os cursos. Acesse a área de Treinamentos após ativar seu plano.',
            ],
            [
                'question' => 'Como configuro minhas tags de rastreamento?',
                'answer' => 'No seu perfil, vá em "Tags de Rastreamento" e configure Facebook Pixel, Google Tag Manager, Google Analytics e códigos personalizados.',
            ],
            [
                'question' => 'Posso editar meu CPF ou email?',
                'answer' => 'Por motivos de segurança, CPF e email não podem ser alterados após o cadastro. Entre em contato conosco se houver necessidade de alteração.',
            ],
            [
                'question' => 'Como faço para importar uma lista de leads?',
                'answer' => 'Na área de Leads, clique em "Importar" e selecione um arquivo CSV com as colunas: Nome, Email, Telefone, Origem, Status, Notas.',
            ],
            [
                'question' => 'Onde encontro meus sites de captura?',
                'answer' => 'No Dashboard, você encontrará os links dos seus sites de captura que já incluem suas tags de rastreamento configuradas.',
            ],
        ];

        return view('client.support.faq', compact('faqs'));
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(SupportTicket $ticket, $attachmentIndex)
    {
        $client = Auth::guard('client')->user();
        
        // Check if client owns this ticket
        if ($ticket->client_id !== $client->id) {
            abort(403, 'Você não tem permissão para baixar este arquivo.');
        }
        
        $attachments = $ticket->attachments ?? [];
        
        if (!isset($attachments[$attachmentIndex])) {
            abort(404, 'Arquivo não encontrado.');
        }
        
        $attachment = $attachments[$attachmentIndex];
        $filePath = storage_path('app/public/' . $attachment['path']);
        
        if (!file_exists($filePath)) {
            abort(404, 'Arquivo não encontrado no servidor.');
        }
        
        return response()->download($filePath, $attachment['name']);
    }

    /**
     * Get ticket statistics
     */
    public function getStats()
    {
        $client = Auth::guard('client')->user();
        
        return response()->json([
            'total_tickets' => $client->supportTickets()->count(),
            'open_tickets' => $client->supportTickets()->open()->count(),
            'resolved_tickets' => $client->supportTickets()->closed()->count(),
            'average_response_time' => $this->getAverageResponseTime($client),
        ]);
    }

    /**
     * Calculate average response time
     */
    private function getAverageResponseTime($client)
    {
        $tickets = $client->supportTickets()->whereNotNull('resolved_at')->get();
        
        if ($tickets->isEmpty()) {
            return 0;
        }
        
        $totalHours = 0;
        foreach ($tickets as $ticket) {
            $totalHours += $ticket->created_at->diffInHours($ticket->resolved_at);
        }
        
        return round($totalHours / $tickets->count(), 1);
    }
}
