<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pharmacien;
use App\Http\Controllers\Controller;
use PDF; 
use Carbon\Carbon;
use App\Models\Commande;
use App\Models\Medicament;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Vente;
use App\Models\order; 
use App\Models\ligneOrder;
use App\Models\Stock;

class PharmacienController extends Controller
{
    /**
     * Handle the pharmacien licence form submission.
     */
    public function submitLicence(Request $request)
    {
        $request->validate([
            'licence' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Incorrect password. Please try again.')->withInput();
        }

        // Find or create the Pharmacien record
        $pharmacien = Pharmacien::firstOrCreate(
            ['user_id' => $user->id],
            ['licence' => $request->licence]
        );

        // If already exists, update the licence
        if ($pharmacien->licence !== $request->licence) {
            $pharmacien->licence = $request->licence;
            $pharmacien->save();
        }

        // Redirect to dashboard on success
        return redirect()->route('dashboard')->with('success', 'Licence information saved successfully.');
    }

    /**
     * Display the commandes of commandes.
     */
    public function commandes(Request $request)
    {
        $orderQuery = order::query();

        if ($request->filled('search_id')) {
            $orderQuery->where('id', $request->input('search_id'));
        }
        if ($request->filled('search_status')) {
            $orderQuery->where('status', strtolower($request->input('search_status')));
        }
        if ($request->filled('search_date')) {
            $date = $request->input('search_date');
            $dates = explode(' - ', $date);
            if (count($dates) === 2) {
                try {
                    $start = Carbon::createFromFormat('d M, Y', trim($dates[0]))->format('Y-m-d');
                    $end = Carbon::createFromFormat('d M, Y', trim($dates[1]))->format('Y-m-d');
                    $orderQuery->whereDate('creation_time', '>=', $start)
                               ->whereDate('creation_time', '<=', $end);
                } catch (\Exception $e) {}
            } else {
                try {
                    $single = Carbon::createFromFormat('d M, Y', trim($date))->format('Y-m-d');
                    $orderQuery->whereDate('creation_time', $single);
                } catch (\Exception $e) {}
            }
        }

        $orders = $orderQuery->orderByDesc('creation_time')->get();

        // Pagination
        $perPage = 20;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $paginated = new LengthAwarePaginator(
            $orders->slice(($page - 1) * $perPage, $perPage)->values(),
            $orders->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('pharmacien.commandes', ['orders' => $paginated]);
    }

    public function dashboard()
    {
        $orders = order::orderByDesc('creation_time')->take(5)->get();

        // Calculate totals for this month and last month
        $monthlytotal = order::whereMonth('creation_time', now()->month)
            ->whereYear('creation_time', now()->year)
            ->sum('total');

        $monthbeforetotal = order::whereMonth('creation_time', now()->subMonth()->month)
            ->whereYear('creation_time', now()->year)
            ->sum('total');

        // --- Top selling medicaments this week ---
        $startOfWeek = now()->subDays(6)->startOfDay();
        $endOfWeek = now()->endOfDay();

        $topProducts = \App\Models\ligneOrder::with(['medicament.stock'])
            ->whereHas('order', function($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('creation_time', [$startOfWeek, $endOfWeek]);
            })
            ->selectRaw('medicament_id, SUM(quantite) as total_sold')
            ->groupBy('medicament_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // --- Top clients ---
        $topClients = \App\Models\order::select('patient_name')
            ->whereNotNull('patient_name')
            ->groupBy('patient_name')
            ->selectRaw('patient_name, COUNT(*) as orders_count, SUM(total) as total_spent')
            ->orderByDesc('orders_count')
            ->take(5)
            ->get();

        return view('pharmacien.dashboard', [
            'orders' => $orders,
            'monthlytotal' => $monthlytotal,
            'monthbeforetotal' => $monthbeforetotal,
            'topProducts' => $topProducts,
            'topClients' => $topClients,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $orderQuery = order::query();

        if ($request->filled('search_id')) {
            $orderQuery->where('id', $request->input('search_id'));
        }
        if ($request->filled('search_status')) {
            $orderQuery->where('status', strtolower($request->input('search_status')));
        }
        if ($request->filled('search_date')) {
            $date = $request->input('search_date');
            $dates = explode(' - ', $date);
            if (count($dates) === 2) {
                try {
                    $start = Carbon::createFromFormat('d M, Y', trim($dates[0]))->format('Y-m-d');
                    $end = Carbon::createFromFormat('d M, Y', trim($dates[1]))->format('Y-m-d');
                    $orderQuery->whereDate('creation_time', '>=', $start)
                               ->whereDate('creation_time', '<=', $end);
                } catch (\Exception $e) {}
            } else {
                try {
                    $single = Carbon::createFromFormat('d M, Y', trim($date))->format('Y-m-d');
                    $orderQuery->whereDate('creation_time', $single);
                } catch (\Exception $e) {}
            }
        }

        // If no filters, default to current month
        if (!$request->filled('search_id') && !$request->filled('search_status') && !$request->filled('search_date')) {
            $orderQuery->whereMonth('creation_time', now()->month)
                       ->whereYear('creation_time', now()->year);
        }

        $orders = $orderQuery->orderByDesc('creation_time')->get();

        $pdf = PDF::loadView('pharmacien.commandes_pdf', compact('orders'));
        return $pdf->download('commandes.pdf');
    }

    /**
     * Show the details of a specific order (commande or vente).
     */
    public function showCommande($id)
    {
        $order = order::findOrFail($id);

        // Fetch lignes from the new ligneOrder model
        $lignes = ligneOrder::where('order_id', $order->id)
            ->with('medicament')
            ->get()
            ->map(function ($ligne) {
                return [
                    'medicament_nom' => $ligne->medicament ? $ligne->medicament->nom : 'N/A',
                    'quantite'       => $ligne->quantite,
                    'prix_unitaire'  => $ligne->montant,
                    'posologie'      => $ligne->posologie,
                ];
            });

        $order->lignes = $lignes;

        return view('pharmacien.detailCommande', compact('order'));
    }

    /**
     * Change the status of a commande.
     */
    public function changeStatus(Request $request)
    {
        $request->validate([
            'commande_id' => 'required|integer',
            'order_type' => 'required|in:commande,vente',
            'new_status' => 'required|in:en attente,valide,livre,refus,terminee',
        ]);

        if ($request->order_type === 'commande') {
            // Only allow valid statuses for commandes
            if (!in_array($request->new_status, ['en attente', 'valide', 'livre', 'refus'])) {
                return redirect()->back()->with('error', 'Statut non autorisé pour une commande.');
            }
            $commande = Commande::find($request->commande_id);
            $order = order::where('raw_id', $request->commande_id)->where('type', 'commande')->first();

            if ($commande && $order) {
                $commande->statut = $request->new_status;
                $commande->save();

                $order->status = $request->new_status;
                $order->save();

                return redirect()->back()->with('success', 'Le statut de la commande a été mis à jour.');
            }
        }

        if ($request->order_type === 'vente') {
            // Only allow valid statuses for ventes
            if (!in_array($request->new_status, ['en attente', 'valide', 'terminee', 'refus'])) {
                return redirect()->back()->with('error', 'Statut non autorisé pour une vente.');
            }
            $vente = Vente::find($request->commande_id);
            $order = order::where('raw_id', $request->commande_id)->where('type', 'vente')->first();

            if ($vente && $order) {
                $vente->statut = $request->new_status;
                $vente->save();

                $order->status = $request->new_status;
                $order->save();

                return redirect()->back()->with('success', 'Le statut de la vente a été mis à jour.');
            }
        }

        return redirect()->back()->with('error', 'Commande ou vente introuvable.');
    }

    /**
     * export the list of medicaments in PDF format.
     */
    public function exportMedicamentsPdf(Request $request)
    {
        $query = ligneOrder::with('medicament');
        if ($request->filled('order_id')) {
            $query->where('order_id', $request->input('order_id'));
        }
        $lignes = $query->get()->map(function ($ligne) {
            return [
                'medicament_nom' => $ligne->medicament ? $ligne->medicament->nom : 'N/A',
                'description'    => $ligne->medicament ? $ligne->medicament->detailles : '-',
                'quantite'       => $ligne->quantite,
                'prix_unitaire'  => $ligne->montant,
                'posologie'      => $ligne->posologie,
            ];
        });

        $pdf = \PDF::loadView('pharmacien.detailcommande_pdf', ['lignes' => $lignes]);
        return $pdf->download('medicaments.pdf');
    }

    public function stockList(Request $request)
    {
        $query = Stock::with('medicament');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('medicament', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('detailles', 'like', "%{$search}%");
            });
        }

        $stocks = $query->paginate(10)->appends($request->only('search'));

        return view('pharmacien.stocklist', compact('stocks'));
    }
    public function makeSale()
    {
        // Pull medicaments from the stock table, eager load medicament relation
        $stocks = Stock::with('medicament')->get();
        return view('pharmacien.makeSale', ['stocks' => $stocks]);
    }
    public function storeSale(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.medicament_id' => 'required|exists:medicaments,id',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.prix' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0'
        ]);

        // Create Vente
        $vente = new Vente();
        $vente->dateCreation = now();
        $vente->statut = 'complete';
        $vente->total = $request->total;
        $vente->patient_name = $request->patient_name;
        $vente->save();

        // Create LigneVente for each item and update stock
        foreach ($request->items as $item) {
            $ligne = new \App\Models\LigneVente();
            $ligne->vente_id = $vente->id;
            $ligne->medicament_id = $item['medicament_id'];
            $ligne->quantite = $item['quantite'];
            $ligne->montant = $item['prix'] * $item['quantite'];
            $ligne->save();

            // Decrease stock quantity
            $stock = Stock::where('medicament_id', $item['medicament_id'])->first();
            if ($stock) {
                $stock->quantité = max(0, $stock->quantité - $item['quantite']);
                $stock->save();
            }
        }

        return response()->json(['success' => true]);
    }
    public function deleteStock($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();
        return response()->json(['success' => true]);
    }
    public function storeMedicaments(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'seuilMinimum' => 'required|integer|min:0',
            'quantité' => 'required|integer|min:0',
        ]);

