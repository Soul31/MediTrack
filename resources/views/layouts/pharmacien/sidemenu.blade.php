                <nav class="side-nav">
                    <ul>
                        <!-- Dashboard -->
                        <li>
                            <a href="{{ route('dashboard') }}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                                <div class="side-menu__title"> Tableau de bord </div>
                            </a>
                        </li>
                
                        <!-- Stock -->
                        <li>
                            <a href="javascript:;" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="box"></i> </div>
                                <div class="side-menu__title">
                                    Stock
                                    <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
                                </div>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('stocklist') }}" class="side-menu">
                                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                                        <div class="side-menu__title"> Liste de stock </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('newproduct')}}" class="side-menu">
                                        <div class="side-menu__icon"> <i data-lucide="plus-square"></i> </div>
                                        <div class="side-menu__title"> Ajouter des articles </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                
                        <!-- Make Sale -->
                        <li>
                            <a href="{{route("makeSale")}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="shopping-cart"></i> </div>
                                <div class="side-menu__title"> Effectuer une vente </div>
                            </a>
                        </li>
                
                        <!-- History -->
                        <li>
                            <a href="{{route("commandes")}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="clock"></i> </div>
                                <div class="side-menu__title"> Transactions </div>
                            </a>
                        </li>
                
                        <!-- Report Creation -->
                        <li>
                            <a href="{{route("profile")}}" class="side-menu">
                                <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                                <div class="side-menu__title"> Profil </div>
                            </a>
                        </li>
                    </ul>
                </nav>