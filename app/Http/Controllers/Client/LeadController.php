<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    /**
     * Display a listing of leads
     */
    public function index(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $query = $client->leads();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by contacted status
        if ($request->filled('contacted')) {
            $query->where('contacted', $request->contacted === 'true');
        }
        
        // Filter by converted status
        if ($request->filled('converted')) {
            $query->where('converted', $request->converted === 'true');
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $leads = $query->paginate(15);
        
        // Statistics
        $stats = [
            'total' => $client->leads()->count(),
            'active' => $client->leads()->active()->count(),
            'inactive' => $client->leads()->inactive()->count(),
            'pending' => $client->leads()->pending()->count(),
            'contacted' => $client->leads()->contacted()->count(),
            'converted' => $client->leads()->converted()->count(),
        ];
        
        return view('client.leads.index', compact('leads', 'stats'));
    }

    /**
     * Show the form for creating a new lead
     */
    public function create()
    {
        return view('client.leads.create');
    }

    /**
     * Store a newly created lead
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'source' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,pending',
            'notes' => 'nullable|string|max:1000',
            'custom_fields' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $client = Auth::guard('client')->user();
        
        $lead = $client->leads()->create($request->all());

        return redirect()->route('client.leads.show', $lead)
            ->with('success', 'Lead criado com sucesso!');
    }

    /**
     * Display the specified lead
     */
    public function show(ClientLead $lead)
    {
        $this->authorize('view', $lead);
        
        return view('client.leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified lead
     */
    public function edit(ClientLead $lead)
    {
        $this->authorize('update', $lead);
        
        return view('client.leads.edit', compact('lead'));
    }

    /**
     * Update the specified lead
     */
    public function update(Request $request, ClientLead $lead)
    {
        $this->authorize('update', $lead);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'source' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,pending',
            'notes' => 'nullable|string|max:1000',
            'contact_notes' => 'nullable|string|max:1000',
            'custom_fields' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $lead->update($request->all());

        return redirect()->route('client.leads.show', $lead)
            ->with('success', 'Lead atualizado com sucesso!');
    }

    /**
     * Remove the specified lead
     */
    public function destroy(ClientLead $lead)
    {
        $this->authorize('delete', $lead);
        
        $lead->delete();

        return redirect()->route('client.leads.index')
            ->with('success', 'Lead removido com sucesso!');
    }

    /**
     * Mark lead as contacted
     */
    public function markAsContacted(Request $request, ClientLead $lead)
    {
        $this->authorize('update', $lead);
        
        $validator = Validator::make($request->all(), [
            'contact_notes' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $lead->markAsContacted($request->contact_notes);

        return redirect()->back()
            ->with('success', 'Lead marcado como contatado!');
    }

    /**
     * Mark lead as converted
     */
    public function markAsConverted(Request $request, ClientLead $lead)
    {
        $this->authorize('update', $lead);
        
        $validator = Validator::make($request->all(), [
            'conversion_value' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $lead->markAsConverted($request->conversion_value);

        return redirect()->back()
            ->with('success', 'Lead marcado como convertido!');
    }

    /**
     * Import leads from CSV
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $client = Auth::guard('client')->user();
        $file = $request->file('file');
        $imported = 0;
        $errors = [];

        try {
            $handle = fopen($file->getPathname(), 'r');
            $header = fgetcsv($handle); // Skip header row

            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) >= 2) { // At least name and email
                    try {
                        $client->leads()->create([
                            'name' => $data[0] ?? 'Unknown',
                            'email' => $data[1] ?? '',
                            'phone' => $data[2] ?? null,
                            'source' => $data[3] ?? 'import',
                            'status' => $data[4] ?? 'pending',
                            'notes' => $data[5] ?? null,
                        ]);
                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "Linha " . ($imported + 1) . ": " . $e->getMessage();
                    }
                }
            }

            fclose($handle);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao importar arquivo: ' . $e->getMessage());
        }

        $message = "{$imported} leads importados com sucesso!";
        if (!empty($errors)) {
            $message .= " Erros: " . implode(', ', $errors);
        }

        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Export leads to CSV
     */
    public function export(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        $query = $client->leads();
        
        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('contacted')) {
            $query->where('contacted', $request->contacted === 'true');
        }
        
        $leads = $query->get();
        
        $filename = 'leads_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($leads) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Nome', 'Email', 'Telefone', 'Origem', 'Status', 
                'Contatado', 'Convertido', 'Valor Conversão', 'Notas', 'Data Criação'
            ]);

            // Data
            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->name,
                    $lead->email,
                    $lead->phone,
                    $lead->source,
                    $lead->status,
                    $lead->contacted ? 'Sim' : 'Não',
                    $lead->converted ? 'Sim' : 'Não',
                    $lead->conversion_value,
                    $lead->notes,
                    $lead->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get leads statistics for dashboard
     */
    public function getStats()
    {
        $client = Auth::guard('client')->user();
        
        return response()->json([
            'total' => $client->leads()->count(),
            'active' => $client->leads()->active()->count(),
            'contacted' => $client->leads()->contacted()->count(),
            'converted' => $client->leads()->converted()->count(),
            'conversion_rate' => $client->leads()->count() > 0 
                ? round(($client->leads()->converted()->count() / $client->leads()->count()) * 100, 2)
                : 0,
        ]);
    }
}
