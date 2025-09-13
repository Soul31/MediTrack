<x-docteur.dlayout title="Nouvelle Ordonnance | Docteur" :breadcrum="['Nouvelle Ordonnance']" activePage="-1">

    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Nouvelle Ordonnance
        </h2>
    </div>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <!-- BEGIN: Item List -->
        <div class="intro-y col-span-12 xl:col-span-8">
            <div class="lg:flex intro-y">
                <div class="relative">
                    <input id="search-input" type="text" class="form-control py-3 px-4 w-full lg:w-64 box pr-10" placeholder="Search item...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0 text-slate-500" data-lucide="search"></i>
                </div>
            </div>
            <!-- Liste des medicaments -->
            <div class="grid grid-cols-12 gap-5 mt-5 pt-5 border-t">
                <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                    <table class="table table-report -mt-2">
                        <thead>
                        <tr>
                            <th class="whitespace-nowrap">NOM</th>
                            <th class="whitespace-nowrap">PRIX</th>
                            <th class="text-center whitespace-nowrap">DOSAGE</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($medicaments as $med)
                            <tr class="intro-x medicament-row zoom-in hidden"
                                data-id="{{ $med->id }}"
                                data-nom="{{ $med->nom }}"
                                data-prix="{{ $med->prix }}"
                                data-tw-toggle="modal"
                                data-tw-target="#add-item-modal">
                                <td>
                                    <div class="font-medium w-48 truncate whitespace-nowrap" title="{{ $med->nom }}">{{ $med->nom }}</div>
                                </td>
                                <td class="text-center">{{ $med->prix }} DH</td>
                                <td class="text-center">{{ $med->dosage }} mg</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- END: Data List -->
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->
            </div>
        </div>
        <!-- END: Item List -->
        <!-- BEGIN: Panier -->
        <div class="col-span-12 xl:col-span-4">
            <div class="tab-content">
                <div id="ticket" class="tab-pane active" role="tabpanel" aria-labelledby="ticket-tab">
                    <div class="box p-2 mt-5">
                        <!-- Articles du panier s'afficheront ici -->
                        <div id="liste-panier">
                            <!-- Items will be added here dynamically -->
                            Ajouter des médicaments
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-200/60 dark:border-darkmode-400">
                            <div class="mr-auto font-medium text-base">Choisir un Patient</div>
                            <div class="font-medium text-base">
                                <div class="flex">
                                    <div class="z-30 rounded-l w-10 flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400 -mr-1">
                                        @
                                    </div>
                                    <select class="tom-select w-48" id="patient-id">
                                        @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">
                                            {{ $patient->user->nom.' '.$patient->user->prenom }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="flex mt-4 pt-4 border-t border-slate-200/60 dark:border-darkmode-400">
                            <div class="mr-auto font-medium text-base">Total à payer</div>
                            <div class="font-medium text-base total-panier">0.00 DH</div>
                        </div>
                    </div>
                    <div class="flex mt-5">
                        <button id="btn-vider" class="btn w-32 border-slate-300 dark:border-darkmode-400 text-slate-500">Vider</button>
                        <button id="btn-facturer" class="btn btn-primary w-32 shadow-md ml-auto">Facturer</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Panier -->
    </div>
    <!-- BEGIN: Add Item Modal -->
    <div id="add-item-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto" id="nom-medicament">

                    </h2>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label for="pos-form-4" class="form-label">Quantité</label>
                        <div class="flex mt-2 flex-1">
                            <button type="button" id="minus-button" class="btn w-12 border-slate-200 bg-slate-100 dark:bg-darkmode-700 dark:border-darkmode-500 text-slate-500 mr-1">-</button>
                            <input id="quantite" type="text" class="form-control w-24 text-center" placeholder="quantié de médicament" value="1">
                            <button type="button" id="plus-button" class="btn w-12 border-slate-200 bg-slate-100 dark:bg-darkmode-700 dark:border-darkmode-500 text-slate-500 ml-1">+</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Annuler</button>
                    <button type="button" id="btn-ajouter" data-tw-dismiss="modal" class="btn btn-primary w-30">Ajouter médicament</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Add Item Modal -->

    <script>
        // Global variables
        let panier = [];
        let currentMedicament = null;

        // Add item modal functions
        const quantite = document.getElementById('quantite');

        function plus() {
            if (!quantite.value || isNaN(quantite.value)) quantite.value = 1;
            else quantite.value = parseInt(quantite.value) + 1;
        }

        function minus() {
            if (!quantite.value || isNaN(quantite.value)) quantite.value = 1;
            else if (parseInt(quantite.value) > 1) {
                quantite.value = parseInt(quantite.value) - 1;
            }
        }

        document.getElementById('plus-button').addEventListener('click', plus);
        document.getElementById('minus-button').addEventListener('click', minus);

        // When clicking on a medicament row
        document.querySelectorAll('.medicament-row').forEach(row => {
            row.addEventListener('click', () => {
                currentMedicament = {
                    id: row.dataset.id,
                    nom: row.dataset.nom,
                    prix: parseFloat(row.dataset.prix)
                };
                document.getElementById('nom-medicament').textContent = currentMedicament.nom;
                const existant = panier.find(item => item.id === currentMedicament.id);
                quantite.value = existant ? existant.quantite : 1;
            });
        });

        // Add to cart button
        document.getElementById('btn-ajouter').addEventListener('click', () => {
            if (currentMedicament) {
                const qty = parseInt(quantite.value) || 1;
                ajouterAuPanier(currentMedicament.id, currentMedicament.nom, currentMedicament.prix, qty);

                // Hide the modal
                const modal = document.getElementById('add-item-modal');
                const modalInstance = tailwind.Modal.getOrCreateInstance(modal);
                modalInstance.hide();
            }
        });

        // Cart functions
        function ajouterAuPanier(id, nom, prix, quantite = 1) {
            const existant = panier.find(item => item.id === id);
            if (quantite < 1) {
                alert('entrer un nombre > 0');
                return;
            }
            if (existant) {
                existant.quantite = quantite;
            } else {
                panier.push({ id, nom, prix, quantite });
            }
            afficherPanier();
        }

        function viderPanier() {
            panier = [];
            afficherPanier();
        }
        function removeMed(id) {
            panier = panier.filter(item => item.id !== `${id}`);
            afficherPanier();
        }

        function afficherPanier() {
            const conteneur = document.getElementById('liste-panier');
            conteneur.innerHTML = '';
            if (panier.length == 0) {
                conteneur.innerHTML += `Ajouter des médicaments`;
                document.querySelector('.total-panier').textContent = (0.0).toFixed(2) + ' DH';
                return;
            }
            let total = 0;
            panier.forEach((item, index) => {
                total += item.prix * item.quantite;
                const container = document.createElement('div');
                container.className = 'flex flex-columns';
                const div = document.createElement('a');
                div.setAttribute('data-tw-toggle', 'modal');
                div.setAttribute('data-tw-target', '#add-item-modal');
                div.setAttribute('data-id', item.id);
                div.setAttribute('data-nom', item.nom);
                div.setAttribute('data-prix', item.prix);
                div.className = 'flex items-center p-3 w-full cursor-pointer transition duration-300 ease-in-out bg-white dark:bg-darkmode-600 hover:bg-slate-100 dark:hover:bg-darkmode-400 rounded-md';
                div.innerHTML = `
                    <div class="w-32 truncate truncate mr-1">${item.nom}</div>
                    <div class="text-slate-500">x ${item.quantite}</div>
                    <i data-lucide="edit" class="w-4 h-4 text-slate-500 ml-2"></i>
                    <div class="ml-auto font-medium">${(item.prix * item.quantite).toFixed(2)} DH</div>
                `;
                const rmbutton = document.createElement('button');
                rmbutton.className = 'w-8';
                rmbutton.innerHTML = `<i data-lucide="trash" class="w-4 h-4 text-slate-500 ml-2"></i>`;
                rmbutton.setAttribute('onclick', `removeMed(${item.id})`);
                container.appendChild(div);
                container.appendChild(rmbutton);
                conteneur.appendChild(container);
            });

            document.querySelector('.total-panier').textContent = total.toFixed(2) + ' DH';
            lucide.createIcons();
        }

        // Button events
        document.getElementById('btn-vider').addEventListener('click', viderPanier);
        document.getElementById('btn-facturer').addEventListener('click', () => {
            if (panier.length === 0) {
                alert('Le panier est vide!');
                return;
            }

            // Create a form dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("docteur-ordonnance-store") }}';
            form.style.display = 'none';

            // Add CSRF token (Laravel specific)
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add patient ID
            const patientId = document.getElementById('patient-id');
            const patientIdInput = document.createElement('input');
            patientIdInput.type = 'hidden';
            patientIdInput.name = 'patient_id';
            patientIdInput.value = patientId.value;
            form.appendChild(patientIdInput);

            // Add each medicament to the form
            panier.forEach((item, index) => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = `medicaments[${index}][id]`;
                idInput.value = item.id;
                form.appendChild(idInput);

                const qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = `medicaments[${index}][quantite]`;
                qtyInput.value = item.quantite;
                form.appendChild(qtyInput);
            });
            // Submit the form
            document.body.appendChild(form);
            form.submit();
        });

        // Global variables
        let currentPage = 1;
        let rowsPerPage = 10;
        let allRows = Array.from(document.querySelectorAll('.medicament-row'));
        let filteredRows = allRows;


        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initSearch();
            displayRows();
            setupPagination();
            lucide.createIcons();
        });

        // Search functionality
        function initSearch() {
            const searchInput = document.getElementById('search-input');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                // Filter rows based on search term
                filteredRows = allRows.filter(row => {
                    const name = row.dataset.nom.toLowerCase();

                    return name.includes(searchTerm);
                });

                // Reset to first page after search
                currentPage = 1;

                // Update display and pagination
                displayRows();
                setupPagination();
            });
        }

        // Display rows for current page
        function displayRows() {
            // Hide all rows first
            allRows.forEach(row => row.classList.add('hidden'));

            // Calculate which rows to show
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            // Show only the rows for current page
            filteredRows.slice(start, end).forEach(row => {
                row.classList.remove('hidden');
            });
        }
        function changePage(page) {
            const pageCount = Math.ceil(filteredRows.length / rowsPerPage);
            if (page > pageCount || page < 1) return;
            currentPage = page;
            displayRows();
            setupPagination();
        }

        // Pagination setup
        function setupPagination() {
            const pagination = document.querySelector('.pagination');
            const pageCount = Math.ceil(filteredRows.length / rowsPerPage);

            pagination.innerHTML = '';
            if (pageCount <= 1) return;
            // Previous buttons
            pagination.innerHTML += `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <button class="page-link" id="first-page" onclick="changePage(1)"><i class="w-4 h-4" data-lucide="chevrons-left"></i></button>
                </li>
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <button class="page-link" id="prev-page" onclick="changePage(${currentPage - 1})"><i class="w-4 h-4" data-lucide="chevron-left"></i></button>
                </li>
            `;

            // Page numbers - show up to 5 pages around current page
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(pageCount, currentPage + 2);

            if (startPage > 1) {
                pagination.innerHTML += `<li class="page-item"><button class="page-link" >...</button></li>`;
            }

            for (let i = startPage; i <= endPage; i++) {
                pagination.innerHTML += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <button class="page-link page-number" data-page="${i}" onclick="changePage(${i})">${i}</button>
                    </li>
                `;
            }

            if (endPage < pageCount) {
                pagination.innerHTML += `<li class="page-item"><a class="page-link" href="#">...</a></li>`;
            }

            // Next buttons
            pagination.innerHTML += `
                <li class="page-item ${currentPage === pageCount ? 'disabled' : ''}">
                    <button class="page-link" id="next-page" onclick="changePage(${currentPage + 1})"><i class="w-4 h-4" data-lucide="chevron-right"></i></button>
                </li>
                <li class="page-item ${currentPage === pageCount ? 'disabled' : ''}">
                    <button class="page-link" id="last-page" onclick="changePage(${pageCount})"><i class="w-4 h-4" data-lucide="chevrons-right"></i></button>
                </li>
            `;

            // Reinitialize Lucide icons
            lucide.createIcons();
        }
    </script>
</x-docteur.dlayout>
