<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        <link href="dist/images/logo.svg" rel="shortcut icon">
        <title>Tableau de bord - MediTrack</title>
        <link rel="stylesheet" href="dist/css/app.css" />
    </head>
    <body class="main">
        @php
    use Carbon\Carbon;
    $months = [];
    $sales = [];
    $year = now()->year;
    for ($i = 1; $i <= 12; $i++) {
        $monthObj = Carbon::create($year, $i, 1);
        $months[] = $monthObj->format('M Y');
        $sales[] = \App\Models\order::whereYear('creation_time', $year)
            ->whereMonth('creation_time', $i)
            ->sum('total');
    }
        @endphp
        @include('layouts.topbar', ['pageTitle' => 'Tableau de bord'])
        <div class="wrapper">
            <div class="wrapper-box">
                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12 2xl:col-span-9">
                            <div class="grid grid-cols-12 gap-6">
                                <!-- BEGIN: Links -->
                                <div class="col-span-12 mt-8">
                                    <div class="intro-y flex items-center h-10">
                                        <h2 class="text-lg font-medium truncate mr-5">
                                            Applications
                                        </h2>
                                        <a href="" class="ml-auto flex items-center text-primary"> <i data-lucide="refresh-ccw" class="w-4 h-4 mr-3"></i> Recharger les données </a>
                                    </div>
                                    <div class="grid grid-cols-12 gap-6 mt-5">

                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                        <a href="{{ route('makeSale') }}">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <i data-lucide="shopping-cart" class="report-box__icon text-primary"></i> 
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">Faire une vente</div>
                                                    <div class="text-base text-slate-500 mt-1">vendre un médicament du stock</div>
                                                </div>
                                            </div>
                                        </a>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <a href="{{ route('stocklist') }}">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <i data-lucide="credit-card" class="report-box__icon text-pending"></i>
                                                    </div>                           
                                                        <div class="text-3xl font-medium leading-8 mt-6">Stock</div>
                                                        <div class="text-base text-slate-500 mt-1">voir les articles disponibles en stock</div>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <a href="{{route('newproduct')}}">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <i data-lucide="monitor" class="report-box__icon text-warning"></i> 
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">Ajouter des articles</div>
                                                    <div class="text-base text-slate-500 mt-1">Ajouter des articles en stock</div>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                                            <a href="{{ route('commandes') }}">
                                            <div class="report-box zoom-in">
                                                <div class="box p-5">
                                                    <div class="flex">
                                                        <i data-lucide="user" class="report-box__icon text-success"></i> 
                                                    </div>
                                                    <div class="text-3xl font-medium leading-8 mt-6">Transactions</div>
                                                    <div class="text-base text-slate-500 mt-1">Voir l'historique des transactions</div>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Links -->
                                <!-- BEGIN: Sales Report Graph -->
                                <div class="col-span-12 lg:col-span-6 mt-8">
                                    <div class="intro-y block sm:flex items-center h-10">
                                        <h2 class="text-lg font-medium truncate mr-5">
                                            Rapport des ventes
                                        </h2>
                                    </div>
                                    <div class="intro-y box p-5 mt-12 sm:mt-5">
                                        <div class="flex flex-col md:flex-row md:items-center">
                                            <div class="flex">
                                                <div>
                                                    <div class="text-primary dark:text-slate-300 text-lg xl:text-xl font-medium">{{ number_format($monthlytotal, 0,"",",") }} MAD</div>
                                                    <div class="mt-0.5 text-slate-500">Ce mois-ci</div>
                                                </div>
                                                <div class="w-px h-12 border border-r border-dashed border-slate-200 dark:border-darkmode-300 mx-4 xl:mx-5"></div>
                                                <div>
                                                    <div class="text-slate-500 text-lg xl:text-xl font-medium">{{ number_format($monthbeforetotal, 0,"",",") }} MAD</div>
                                                    <div class="mt-0.5 text-slate-500">Mois dernier</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="report-chart">
                                            <div class="h-[275px]">
                                                <canvas id="report-line-chart" class="mt-6 -mb-6"></canvas>
                                                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                                <script>
