<div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
        <a href="" class="flex mr-auto">
            <img alt="MediTrack logo" class="w-6" src="/dist/images/logo.svg">
        </a>
        <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
    </div>
    <div class="scrollable">
        <a href="javascript:;" class="mobile-menu-toggler"> <i data-lucide="x-circle" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
        <ul class="scrollable__content py-2">
{{--            <li>--}}
{{--                <a href="javascript:;" class="menu">--}}
{{--                    <div class="menu__icon"> <i data-lucide="home"></i> </div>--}}
{{--                    <div class="menu__title"> Dashboard <i data-lucide="chevron-down" class="menu__sub-icon "></i> </div>--}}
{{--                </a>--}}
{{--            </li>--}}
            <!-- Point de vente -->
            <li>
                <a href="{{ route('patient-point-de-vente') }}" class="menu">
                    <div class="side-menu__icon"> <i data-lucide="credit-card"></i> </div>
                    <div class="side-menu__title"> Point de vente </div>
                </a>
            </li>
            <!-- Medicaments -->
            <li>
                <a href="{{ route('patient-medicaments') }}" class="menu">
                    <div class="side-menu__icon"> <i data-lucide="briefcase"></i> </div>
                    <div class="side-menu__title"> Médicaments  </div>
                </a>
            </li>
            <!-- commandes -->
            <li>
                <a href="{{ route('patient-commandes') }}" class="menu">
                    <div class="side-menu__icon"> <i data-lucide="shopping-bag"></i> </div>
                    <div class="side-menu__title"> Commandes </div>
                </a>
            </li>
            <!-- ordonnances -->
            <li>
                <a href="{{ route('patient-ordonnances') }}" class="menu">
                    <div class="side-menu__icon"> <i data-lucide="clipboard"></i> </div>
                    <div class="side-menu__title"> Ordonnances </div>
                </a>
            </li>
        </ul>
    </div>
</div>
