<!DOCTYPE html>
<html lang="en" class="light">
    <head>
    <meta charset="utf-8">
    <link href="{{ asset('dist/images/logo.svg') }}" rel="shortcut icon">
    <title>Détail de la transaction - MediTrack</title>
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" />
</head>
    <body class="main">
        @include('layouts.topbar', ['pageTitle' => 'Détail de la transaction'])
        @include('layouts.pharmacien.topmenu')
        <!-- BEGIN: Content -->
        <div class="wrapper wrapper--top-nav">
            <div class="wrapper-box">
                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Détails de la transaction
                        </h2>
                        <a href="{{ route('medicaments.export.pdf', ['order_id' => $order->id]) }}">
                        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                            <button class="btn btn-primary shadow-md mr-2">Exporter en PDF</button>
                        </div>
                        </a>
                    </div>
                    <!-- BEGIN: Transaction Details -->
                    <div class="intro-y grid grid-cols-11 gap-5 mt-5">
                        <div class="col-span-12 lg:col-span-4 2xl:col-span-3">
                            <div class="box p-5 rounded-md">
                                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                                    <div class="font-medium text-base truncate">Détails de la transaction</div>
                                    <a href="javascript:;" class="flex items-center ml-auto text-primary"
                                       data-tw-toggle="modal"
                                       data-tw-target="#change-status-modal"
                                       onclick="setCommandeId({{ $order->id }})">
                                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Changer le statut
                                    </a>
                                </div>
                                <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i> ID de la transaction : <a  class="underline decoration-dotted ml-1"># {{ $order->id }}</a> </div>
                                <div class="flex items-center mt-3"> <i data-lucide="calendar" class="w-4 h-4 text-slate-500 mr-2"></i> Date d'achat : {{ $order->creation_time}} </div>
                                <div class="flex items-center mt-3"> <i data-lucide="calendar" class="w-4 h-4 text-slate-500 mr-2"></i> Type d'achat : {{ $order->type}} </div>
                                <div class="flex items-center mt-3"> <i data-lucide="clock" class="w-4 h-4 text-slate-500 mr-2"></i> Statut de la transaction : <span class="bg-success/20 text-success rounded px-2 ml-1">{{$order->status}}</span> </div>
                            </div>
                            <div class="box p-5 rounded-md mt-5">
                                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                                    <div class="font-medium text-base truncate">Détails de l'acheteur</div>
                                </div>
                                <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i> Nom : {{$order->patient_name}}</div>
                            </div>
                            <div class="box p-5 rounded-md mt-5">
                                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                                    <div class="font-medium text-base truncate">Détails du paiement</div>
                                </div>
                                <div class="flex items-center">Méthode de paiement :
                                    <div class="ml-auto">{{ $order->payment_method }}</div>
                                </div>
                                <div class="flex items-center border-t border-slate-200/60 dark:border-darkmode-400 pt-5 mt-5 font-medium">
                                    <i data-lucide="credit-card" class="w-4 h-4 text-slate-500 mr-2"></i> Total général : 
                                    <div class="ml-auto">{{ number_format($order->total, 2, ',', ' ') }} MAD</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-7 2xl:col-span-8">
                            <div class="box p-5 rounded-md">
                                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                                    <div class="font-medium text-base truncate">Détails de la commande</div>
                                </div>
                                <div class="overflow-auto lg:overflow-visible -mt-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="whitespace-nowrap !py-5">Produit</th>
                                                <th class="whitespace-nowrap text-right">Prix unitaire</th>
                                                <th class="whitespace-nowrap text-right">Qté</th>
                                                <th class="whitespace-nowrap text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($order->lignes as $ligne)
                                                <tr>
                                                    <td>
                                                        <div class="flex items-center">
                                                            <span class="font-medium whitespace-nowrap ml-4">{{ $ligne['medicament_nom'] ?? 'Produit inconnu' }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($ligne['prix_unitaire'] ?? 0, 2, ',', ' ') }} MAD
                                                    </td>
                                                    <td class="text-right">
                                                        {{ $ligne['quantite'] }}
                                                    </td>
                                                    <td class="text-right">
                                                       {{ number_format(($ligne['prix_unitaire'] ?? 0) * ($ligne['quantite'] ?? 0), 2, ',', ' ') }} MAD 
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-slate-500 py-6">Aucun médicament trouvé</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Transaction Details -->
                </div>
                <!-- END: Content -->
            </div>
        </div>
        <!-- END: Content -->
        <!-- BEGIN: Change Status Modal -->
<div id="change-status-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" action="{{ route('commandes.changeStatus') }}">
                @csrf
                <input type="hidden" name="commande_id" id="modal_commande_id">
                <div class="modal-body p-5 text-center">
                    <div class="text-xl mb-4 font-semibold">Changer le statut de la commande</div>
                    <select name="new_status" class="form-select w-full mb-4" required>
                        <option value="en attente">En attente</option>
                        <option value="valide">Validé</option>
                        <option value="livre">Livré</option>
                        <option value="refus">Refusé</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-full">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END: Change Status Modal -->

<script>
    function setCommandeId(id) {
        document.getElementById('modal_commande_id').value = id;
    }
</script>
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        <script src="{{ asset('dist/js/app.js') }}"></script>
        <!-- END: JS Assets-->
    </body>
</html>