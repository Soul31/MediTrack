<x-layout :title="$title" :breadcrum="$breadcrum">
    <x-sidebar>
        <!-- Medicaments -->
        <li>
            <a href="{{ route('docteur-medicaments') }}" class="side-menu @if($activePage == '1') side-menu--active @endif">
                <div class="side-menu__icon"> <i data-lucide="briefcase"></i> </div>
                <div class="side-menu__title"> Médicaments  </div>
            </a>
        </li>
        <!-- Ordonnances -->
        <li>
            <a href="{{ route('docteur-ordonnances') }}" class="side-menu  @if($activePage == '2') side-menu--active @endif">
                <div class="side-menu__icon"> <i data-lucide="clipboard"></i> </div>
                <div class="side-menu__title"> Ordonnances </div>
            </a>
        </li>
    </x-sidebar>
    <!-- BEGIN: Content -->
    <div class="content">
        {{ $slot }}
    </div>
    <!-- END: Content -->
    <!-- SheetJS for Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <!-- jsPDF and AutoTable for PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</x-layout>
