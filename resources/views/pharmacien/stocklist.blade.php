<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        <meta charset="utf-8">
        <link href="dist/images/logo.svg" rel="shortcut icon">
        <title>Liste des produits - Medictrack</title>
        <link rel="stylesheet" href="dist/css/app.css" />
    </head>
    <body class="main">
        @include('layouts.topbar', ['pageTitle' => 'Liste de stock'])
        <div class="wrapper">
            <div class="wrapper-box">
                @include('layouts.pharmacien.sidemenu')
                <!-- BEGIN: Content -->
                <div class="content">
                    <h2 class="intro-y text-lg font-medium mt-10">
                        Liste des produits
                    </h2>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                            <a href="{{route("newproduct")}}"><button class="btn btn-primary shadow-md mr-2">Ajouter un produit</button> </a>
                            <div class="dropdown">
                                <div class="dropdown-menu w-40">
                                    <ul class="dropdown-content">
                                        <li>
                                            <a href="" class="dropdown-item"> <i data-lucide="printer" class="w-4 h-4 mr-2"></i> Imprimer </a>
                                        </li>
                                        <li>
                                            <a href="" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Exporter vers Excel </a>
                                        </li>
                                        <li>
                                            <a href="" class="dropdown-item"> <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Exporter vers PDF </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="hidden md:block mx-auto text-slate-500">
                                Affichage de {{ $stocks->firstItem() }} à {{ $stocks->lastItem() }} sur {{ $stocks->total() }} entrées
                            </div>
                            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                                <form method="GET" action="{{ route('stocklist') }}" class="w-56 relative text-slate-500">
                                    <input
                                        id="stock-search"
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        class="form-control w-56 box pr-10"
                                        placeholder="Rechercher..."
                                    >
                                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <i class="w-4 h-4" data-lucide="search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <!-- BEGIN: Data List -->
                        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                            <table class="table table-report -mt-2">
                                <thead>
                                    <tr>
                                        <th class="whitespace-nowrap">NOM DU PRODUIT</th>
                                        <th class="text-center whitespace-nowrap">STOCK</th>
                                        <th class="text-center whitespace-nowrap">PRIX</th>
                                        <th class="text-center whitespace-nowrap">Nécessite ordonnance</th>
                                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stocks as $stock)
                                        <tr class="intro-x" data-stock-id="{{ $stock->id }}">
                                            <td>
                                                <a href="#" class="font-medium whitespace-nowrap">{{ $stock->medicament->nom ?? 'N/A' }}</a>
                                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                                    {{ $stock->medicament->detailles ?? '' }}
                                                </div>
                                            </td>
                                            <td class="text-center stock-quantity" data-value="{{ $stock->quantité }}">{{ $stock->quantité }}</td>
                                            <td class="text-center stock-price" data-value="{{ $stock->medicament->prix ?? 0 }}">{{ number_format($stock->medicament->prix ?? 0, 2) }} MAD</td>
                                            <td class="text-center stock-ordonnance" data-value="{{ $stock->medicament->necessiteOrdonnance ?? 0 }}">
                                                {{ isset($stock->medicament->necessiteOrdonnance) && $stock->medicament->necessiteOrdonnance ? 'Oui' : 'Non' }}
                                            </td>
                                            <td class="table-report__action w-56">
                                                <div class="flex justify-center items-center">
                                                    <a class="flex items-center mr-3 btn-edit-stock" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Modifier </a>
                                                    <a class="flex items-center text-danger btn-delete-stock" href="javascript:;" data-stock-id="{{ $stock->id }}" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal">
                                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Supprimer
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- END: Data List -->
                        <!-- BEGIN: Pagination -->
                        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                            <nav class="w-full sm:w-auto sm:mr-auto">
                                @if ($stocks->lastPage() > 1)
                                <ul class="pagination">
                                    {{-- First Page --}}
                                    <li class="page-item {{ $stocks->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $stocks->url(1) }}"><i class="w-4 h-4" data-lucide="chevrons-left"></i></a>
                                    </li>
                                    {{-- Previous Page --}}
                                    <li class="page-item {{ $stocks->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $stocks->previousPageUrl() ?? '#' }}"><i class="w-4 h-4" data-lucide="chevron-left"></i></a>
                                    </li>
                                    {{-- Pagination Elements --}}
                                    @for ($i = max(1, $stocks->currentPage() - 2); $i <= min($stocks->lastPage(), $stocks->currentPage() + 2); $i++)
                                        <li class="page-item {{ $i == $stocks->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $stocks->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    {{-- Next Page --}}
                                    <li class="page-item {{ !$stocks->hasMorePages() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $stocks->nextPageUrl() ?? '#' }}"><i class="w-4 h-4" data-lucide="chevron-right"></i></a>
                                    </li>
                                    {{-- Last Page --}}
                                    <li class="page-item {{ !$stocks->hasMorePages() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $stocks->url($stocks->lastPage()) }}"><i class="w-4 h-4" data-lucide="chevrons-right"></i></a>
                                    </li>
                                </ul>
                                @endif
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
                                        <button type="button" id="confirm-delete-stock" class="btn btn-danger w-24">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Delete Confirmation Modal -->

                    <!-- BEGIN: Edit Product Modal -->
                    <div id="edit-product-modal" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="edit-product-form" class="modal-content">
                                @csrf
                                <div class="modal-body p-0">
                                    <div class="p-5 text-center">
                                        <div class="text-3xl mt-5 mb-2">Modifier le produit</div>
                                        <input type="hidden" id="edit-stock-id" name="stock_id">
                                        <div class="mt-3">
                                            <label class="block mb-1">Prix (MAD)</label>
                                            <input type="number" step="0.01" min="0" id="edit-price" name="price" class="form-control" required>
                                        </div>
                                        <div class="mt-3">
                                            <label class="block mb-1">Quantité en stock</label>
                                            <input type="number" min="0" id="edit-quantity" name="quantity" class="form-control" required>
                                        </div>
                                        <div class="mt-3">
                                            <label class="block mb-1">Nécessite ordonnance</label>
                                            <select id="edit-ordonnance" name="necessiteOrdonnance" class="form-control" required>
                                                <option value="1">Oui</option>
                                                <option value="0">Non</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="px-5 pb-8 text-center">
                                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Annuler</button>
                                        <button type="submit" class="btn btn-primary w-24">Enregistrer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- END: Edit Product Modal -->
                    <script>
                        let stockIdToDelete = null;

                        // When clicking the delete button, store the stock id
                        document.querySelectorAll('.btn-delete-stock').forEach(btn => {
                            btn.addEventListener('click', function () {
                                stockIdToDelete = this.getAttribute('data-stock-id');
                            });
                        });

                        // When confirming deletion
                        document.getElementById('confirm-delete-stock').addEventListener('click', function () {
                            if (!stockIdToDelete) return;
                            fetch(`/stocks/${stockIdToDelete}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => {
                                if (res.ok) {
                                    // Remove the row from the table
                                    const row = document.querySelector(`tr[data-stock-id="${stockIdToDelete}"]`);
                                    if (row) row.remove();
                                    // Hide modal (if using Tailwind modal)
                                    if (window.tailwind && window.tailwind.Modal) {
                                        window.tailwind.Modal.getOrCreateInstance(document.getElementById('delete-confirmation-modal')).hide();
                                    } else {
                                        document.getElementById('delete-confirmation-modal').style.display = 'none';
                                    }
                                } else {
                                    alert('Erreur lors de la suppression du stock.');
                                }
                                stockIdToDelete = null; // Reset stock ID
                            })
                            .catch(err => {
                                console.error('Error deleting stock:', err);
                                alert('Erreur lors de la suppression du stock.');
                            });
                        });

                        // Edit functionality
                        document.querySelectorAll('.btn-edit-stock').forEach(btn => {
                            btn.addEventListener('click', function () {
                                const row = this.closest('tr');
                                const stockId = row.getAttribute('data-stock-id');
                                const price = row.querySelector('.stock-price').dataset.value;
                                const quantity = row.querySelector('.stock-quantity').dataset.value;
                                const ordonnance = row.querySelector('.stock-ordonnance').dataset.value;

                                document.getElementById('edit-stock-id').value = stockId;
                                document.getElementById('edit-price').value = price;
                                document.getElementById('edit-quantity').value = quantity;
                                document.getElementById('edit-ordonnance').value = ordonnance;

                                // Show modal (Tailwind or fallback)
                                if (window.tailwind && window.tailwind.Modal) {
                                    window.tailwind.Modal.getOrCreateInstance(document.getElementById('edit-product-modal')).show();
                                } else {
                                    document.getElementById('edit-product-modal').style.display = 'block';
                                }
                            });
                        });

                        // Handle form submit
                        document.getElementById('edit-product-form').addEventListener('submit', function (e) {
                            e.preventDefault();
                            const stockId = document.getElementById('edit-stock-id').value;
                            const price = document.getElementById('edit-price').value;
                            const quantity = document.getElementById('edit-quantity').value;
                            const ordonnance = document.getElementById('edit-ordonnance').value;

                            fetch(`/stocks/${stockId}/update`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    price: price,
                                    quantity: quantity,
                                    necessiteOrdonnance: ordonnance
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                } else {
                                    alert('Erreur lors de la mise à jour.');
                                }
                            })
                            .catch(err => {
                                alert('Erreur lors de la mise à jour.');
                            });
                        });
                    </script>
                </div>
                <!-- END: Content -->
            </div>
        </div>
        <script src="dist/js/app.js"></script>
    </body>
</html>