        // Create or update Medicament
        $medicament = Medicament::updateOrCreate(
            ['nom' => $request->nom],
            [
                'prix' => $request->prix,
                'description' => $request->description,
            ]
        );

        // Create or update Stock
        Stock::updateOrCreate(
            ['medicament_id' => $medicament->id],
            [
                'seuilMinimum' => $request->seuilMinimum,
                'quantité' => $request->quantité,
            ]
        );

        return redirect()->back()->with('success', 'Medicaments saved successfully.');
    }

    public function storeNewProduct(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'detailles' => 'nullable|string',
            'dosage' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'necessiteOrdonnance' => 'required|boolean',
            'quantite' => 'required|integer|min:0',
            'seuilMinimum' => 'nullable|integer|min:0',
        ]);

        // Create Medicament
        $medicament = Medicament::create([
            'nom' => $request->nom,
            'detailles' => $request->detailles,
            'dosage' => $request->dosage,
            'prix' => $request->prix,
            'necessiteOrdonnance' => $request->necessiteOrdonnance,
        ]);

        // Create Stock
        Stock::create([
            'medicament_id' => $medicament->id,
            'quantité' => $request->quantite,
            'seuilMinimum' => $request->seuilMinimum ?? 0,
        ]);

        return redirect()->route('stocklist')->with('success', 'Medicament and stock added successfully.');
    }

    public function updateStock(Request $request, $id)
{
    $request->validate([
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'necessiteOrdonnance' => 'required|in:0,1',
    ]);

    $stock = Stock::with('medicament')->findOrFail($id);
    $stock->quantité = $request->quantity;
    $stock->save();

    if ($stock->medicament) {
        $stock->medicament->prix = $request->price;
        $stock->medicament->necessiteOrdonnance = $request->necessiteOrdonnance;
        $stock->medicament->save();
    }

    return response()->json(['success' => true]);
}
}