document.addEventListener('DOMContentLoaded', function () {
    // Store chart instance globally to destroy it before creating a new one
    if (window.reportLineChart) {
        window.reportLineChart.destroy();
    }
    const ctx = document.getElementById('report-line-chart').getContext('2d');
    window.reportLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Ventes mensuelles',
                data: {!! json_encode($sales) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' MAD';
                        }
                    }
                }
            }
        }
    });
});
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: Sales Report Graph -->
                                <!-- BEGIN: Low stock -->
                                <div class="col-span-12 xl:col-span-6 mt-8">
                                    <div class="intro-y flex items-center h-10">
                                        <h2 class="text-lg font-medium truncate mr-5">
                                            Stock trop bas
                                        </h2>
                                    </div>
                                    <div class="mt-5">
                                        @php
                                            // Fetch low stock items (you can move this logic to the controller for better practice)
                                            $lowStocks = \App\Models\Stock::with('medicament')->whereColumn('quantité', '<=', 'seuilMinimum')->get();
                                        @endphp
                                        @php
                                            $count = $lowStocks->count();
                                            $minRows = 4;
                                        @endphp

                                        @forelse($lowStocks as $stock)
                                            <div class="intro-y">
                                                <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                                                    <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden bg-slate-200 flex items-center justify-center">
                                                    </div>
                                                    <div class="ml-4 mr-auto">
                                                        <div class="font-medium">{{ $stock->medicament->nom ?? 'N/A' }}</div>
                                                        <div class="text-slate-500 text-xs mt-0.5">
                                                            Stock : <span class="font-bold text-danger">{{ $stock->quantité }}</span>
                                                            @if($stock->medicament && $stock->medicament->necessiteOrdonnance)
                                                                <span class="ml-2 text-xs text-warning">(Ordonnance requise)</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="py-1 px-2 rounded-full text-xs bg-danger text-white cursor-pointer font-medium">
                                                        Min : {{ $stock->seuilMinimum }}
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                        @endforelse

                                        @for($i = 0; $i < max(0, $minRows - $count); $i++)
                                            <div class="intro-y mb-3">
                                                <div class="box px-4 py-4 flex items-center zoom-in">
                                                    <div class="w-10 h-10 flex-none image-fit rounded-md overflow-hidden bg-slate-200"></div>
                                                    <div class="ml-4 mr-auto">
                                                        <div class="font-medium">&nbsp;</div>
                                                        <div class="text-slate-500 text-xs mt-0.5">&nbsp;</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor

                                        <a href="{{ route('stocklist') }}" class="intro-y w-full block text-center rounded-md py-4 border border-dotted border-slate-400 dark:border-darkmode-300 text-slate-500 mt-2">Voir plus</a>
                                    </div>
                                </div>
                                <!-- END: Weekly Top Seller -->

                                <!-- BEGIN: Weekly Top Products -->
                                <div class="col-span-12 mt-6">
                                    <div class="intro-y block sm:flex items-center h-10">
                                        <h2 class="text-lg font-medium truncate mr-5">
                                            Meilleurs produits de la semaine
                                        </h2>
                                    </div>
                                    <div class="intro-y overflow-auto lg:overflow-visible mt-8 sm:mt-0">
                                        <table class="table table-report sm:mt-2">
                                            <thead>
                                                <tr>
                                                    <th class="whitespace-nowrap">NOM DU PRODUIT</th>
                                                    <th class="text-center whitespace-nowrap">VENDU</th>
                                                    <th class="text-center whitespace-nowrap">STOCK</th>
                                                    <th class="text-center whitespace-nowrap">ORDONNANCE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($topProducts as $product)
                                                    <tr class="intro-x">
                                                        <td>
                                                            <span class="font-medium whitespace-nowrap">
                                                                {{ $product->medicament->nom ?? 'N/A' }}
                                                        </span>
                                                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                                                {{ $product->medicament->detailles ?? '' }}
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{ $product->total_sold }}</td>
                                                        <td class="text-center">
                                                            {{ optional($product->medicament->stock)->quantité ?? '-' }}
                                                        </td>
                                                        <td class="text-center">
                                                            @if(isset($product->medicament->necessiteOrdonnance) && $product->medicament->necessiteOrdonnance)
                                                                <span class="text-warning">Oui</span>
                                                            @else
                                                                <span class="text-success">Non</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Aucune vente cette semaine.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END: Weekly Top Products -->
                            </div>
                        </div>
                        <div class="col-span-12 2xl:col-span-3">
                            <div class="2xl:border-l -mb-10 pb-10">
                                <div class="2xl:pl-6 grid grid-cols-12 gap-x-6 2xl:gap-x-0 gap-y-6">
                                    <!-- BEGIN: Transactions -->
                                    <div class="col-span-12 md:col-span-6 xl:col-span-4 2xl:col-span-12 mt-3 2xl:mt-8">
                                        <div class="intro-x flex items-center h-10">
                                            <h2 class="text-lg font-medium truncate mr-5">
                                                Transactions
                                            </h2>
                                        </div>
                                        <div class="mt-5">
                                            @foreach ($orders as $order)
                                            <a href="{{route('detailCommande', ['id'=> $order->id])}}">
                                            <div class="intro-x">
                                                <div class="box px-5 py-3 mb-3 flex items-center zoom-in">
                                                    <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden">
                                                        <img alt="Midone - HTML Admin Template" src="dist/images/profile-3.png">
                                                    </div>
                                                    <div class="ml-4 mr-auto">
                                                        <div class="font-medium"> {{$order->patient_name}}</div>
                                                        <div class="text-slate-500 text-xs mt-0.5">{{ $order->creation_time ? \Carbon\Carbon::parse($order->creation_time)->format('d M, H:i') : '' }}</div>
                                                    </div>
                                                    <div class="text-success">+${{ number_format($order->total, 2, ',', ' ') }} </div>
                                                </div>
                                            </div>
                                            </a>
                                            @endforeach
                                            </div>
                                            <a href="{{route("commandes")}}" class="intro-x w-full block text-center rounded-md py-3 border border-dotted border-slate-400 dark:border-darkmode-300 text-slate-500">Voir plus</a> 
                                        </div>
                                    </div>
                                    <!-- END: Transactions -->

                                    <!-- BEGIN: Top Clients -->
                                    <div class="col-span-12 md:col-span-6 xl:col-span-4 2xl:col-span-12 xl:col-start-1 xl:row-start-2 2xl:col-start-auto 2xl:row-start-auto mt-3">
                                        <div class="intro-x flex items-center h-10">
                                            <h2 class="text-lg font-medium truncate mr-5">
                                                Top Clients
                                            </h2>
                                        </div>
                                        <div class="mt-5">
                                            @if($topClients->isEmpty())
                                                <div class="intro-x box p-5 text-slate-500">Aucun client trouvé.</div>
                                            @else
                                                @foreach($topClients as $client)
                                                    <div class="intro-x">
                                                        <div class="box px-5 py-3 mb-3 flex items-center zoom-in">
                                                            <div class="w-10 h-10 flex-none image-fit rounded-full overflow-hidden bg-slate-200 flex items-center justify-center">
                                                                <img alt="Client" src="dist/images/profile-3.png">
                                                            </div>
                                                            <div class="ml-4 mr-auto">
                                                                <div class="font-medium">{{ $client->patient_name }}</div>
                                                                <div class="text-slate-500 text-xs mt-0.5">
                                                                    Achats : <span class="font-bold">{{ $client->orders_count }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="text-success font-medium">
                                                                {{ number_format($client->total_spent, 2, ',', ' ') }} MAD
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <!-- END: Top Clients -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Content -->
            </div>
        </div>
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        <script src="dist/js/app.js"></script>
        <!-- END: JS Assets-->
    </body>
</html>