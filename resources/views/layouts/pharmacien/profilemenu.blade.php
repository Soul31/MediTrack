                        <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
                            <div class="intro-y box mt-5">
                                <div class="relative flex items-center p-5">
                                    <div class="w-12 h-12 image-fit">
                                        <img alt="Midone - HTML Admin Template" class="rounded-full" src="dist/images/profile-3.png">
                                    </div>
                                    <div class="ml-4 mr-auto">
                                        <div class="font-medium text-base">{{Auth::user()->nom}}</div>
                                        <div class="text-slate-500">{{Auth::user()->role}}</div>
                                    </div>
                                </div>
                                <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                                    <a class="flex items-center mt-5" href="{{route("profile")}}"> <i data-lucide="activity" class="w-4 h-4 mr-2"></i> Informations personnelles </a>
                                    <a class="flex items-center mt-5" href="{{route("stocklist")}}"> <i data-lucide="box" class="w-4 h-4 mr-2"></i> Vérifier le stock </a>
                                </div>
                                <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
                                    <a class="flex items-center mt-5" href="{{route("change-password")}}"> <i data-lucide="lock" class="w-4 h-4 mr-2"></i> Changer le mot de passe </a>
                                    <a class="flex items-center mt-5" href="{{route("update-profile")}}"> <i data-lucide="settings" class="w-4 h-4 mr-2"></i> Paramètres utilisateur </a>
                                </div>
                                <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400 flex">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                 @csrf
                                 <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                 <button type="button" class="btn btn-primary py-1 px-2">Se déconnecter</button> 
                                    </a> 
                                </form>
                                </div>
                            </div>
                        </div>