<x-patient.playout title="Ordonnances | Patient" :breadcrum="['Ordonnances']" activePage="4">

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des Ordonnances
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap xl:flex-nowrap items-center mt-2">
            <div class="flex w-full sm:w-auto">
                <select class="form-select justify-start box ml-2" id="select-statut">
                    <option value="0">statut</option>
                    <option value="1">en attente</option>
                    <option value="2">validé</option>
                    <option value="3">livré</option>
                    <option value="4">refusé</option>
                </select>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto 2xl:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                <tr>
                    <th class="whitespace-nowrap">ID Ordonnance</th>
                    <th class="text-center whitespace-nowrap">STATUT</th>
                    <th class="text-right whitespace-nowrap">
                        <div class="pr-16">TOTAL Ordonnance</div>
                    </th>
                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                </tr>
                </thead>
                <tbody>
                @foreach($ordonnances as $ordo)
                    <tr class="intro-x ordonnance-row" data-id="{{ $ordo->id }}" data-statut="{{ $ordo->statut }}">

                        <td class="w-40">
                            <div class="font-medium whitespace-nowrap">{{ $ordo->id }}</div>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center whitespace-nowrap
                            @switch($ordo->statut)
                                @case('en attente')
                                 text-pending
                                 @break
                                @case('valide')
                                 text-success
                                 @break
                                @case('refus')
                                 text-danger
                                 @break
                                @case('livre')
                                 text-gray-600
                                 @break
                            @endswitch
                        "> <i data-lucide="check-square" class="w-4 h-4 mr-2"></i> {{ $ordo->statut }} </div>
                        </td>
                        <td class="w-40 text-right">
                            <div class="pr-16">{{ $ordo->total }} DH</div>
                        </td>
                        <td class="table-report__action">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center text-primary whitespace-nowrap mr-5" href="{{ route('patient-ordonnance', [$ordo->id]) }}"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Voir les Détails </a>
                                <a class="flex items-center text-primary whitespace-nowrap" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="arrow-left-right" class="w-4 h-4 mr-1"></i> Annuler Ordonnance </a>
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
                <ul class="pagination">

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
                        <div class="text-3xl mt-5">Vous êtes sûr?</div>
                        <div class="text-slate-500 mt-2">
                            Voulez-vous vraiment annuler cette ordonnance ?
                            <br>
                            Ce processus ne peut pas être annulé.
                        </div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Annuler</button>
                        <button type="button" class="btn btn-danger w-auto">Annuler l'Ordonnance</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Delete Confirmation Modal -->
    <script>
        // Global variables
        let currentPage = 1;
        let rowsPerPage = 15;
        let allRows = Array.from(document.querySelectorAll('.ordonnance-row'));
        let filteredRows = allRows;

        document.getElementById('select-statut').addEventListener('change', function () {
            value = this.value;
            if (value === "1") {
                filteredRows = allRows.filter(item => item.dataset.statut === 'en attente');
            } else if (value === "2") {
                filteredRows = allRows.filter(item => item.dataset.statut === 'valide')
            } else if (value === "3") {
                filteredRows = allRows.filter(item => item.dataset.statut === 'livre');
            } else if (value === "4") {
                filteredRows = allRows.filter(item => item.dataset.statut === 'refus');
            } else {
                filteredRows = allRows;
            }
            displayRows();
            setupPagination();
        });

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            displayRows();
            setupPagination();
            lucide.createIcons();
        });

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


        // Delete Confirmation Modal Handler
        document.addEventListener('DOMContentLoaded', function() {
            // Store the commande ID when delete button is clicked
            let ordonnanceIdToDelete = null;

            // Set up event listeners for all delete buttons
            document.querySelectorAll('[data-tw-toggle="modal"][data-tw-target="#delete-confirmation-modal"]').forEach(button => {
                button.addEventListener('click', function() {
                    // Get the commande ID from the parent row
                    const row = this.closest('.ordonnance-row');
                    ordonnanceIdToDelete = row.dataset.id;
                });
            });

            // Handle the actual delete confirmation
            document.querySelector('#delete-confirmation-modal .btn-danger').addEventListener('click', function() {
                if (!ordonnanceIdToDelete) return;

                // Create a form dynamically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('patient-delete-ordonnance', '') }}/${ordonnanceIdToDelete}`;
                form.style.display = 'none';

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Submit the form
                document.body.appendChild(form);
                form.submit();
            });

            // Close modal after submission
            const modal = tailwind.Modal.getOrCreateInstance(document.getElementById('delete-confirmation-modal'));
            modal.hide();
        });
    </script>
</x-patient.playout>
