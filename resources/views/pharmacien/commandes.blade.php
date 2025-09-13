<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        <meta charset="utf-8">
        <link href="dist/images/logo.svg" rel="shortcut icon">
        <title>Liste des transactions - MediTrack</title>
        <link rel="stylesheet" href="dist/css/app.css" />
    </head>
    <body class="main">
        @include('layouts.topbar', ['pageTitle' => 'Liste des transactions'])
        @include('layouts.pharmacien.topmenu')
        <div class="wrapper wrapper--top-nav">
            <div class="wrapper-box">
                <!-- BEGIN: Content -->
                <div class="content">
                    <h2 class="intro-y text-lg font-medium mt-10">
                        Liste des transactions
                    </h2>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="intro-y col-span-12 flex flex-wrap xl:flex-nowrap items-center mt-2">
                            <div class="flex w-full sm:w-auto">
                                <form method="GET" action="{{ route('commandes') }}" class="flex w-full sm:w-auto items-center">
                                    <div class="w-48 relative text-slate-500">
                                        <input
                                            type="text"
                                            name="search_id"
                                            class="form-control w-48 box pr-10"
                                            placeholder="Rechercher par facture..."
                                            value="{{ request('search_id') }}"
                                        >
                                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                                    </div>
                                    <select name="search_status" class="form-select box ml-2">
                                        <option value="">Tous les statuts</option>
                                        <option value="en attente" {{ request('search_status') == 'en attente' ? 'selected' : '' }}>En attente</option>
                                        <option value="valide" {{ request('search_status') == 'valide' ? 'selected' : '' }}>Validé</option>
                                        <option value="livre" {{ request('search_status') == 'livre' ? 'selected' : '' }}>Livré</option>
                                        <option value="refus" {{ request('search_status') == 'refus' ? 'selected' : '' }}>Refusé</option>
                                    </select>
                                    <div class="sm:ml-2 mt-3 sm:mt-0 relative text-slate-500">
                                        <i data-lucide="calendar" class="w-4 h-4 z-10 absolute my-auto inset-y-0 ml-3 left-0"></i>
                                        <input
                                            type="text"
                                            name="search_date"
                                            class="datepicker form-control sm:w-56 box pl-10"
                                            placeholder="Sélectionner une date"
                                            value=""
                                            autocomplete="off"
                                        >
                                    </div>
                                    <button type="submit" class="btn btn-primary ml-2">Rechercher</button>
                                </form>
                            </div>
                            <div class="hidden xl:block mx-auto text-slate-500">
                                Affichage de {{ $orders->firstItem() ?? 0 }} à {{ $orders->lastItem() ?? 0 }} sur {{ $orders->total() }} entrées
                            </div>
                            <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
                                <a href="{{ route('commandes.export.pdf', request()->all()) }}" class="btn btn-primary shadow-md mr-2">
                                    <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Exporter en PDF
                                </a>
                            </div>
                        </div>
                        <!-- BEGIN: Data List -->
                        <div class="intro-y col-span-12 overflow-auto 2xl:overflow-visible">
                            <table class="table table-report -mt-2">
                                <thead>
                                    <tr>
                                        <th class="whitespace-nowrap">ID TRANSACTION</th>
                                        <th class="whitespace-nowrap">NOM DE L'ACHETEUR</th>
                                        <th class="text-center whitespace-nowrap">STATUT</th>
                                        <th class="whitespace-nowrap">PAIEMENT</th>
                                        <th class="text-right whitespace-nowrap">
                                            <div class="pr-16">TOTAL TRANSACTION</div>
                                        </th>
                                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        <tr class="intro-x">
                                            <td class="w-40 !py-4">
                                                <a href="" class="underline decoration-dotted whitespace-nowrap">
                                                    #{{ $order->id }}
                                                </a>
                                            </td>
                                            <td class="w-40">
                                                <a href="" class="font-medium whitespace-nowrap">
                                                    {{ $order->patient_name }}
                                                </a>
                                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5"></div>
                                            </td>
                                            <td class="text-center">
                                                @if($order->type === 'commande')
                                                    <div class="flex items-center justify-center whitespace-nowrap
                                                        @if($order->status === 'livre') text-success
                                                        @elseif($order->status === 'valide') text-primary
                                                        @elseif($order->status === 'en attente') text-warning
                                                        @else text-danger
                                                        @endif">
                                                        <i data-lucide="check-square" class="w-4 h-4 mr-2"></i>
                                                        {{ ucfirst($order->status) }}
                                                    </div>
                                                @else
                                                    <div class="flex items-center justify-center whitespace-nowrap
                                                        @if($order->status === 'terminee') text-success
                                                        @elseif($order->status === 'valide') text-primary
                                                        @elseif($order->status === 'en attente') text-warning
                                                        @else text-danger
                                                        @endif">
                                                        <i data-lucide="shopping-cart" class="w-4 h-4 mr-2"></i>
                                                        {{ ucfirst($order->status) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="whitespace-nowrap">{{ $order->payment_method }}</div>
                                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                                    {{ $order->creation_time ? \Carbon\Carbon::parse($order->creation_time)->format('d M, H:i') : '' }}
                                                </div>
                                            </td>
                                            <td class="w-40 text-right">
                                                <div class="pr-16">{{ number_format($order->total, 2, ',', ' ') }} MAD</div>
                                            </td>
                                            <td class="table-report__action">
                                                <div class="flex justify-center items-center">
                                                        <a class="flex items-center text-primary whitespace-nowrap mr-5" href="{{ route('detailCommande', ['id' => $order->id]) }}">
                                                            <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Voir détails
                                                        </a>
                                                        <a class="flex items-center text-primary whitespace-nowrap" href="javascript:;" data-tw-toggle="modal" data-tw-target="#change-status-modal" onclick="setCommandeId({{ $order->raw_id }}, '{{ $order->type }}')">
                                                            <i data-lucide="arrow-left-right" class="w-4 h-4 mr-1"></i> Changer le statut
                                                        </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="intro-x">
                                            <td class="w-10" colspan="6">
                                                <span class="font-medium whitespace-nowrap">Aucune commande trouvée</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- END: Data List -->
                        <!-- BEGIN: Pagination -->
                        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                            <nav class="w-full sm:w-auto sm:mr-auto">
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    <li class="page-item {{ $orders->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $orders->previousPageUrl() ?? '#' }}">
                                            <i class="w-4 h-4" data-lucide="chevrons-left"></i>
                                        </a>
                                    </li>
                                    {{-- Pagination Elements --}}
                                    @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                        @if ($page == 1 && $orders->currentPage() > 3)
                                            <li class="page-item"><a class="page-link" href="{{ $orders->url(1) }}">1</a></li>
                                            @if ($orders->currentPage() > 4)
                                                <li class="page-item"><a class="page-link" href="#">...</a></li>
                                            @endif
                                        @endif

                                        @if ($page >= $orders->currentPage() - 2 && $page <= $orders->currentPage() + 2)
                                            <li class="page-item {{ $page == $orders->currentPage() ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif

                                        @if ($page == $orders->lastPage() && $orders->currentPage() < $orders->lastPage() - 2)
                                            @if ($orders->currentPage() < $orders->lastPage() - 3)
                                                <li class="page-item"><a class="page-link" href="#">...</a></li>
                                            @endif
                                            <li class="page-item"><a class="page-link" href="{{ $orders->url($orders->lastPage()) }}">{{ $orders->lastPage() }}</a></li>
                                        @endif
                                    @endforeach
                                    {{-- Next Page Link --}}
                                    <li class="page-item {{ !$orders->hasMorePages() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $orders->nextPageUrl() ?? '#' }}">
                                            <i class="w-4 h-4" data-lucide="chevrons-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <!-- END: Pagination -->
                    </div>
                    <!-- BEGIN: Delete Confirmation Modal -->
                    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="p-5 text-center">
                                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i> 
                                        <div class="text-3xl mt-5">Êtes-vous sûr ?</div>
                                        <div class="text-slate-500 mt-2">
                                            Voulez-vous vraiment supprimer ces enregistrements ? 
                                            <br>
                                            Ce processus est irréversible.
                                        </div>
                                    </div>
                                    <div class="px-5 pb-8 text-center">
                                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Annuler</button>
                                        <button type="button" class="btn btn-danger w-24">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Delete Confirmation Modal -->
                    <!-- BEGIN: Change Status Modal -->
                    <div id="change-status-modal" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('commandes.changeStatus') }}">
                                    @csrf
                                    <input type="hidden" name="commande_id" id="modal_commande_id">
                                    <input type="hidden" name="order_type" id="modal_order_type">
                                    <div class="modal-body p-5 text-center">
                                        <div class="text-xl mb-4 font-semibold">Changer le statut de la transaction</div>
                                        <select name="new_status" class="form-select w-full mb-4" id="modal_status_select" required>
                                            <!-- Options will be set by JS -->
                                        </select>
                                        <button type="submit" class="btn btn-primary w-full">Confirmer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- END: Change Status Modal -->
                </div>
                <!-- END: Content -->
            </div>
        </div>
        <!-- END: Content -->
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        <script src="dist/js/app.js"></script>
        <!-- END: JS Assets-->
        <script>
            function setCommandeId(id, type) {
                document.getElementById('modal_commande_id').value = id;
                document.getElementById('modal_order_type').value = type;

                var select = document.getElementById('modal_status_select');
                select.innerHTML = ''; // Clear previous options

                if (type === 'commande') {
                    select.innerHTML += '<option value="en attente">En attente</option>';
                    select.innerHTML += '<option value="valide">Validé</option>';
                    select.innerHTML += '<option value="livre">Livré</option>';
                    select.innerHTML += '<option value="refus">Refusé</option>';
                } else if (type === 'vente') {
                    select.innerHTML += '<option value="en attente">En attente</option>';
                    select.innerHTML += '<option value="valide">Validé</option>';
                    select.innerHTML += '<option value="terminee">Terminée</option>';
                    select.innerHTML += '<option value="refus">Refusée</option>';
                }
            }
        </script>
    </body>
</html>