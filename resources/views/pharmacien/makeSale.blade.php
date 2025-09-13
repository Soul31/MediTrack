<!DOCTYPE html>
<html lang="fr" class="light">
    <head>
        <meta charset="utf-8">
        <title>Point de Vente - MediTrack</title>
        <link rel="stylesheet" href="dist/css/app.css" />
    </head>
    <body class="main">
        @include('layouts.topbar', ['pageTitle' => 'Point de Vente'])
        @include('layouts.pharmacien.topmenu')
        <!-- BEGIN: Content -->
        <div class="wrapper wrapper--top-nav">
            <div class="wrapper-box">
                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Point de Vente
                        </h2>
                    </div>
                    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
                        <!-- BEGIN: Item List -->
                        <div class="intro-y col-span-12 lg:col-span-8">
                            <div class="lg:flex intro-y">
                                <div class="relative">
                                    <input id="search-medicament" type="text" class="form-control py-3 px-4 w-full lg:w-64 box pr-10" placeholder="Rechercher un médicament...">
                                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 text-slate-500" data-lucide="search"></i> 
                                </div>
                            </div>
                            <div id="medicament-list" class="grid grid-cols-12 gap-5 mt-5">
                                @foreach($stocks as $stock)
                                    @if($stock->medicament)
                                        <div 
                                            class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in medicament-item" 
                                            data-name="{{ strtolower($stock->medicament->nom) }}"
                                            data-id="{{ $stock->medicament->id }}"
                                            data-nom="{{ $stock->medicament->nom }}"
                                            data-prix="{{ $stock->medicament->prix }}"
                                            data-quantite="{{ $stock->quantité ?? 0 }}"
                                            data-ordonnance="{{ $stock->medicament->necessiteOrdonnance ?? 0 }}"

                                        >
                                            <div class="font-medium text-base">{{ $stock->medicament->nom }}</div>
                                            <div class="text-slate-500">{{ $stock->quantité ?? 0 }} en stock</div>
                                            <div class="text-slate-500">{{ $stock->medicament->prix ?? 0 }} MAD</div>
                                            <div class="mt-2">
                                                @if(isset($stock->medicament->necessiteOrdonnance) && $stock->medicament->necessiteOrdonnance)
                                                    <span class="text-warning font-semibold">Ordonnance requise</span>
                                                @else
                                                    <span class="text-success">Sans ordonnance</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <!-- END: Item List -->
                        <!-- BEGIN: Ticket -->
                        <div class="col-span-12 lg:col-span-4">
                            <div class="intro-y pr-1">
                                <div class="box p-2">
                                    <ul class="nav nav-pills" role="tablist">
                                        <li id="ticket-tab" class="nav-item flex-1" role="presentation">
                                            <button class="nav-link w-full py-2 active" data-tw-toggle="pill" data-tw-target="#ticket" type="button" role="tab" aria-controls="ticket" aria-selected="true" > Ticket </button>
                                        </li>
                                        <li id="details-tab" class="nav-item flex-1" role="presentation">
                                            <button class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#details" type="button" role="tab" aria-controls="details" aria-selected="false" > Détails </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-content">
                                <div id="ticket" class="tab-pane active" role="tabpanel" aria-labelledby="ticket-tab">
                                    <div id="ticket-items" class="box p-2 mt-5">
                                        <!-- Les éléments du ticket seront ajoutés dynamiquement ici -->
                                    </div>
                                    <div class="box mt-4">
                                        <div class="flex p-5 mt-5">
                                            <div class="mr-auto font-medium text-base">Total à payer</div>
                                            <div id="ticket-total" class="font-medium text-base">0 MAD</div>
                                        </div>
                                    </div>
                                    <div class="flex mt-5">
                                        <button id="clear-ticket" class="btn w-32 border-slate-300 dark:border-darkmode-400 text-slate-500">Vider</button>
                                        <button id="charge-btn" class="btn btn-primary w-32 shadow-md ml-auto">Encaisser</button>
                                    </div>
                                </div>
                                <div id="details" class="tab-pane" role="tabpanel" aria-labelledby="details-tab">
                                    <div class="box p-5 mt-5">
                                        <div class="flex items-center border-b border-slate-200 dark:border-darkmode-400 pb-5">
                                            <div>
                                                <div class="text-slate-500">Heure</div>
                                                <div class="mt-1" id="current-date"></div>
                                            </div>
                                            <i data-lucide="clock" class="w-4 h-4 text-slate-500 ml-auto"></i> 
                                        </div>
                                        <div class="flex items-center border-b border-slate-200 dark:border-darkmode-400 py-5">
                                            <div class="w-full">
                                                <div class="text-slate-500">Patient</div>
                                                <input 
                                                    type="text" 
                                                    id="patient-name" 
                                                    name="patient_name" 
                                                    class="form-control mt-2" 
                                                    placeholder="Entrer le nom du patient..." 
                                                    required
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: Ticket -->
                    </div>
                    <!-- BEGIN: Add/Edit Quantity Modal -->
                    <div id="edit-qty-modal" class="modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="font-medium text-base mr-auto" id="edit-qty-medicament-name"></h2>
                                </div>
                                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                    <div class="col-span-12">
                                        <label class="form-label">Quantité</label>
                                        <div class="flex mt-2 flex-1">
                                            <button type="button" id="edit-qty-minus" class="btn w-12 border-slate-200 bg-slate-100 dark:bg-darkmode-700 dark:border-darkmode-500 text-slate-500 mr-1">-</button>
                                            <input id="edit-qty-input" type="text" class="form-control w-24 text-center" value="1">
                                            <button type="button" id="edit-qty-plus" class="btn w-12 border-slate-200 bg-slate-100 dark:bg-darkmode-700 dark:border-darkmode-500 text-slate-500 ml-1">+</button>
                                        </div>
                                        <div class="text-xs text-slate-500 mt-2" id="edit-qty-stock-info"></div>
                                    </div>
                                </div>
                                <div class="modal-footer text-right">
                                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Annuler</button>
                                    <button type="button" id="edit-qty-confirm" data-tw-dismiss="modal" class="btn btn-primary w-30">Confirmer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Add/Edit Quantity Modal -->
                    <!-- BEGIN: Notification Toast -->
                    <div id="vente-success-toast" class="toastify on toastify-right toastify-top bg-success text-white hidden" style="z-index:9999;position:fixed;top:30px;right:30px;min-width:200px;">
                        Vente enregistrée avec succès !
                    </div>
                    <!-- END: Notification Toast -->
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
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('search-medicament');
                const items = document.querySelectorAll('.medicament-item');
                const ticketItems = document.getElementById('ticket-items');
                const ticketTotal = document.getElementById('ticket-total');
                const clearTicketBtn = document.getElementById('clear-ticket');

                // Modal elements
                const editQtyModal = document.getElementById('edit-qty-modal');
                const editQtyInput = document.getElementById('edit-qty-input');
                const editQtyPlus = document.getElementById('edit-qty-plus');
                const editQtyMinus = document.getElementById('edit-qty-minus');
                const editQtyConfirm = document.getElementById('edit-qty-confirm');
                const editQtyMedicamentName = document.getElementById('edit-qty-medicament-name');
                const editQtyStockInfo = document.getElementById('edit-qty-stock-info');

                let ticket = [];
                let currentEdit = null; // {id, nom, prix, quantiteStock, idx}

                // Search functionality
                searchInput.addEventListener('input', function () {
                    const value = this.value.trim().toLowerCase();
                    items.forEach(item => {
                        const name = item.getAttribute('data-name');
                        if (name.includes(value)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });

                // Add medicament to ticket on click (open quantity modal)
                items.forEach(item => {
                    item.addEventListener('click', function () {
                        const id = this.getAttribute('data-id');
                        const nom = this.getAttribute('data-nom');
                        const prix = parseFloat(this.getAttribute('data-prix'));
                        const quantiteStock = parseInt(this.getAttribute('data-quantite'));

                        // Check if already in ticket
                        let foundIdx = ticket.findIndex(m => m.id === id);
                        let found = foundIdx !== -1 ? ticket[foundIdx] : null;

                        currentEdit = {
                            id: id,
                            nom: nom,
                            prix: prix,
                            quantiteStock: quantiteStock,
                            idx: foundIdx
                        };

                        editQtyMedicamentName.textContent = nom;
                        editQtyStockInfo.textContent = `Stock disponible : ${quantiteStock}`;
                        editQtyInput.value = found ? found.quantite : 1;

                        // Show modal (Tailwind Modal)
                        if (window.tailwind && window.tailwind.Modal) {
                            window.tailwind.Modal.getOrCreateInstance(editQtyModal).show();
                        } else {
                            editQtyModal.style.display = 'block';
                        }
                    });
                });

                // Modal plus/minus buttons
                editQtyPlus.addEventListener('click', function () {
                    let val = parseInt(editQtyInput.value) || 1;
                    if (val < currentEdit.quantiteStock) {
                        editQtyInput.value = val + 1;
                    }
                });
                editQtyMinus.addEventListener('click', function () {
                    let val = parseInt(editQtyInput.value) || 1;
                    if (val > 1) {
                        editQtyInput.value = val - 1;
                    }
                });

                // Modal confirm button
                editQtyConfirm.addEventListener('click', function () {
                    let qty = parseInt(editQtyInput.value) || 1;
                    if (qty < 1) qty = 1;
                    if (qty > currentEdit.quantiteStock) qty = currentEdit.quantiteStock;

                    if (currentEdit.idx !== -1) {
                        ticket[currentEdit.idx].quantite = qty;
                    } else {
                        ticket.push({
                            id: currentEdit.id,
                            nom: currentEdit.nom,
                            prix: currentEdit.prix,
                            quantite: qty,
                            quantiteStock: currentEdit.quantiteStock
                        });
                    }
                    renderTicket();
                });

                // Render ticket items
                function renderTicket() {
                    ticketItems.innerHTML = '';
                    let total = 0;
                    ticket.forEach((item, idx) => {
                        total += item.prix * item.quantite;
                        ticketItems.innerHTML += `
                            <div class="flex items-center p-3 cursor-pointer transition duration-300 ease-in-out bg-white dark:bg-darkmode-600 hover:bg-slate-100 dark:hover:bg-darkmode-400 rounded-md mb-2">
                                <div class="max-w-[50%] truncate mr-1">${item.nom}</div>
                                <button class="ml-2 text-slate-500" onclick="editTicketItem(${idx})" title="Edit Quantity">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h6m2 0a2 2 0 100-4 2 2 0 000 4zm-6 0a2 2 0 100-4 2 2 0 000 4z"/></svg>
                                </button>
                                <div class="text-slate-500">x ${item.quantite}</div>
                                <button class="ml-2 text-slate-500" onclick="removeTicketItem(${idx})" title="Remove">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                <div class="ml-auto font-medium">${(item.prix * item.quantite).toFixed(2)} MAD</div>
                            </div>
                        `;
                    });
                    ticketTotal.textContent = total.toFixed(2) + ' MAD';
                }

                // Remove item from ticket
                window.removeTicketItem = function(idx) {
                    ticket.splice(idx, 1);
                    renderTicket();
                };

                // Edit quantity for ticket item
                window.editTicketItem = function(idx) {
                    const item = ticket[idx];
                    currentEdit = {
                        id: item.id,
                        nom: item.nom,
                        prix: item.prix,
                        quantiteStock: item.quantiteStock,
                        idx: idx
                    };
                    editQtyMedicamentName.textContent = item.nom;
                    editQtyStockInfo.textContent = `Stock disponible : ${item.quantiteStock}`;
                    editQtyInput.value = item.quantite;

                    // Show modal (Tailwind Modal)
                    if (window.tailwind && window.tailwind.Modal) {
                        window.tailwind.Modal.getOrCreateInstance(editQtyModal).show();
                    } else {
                        editQtyModal.style.display = 'block';
                    }
                };

                // Clear ticket
                clearTicketBtn.addEventListener('click', function () {
                    ticket = [];
                    renderTicket();
                });

                // Set current date/time in details tab
                function setCurrentDate() {
                    const dateElem = document.getElementById('current-date');
                    if (dateElem) {
                        const now = new Date();
                        // Format: DD/MM/YYYY HH:mm
                        const formatted = now.toLocaleDateString('fr-FR') + ' ' + now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
                        dateElem.textContent = formatted;
                    }
                }
                setCurrentDate();

                // Optionally, validate patient name before submitting the sale
                // Example: (if you add a form for sale submission)
                // document.getElementById('your-sale-form').addEventListener('submit', function(e) {
                //     const patientName = document.getElementById('patient-name').value.trim();
                //     if (!patientName) {
                //         e.preventDefault();
                //         alert('Patient name is required.');
                //     }
                // });

                // Toast notification function
                function showVenteSuccessToast() {
                    const toast = document.getElementById('vente-success-toast');
                    toast.classList.remove('hidden');
                    setTimeout(() => {
                        toast.classList.add('hidden');
                    }, 2500);
                }

                // Charge button functionality
                const chargeBtn = document.getElementById('charge-btn');

                chargeBtn.addEventListener('click', function () {
                    // Validate patient name
                    const patientName = document.getElementById('patient-name').value.trim();
                    if (!patientName) {
                        alert('Patient name is required.');
                        document.getElementById('details-tab').click();
                        document.getElementById('patient-name').focus();
                        return;
                    }
                    if (ticket.length === 0) {
                        alert('No medicament selected.');
                        return;
                    }

                    // Prepare data
                    const data = {
                        patient_name: patientName,
                        items: ticket.map(item => ({
                            medicament_id: item.id,
                            quantite: item.quantite,
                            prix: item.prix
                        })),
                        total: ticket.reduce((sum, item) => sum + item.prix * item.quantite, 0)
                    };

                    // Send AJAX POST to store sale
                    fetch("{{ route('makeSale.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(data)
                    })
                    .then(async response => {
                        if (response.ok) {
                            showVenteSuccessToast();
                            setTimeout(() => window.location.reload(), 1200);
                        } else {
                            let msg = "Erreur lors de l'enregistrement de la vente.";
                            try {
                                const res = await response.json();
                                if (res && res.message) msg = res.message;
                            } catch {}
                            alert(msg);
                        }
                    })
                    .catch(() => alert("Erreur lors de l'enregistrement de la vente."));
                });

                // Initial render
                renderTicket();
            });
        </script>
    </body>
</html>