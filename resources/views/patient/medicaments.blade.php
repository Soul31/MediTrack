<x-patient.playout title="Médicaments | Patient" :breadcrum="['Médicaments']" activePage="2">
    <h2 class="intro-y text-lg font-medium mt-10 w-auto">
        Liste des Médicaments
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex justify-end flex-wrap sm:flex-nowrap items-center mt-2">

            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" id="search-input" class="form-control w-56 box pr-10" placeholder="Search...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                <tr>
                    <th class="whitespace-nowrap">NOM</th>
                    <th class="whitespace-nowrap">PRIX</th>
                    <th class="text-center whitespace-nowrap">DOSAGE</th>
                    <th class="text-center whitespace-nowrap">NECESSITE ORDONNANCE</th>
                </tr>
                </thead>
                <tbody>
                @foreach($medicaments as $med)
                <tr class="intro-x hidden medicament-row" data-nom="{{ $med->nom }}">
                    <td>
                        <div class="font-medium w-48 truncate whitespace-nowrap" title="{{ $med->nom }}">{{ $med->nom }}</div>
                    </td>
                    <td class="text-center">{{ $med->prix }} DH</td>
                    <td class="text-center">{{ $med->dosage }} mg</td>
                    <td class="w-40">
                        <div class="flex items-center justify-center @if($med->necessiteOrdonnance) text-success @else text-danger @endif">
                            <i data-lucide="check-square" class="w-4 h-4 mr-2"></i>
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
    <script>

        // Global variables
        let currentPage = 1;
        let rowsPerPage = 15;
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

</x-patient.playout>
