<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        <meta charset="utf-8">
        <link href="dist/images/logo.svg" rel="shortcut icon">
        <title>Profile - MediTrack</title>
        <link rel="stylesheet" href="dist/css/app.css" />
    </head>
    <body class="main">
        @include('layouts.topbar', ['pageTitle' => 'Profile'])
        <div class="wrapper">
            <div class="wrapper-box">
                @include('layouts.pharmacien.sidemenu')
                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Mon Profil
                        </h2>
                    </div>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        @include('layouts.pharmacien.profilemenu')
                        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
                            <div class="grid grid-cols-12 gap-6">
                                <!-- BEGIN: User Info -->
                                <div class="intro-y box col-span-12">
                                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Informations utilisateur
                                        </h2>
                                    </div>
                                    <div class="p-5">
                                        @php
                                            $user = Auth::user();
                                            $pharmacien = \App\Models\Pharmacien::where('user_id', $user->id)->first();
                                        @endphp
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <div class="mb-4">
                                                    <span class="font-medium text-slate-700">Nom :</span>
                                                    <span class="ml-2">{{ $user->nom }}</span>
                                                </div>
                                                <div class="mb-4">
                                                    <span class="font-medium text-slate-700">Prénom :</span>
                                                    <span class="ml-2">{{ $user->prenom }}</span>
                                                </div>
                                                <div class="mb-4">
                                                    <span class="font-medium text-slate-700">Email :</span>
                                                    <span class="ml-2">{{ $user->email }}</span>
                                                </div>
                                                <div class="mb-4">
                                                    <span class="font-medium text-slate-700">Téléphone :</span>
                                                    <span class="ml-2">{{ $user->phone ?? '-' }}</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="mb-4">
                                                    <span class="font-medium text-slate-700">Adresse :</span>
                                                    <span class="ml-2">{{ $user->address ?? '-' }}</span>
                                                </div>
                                                <div class="mb-4">
                                                    <span class="font-medium text-slate-700">Rôle :</span>
                                                    <span class="ml-2">{{ $user->role }}</span>
                                                </div>
                                                <div class="mb-4">
                                                    <span class="font-medium text-slate-700">Licence Pharmacien :</span>
                                                    <span class="ml-2">{{ $pharmacien->licence ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END: User Info -->

                                <!-- BEGIN: Orders "en attente" -->
                                <div class="intro-y box col-span-12">
                                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Commandes en attente
                                        </h2>
                                    </div>
                                    <div class="p-5">
                                        @php
                                            $pendingOrders = \App\Models\order::where('status', 'en attente')->orderByDesc('creation_time')->get();
                                        @endphp
                                        @if($pendingOrders->isEmpty())
                                            <div class="text-slate-500">Aucune commande en attente.</div>
                                        @else
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Patient</th>
                                                        <th>Date</th>
                                                        <th>Total</th>
                                                        <th>Méthode de paiement</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pendingOrders as $order)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('detailCommande', ['id' => $order->id]) }}">
                                                                {{ $order->id }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $order->patient_name ?? '-' }}</td>
                                                        <td>{{ $order->creation_time ? \Carbon\Carbon::parse($order->creation_time)->format('d/m/Y H:i') : '-' }}</td>
                                                        <td>{{ number_format($order->total, 2, ',', ' ') }} MAD</td>
                                                        <td>{{ $order->payment_method ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                                <!-- END: Orders "en attente" -->
                                <!-- BEGIN: Low Stock Medicines -->
                                <div class="intro-y box col-span-12">
                                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                                        <h2 class="font-medium text-base mr-auto">
                                            Médicaments en stock faible
                                        </h2>
                                    </div>
                                    <div class="p-5">
                                        @php
                                            $lowStocks = \App\Models\Stock::with('medicament')->whereColumn('quantité', '<=', 'seuilMinimum')->get();
                                        @endphp
                                        @if($lowStocks->isEmpty())
                                            <div class="text-slate-500">Aucun médicament en stock faible.</div>
                                        @else
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Nom</th>
                                                        <th>Quantité</th>
                                                        <th>Seuil minimum</th>
                                                        <th>Ordonnance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($lowStocks as $stock)
                                                        <tr>
                                                            <td>{{ $stock->medicament->nom ?? '-' }}</td>
                                                            <td>{{ $stock->quantité }}</td>
                                                            <td>{{ $stock->seuilMinimum }}</td>
                                                            <td>
                                                                @if(isset($stock->medicament->necessiteOrdonnance) && $stock->medicament->necessiteOrdonnance)
                                                                    <span class="text-warning">Oui</span>
                                                                @else
                                                                    <span class="text-success">Non</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                                <!-- END: Low Stock Medicines -->
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