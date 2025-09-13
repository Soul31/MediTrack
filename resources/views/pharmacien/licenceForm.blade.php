<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <link href="{{ asset('dist/images/logo.svg') }}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Licence - MediTrack</title>
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" />
</head>
<body class="main">
    @include('layouts.topbar', ['pageTitle' => 'Formulaire de Licence'])
    <div class="wrapper wrapper">
        <div class="wrapper-box">
            <div class="content">
                <div class="intro-y flex items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Formulaire de Licence
                    </h2>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 flex justify-center mx-auto">
                        <div class="intro-y box w-full">
                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">
                                    Entrez vos informations de licence
                                </h2>
                            </div>
                            <div class="p-5">
                                @if(session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                <form method="POST" action="{{ route('licence.form.submit') }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label">Nom</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->nom }}" disabled>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                                    </div>
                                    <div class="mb-4">
                                        <label for="licence" class="form-label">Numéro de licence</label>
                                        <input id="licence" name="licence" type="text" class="form-control @error('licence') border-danger @enderror" placeholder="Entrez votre numéro de licence" value="{{ old('licence') }}">
                                        @error('licence')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Vérifier le mot de passe</label>
                                        <input id="password" name="password" type="password" class="form-control @error('password') border-danger @enderror" placeholder="Entrez votre mot de passe">
                                        @error('password')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3 w-full">Soumettre</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Vous pouvez ajouter plus de contenu ou des images dans les autres colonnes si nécessaire -->
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('dist/js/app.js') }}"></script>
</body>
</html>