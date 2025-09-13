<x-layout :title="$title" :breadcrum="$breadcrum" >

    <x-sidebar>
        <!-- Point de vente -->
        <li>
            <a href="{{ route('patient-point-de-vente') }}" class="side-menu @if($activePage == '1') side-menu--active @endif">
                <div class="side-menu__icon"> <i data-lucide="credit-card"></i> </div>
                <div class="side-menu__title"> Point de vente </div>
            </a>
        </li>
        <!-- Medicaments -->
        <li>
            <a href="{{ route('patient-medicaments') }}" class="side-menu @if($activePage == '2') side-menu--active @endif">
                <div class="side-menu__icon"> <i data-lucide="briefcase"></i> </div>
                <div class="side-menu__title"> Médicaments  </div>
            </a>
        </li>
        <!-- commandes -->
        <li>
            <a href="{{ route('patient-commandes') }}" class="side-menu @if($activePage == '3') side-menu--active @endif">
                <div class="side-menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                <div class="side-menu__title"> Commandes </div>
            </a>
        </li>
        <!-- ordonnances -->
        <li>
            <a href="{{ route('patient-ordonnances') }}" class="side-menu @if($activePage == '4') side-menu--active @endif">
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
</x-layout>
