<!DOCTYPE html>
<html lang="en" class="light">
    <head>
        <meta charset="utf-8">
        <link href="dist/images/logo.svg" rel="shortcut icon">
        <title>Mettre à jour le profil - MediTrack</title>
        <link rel="stylesheet" href="dist/css/app.css" />
    </head>
    <body class="main">
        @include('layouts.topbar', ['pageTitle' => 'Mettre à jour le profil'])
        <div class="wrapper">
            <div class="wrapper-box">
                @include('layouts.' . Auth::user()->role . '.sidemenu')
                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Mettre à jour le profil
                        </h2>
                    </div>
                    <div class="grid grid-cols-12 gap-6">
                        <!-- BEGIN: Profile Menu -->
                        @include('layouts.' . Auth::user()->role . '.profilemenu')
                        <!-- END: Profile Menu -->
                        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
                            <!-- BEGIN: Display Information -->
                            <div class="intro-y box lg:mt-5">
                                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                    <h2 class="font-medium text-base mr-auto">
                                        Informations affichées
                                    </h2>
                                </div>
                                <div class="p-5">
                                    <form method="POST" action="{{ route('profile.update') }}">
                                        @csrf
                                        @method('POST')
                                        <div class="flex flex-col-reverse xl:flex-row flex-col">
                                            <div class="flex-1 mt-6 xl:mt-0">
                                                <div class="grid grid-cols-12 gap-x-5">
                                                    <div class="col-span-12 2xl:col-span-6">
                                                        <div>
                                                            <label for="nom" class="form-label">Prénom</label>
                                                            <input id="nom" name="nom" type="text" class="form-control" value="{{ Auth::user()->nom }}">
                                                        </div>
                                                        <div class="mt-3">
                                                            <label for="prenom" class="form-label">Nom</label>
                                                            <input id="prenom" name="prenom" type="text" class="form-control" value="{{ Auth::user()->prenom }}">
                                                        </div>
                                                        <div class="mt-3">
                                                            <label for="email" class="form-label">E-mail</label>
                                                            <input id="email" name="email" type="email" class="form-control" value="{{ Auth::user()->email }}">
                                                        </div>
                                                        <!-- Add other fields as needed -->
                                                    </div>
                                                    <div class="col-span-12 2xl:col-span-6">
                                                        <div class="mt-3 2xl:mt-0">
                                                            <label for="phone" class="form-label">Numéro de téléphone</label>
                                                            <input id="phone" name="phone" type="text" class="form-control" value="{{ Auth::user()->phone ?? '' }}">
                                                        </div>
                                                        <div class="mt-3">
                                                            <label for="address" class="form-label">Adresse</label>
                                                            <textarea id="address" name="address" class="form-control">{{ Auth::user()->address ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-12">
                                                    <div class="mt-3">
                                                        <label for="current_password" class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                                                        <input id="current_password" name="current_password" type="password" class="form-control" required>
                                                        @error('current_password')
                                                            <div class="text-danger mt-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary w-20 mt-3">Enregistrer</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- END: Display Information -->
                        </div>
                    </div>
                </div>
                <!-- END: Content -->
            </div>
        </div>
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        <script src="dist/js/app.js"></script>
    </body>
</html>