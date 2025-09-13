<!DOCTYPE html>
<html lang="fr" class="light">
    <head>
        <meta charset="utf-8">
        <link href="{{ asset('dist/images/logo.svg') }}" rel="shortcut icon">
        <title>Connexion - MediTrack</title>
        <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" />
    </head>
    <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Login Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="" class="-intro-x flex items-center pt-5">
                        <img alt="Midone - HTML Admin Template" class="w-6" src="{{ asset('dist/images/logo.svg') }}">
                        <span class="text-white text-lg ml-3"> MediTrack </span> 
                    </a>
                    <div class="my-auto">
                        <img alt="Midone - HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="{{ asset('dist/images/illustration.svg') }}">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            Quelques clics de plus pour 
                            <br>
                            vous connecter à votre compte.
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">Gérez toutes vos ordonnances en un seul endroit</div>
                    </div>
                </div>
                <!-- BEGIN: Verify Email Form -->
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            Vérification de l'e-mail
                        </h2>
                        <div class="intro-x mt-2 text-slate-400 xl:hidden text-center">
                            Merci de vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer.<br>
                            Si vous n'avez pas reçu l'e-mail, vous pouvez en demander un nouveau ci-dessous.
                        </div>
                        @if (session('message'))
                            <div class="alert alert-success mt-4">
                                {{ session('message') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <div class="intro-x mt-8 text-center">
                                <button type="submit" class="btn btn-primary py-3 px-4 w-full xl:w-32 align-top">Renvoyer l'e-mail de vérification</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END: Verify Email Form -->

            </div>
        </div>
        <!-- BEGIN: JS Assets-->
        <script src="{{ asset('dist/js/app.js') }}"></script>
        <!-- END: JS Assets-->
    </body>
</html>