<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <link href="dist/images/logo.svg" rel="shortcut icon">
    <title>MediTrack - Ajouter un médicament</title>
    <link rel="stylesheet" href="dist/css/app.css" />
</head>
<body class="main">
    @include('layouts.topbar',['pageTitle' => 'Ajouter un médicament'])
    <div class="wrapper">
        <div class="wrapper-box">
            @include('layouts.pharmacien.sidemenu')
            <!-- BEGIN: Content -->
            <div class="content">
                <div class="intro-y flex items-center mt-8">
                    <h2 class="text-lg font-medium mr-auto">
                        Ajouter un médicament
                    </h2>
                </div>
                <form action="{{ route('newproduct.store') }}" method="POST" class="grid grid-cols-13 gap-x-6 mt-5 pb-20">
                    @csrf
                    <div class="intro-y col-span-11 2xl:col-span-9">
                        <!-- BEGIN: Medicament Information -->
                        <div class="intro-y box p-5 mt-5">
                            <div class="border border-slate-200/60 dark:border-darkmode-400 rounded-md p-5">
                                <div class="font-medium text-base flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5">
                                    <i data-lucide="chevron-down" class="w-4 h-4 mr-2"></i> Informations sur le médicament
                                </div>
                                <div class="mt-5">
                                    <div class="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                        <div class="form-label xl:w-64 xl:!mr-10">
                                            <div class="text-left">
                                                <div class="flex items-center">
                                                    <div class="font-medium">Nom</div>
                                                    <div class="ml-2 px-2 py-0.5 bg-slate-200 text-slate-600 text-xs rounded-md">Obligatoire</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full mt-3 xl:mt-0 flex-1">
                                            <input name="nom" type="text" class="form-control" placeholder="Nom du médicament" required>
                                        </div>
                                    </div>
                                    <div class="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                        <div class="form-label xl:w-64 xl:!mr-10">
                                            <div class="text-left">
                                                <div class="flex items-center">
                                                    <div class="font-medium">Détails</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full mt-3 xl:mt-0 flex-1">
                                            <textarea name="detailles" class="form-control" placeholder="Détails"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                        <div class="form-label xl:w-64 xl:!mr-10">
                                            <div class="text-left">
                                                <div class="flex items-center">
                                                    <div class="font-medium">Dosage</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full mt-3 xl:mt-0 flex-1">
                                            <input name="dosage" type="text" class="form-control" placeholder="Dosage">
                                        </div>
                                    </div>
                                    <div class="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                        <div class="form-label xl:w-64 xl:!mr-10">
                                            <div class="text-left">
                                                <div class="flex items-center">
                                                    <div class="font-medium">Prix (MAD)</div>
                                                    <div class="ml-2 px-2 py-0.5 bg-slate-200 text-slate-600 text-xs rounded-md">Obligatoire</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full mt-3 xl:mt-0 flex-1">
                                            <input name="prix" type="number" step="0.01" class="form-control" placeholder="Prix" required>
                                        </div>
                                    </div>
                                    <div class="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                        <div class="form-label xl:w-64 xl:!mr-10">
                                            <div class="text-left">
                                                <div class="flex items-center">
                                                    <div class="font-medium">Nécessite ordonnance</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full mt-3 xl:mt-0 flex-1">
                                            <select name="necessiteOrdonnance" class="form-select">
                                                <option value="0">Non</option>
                                                <option value="1">Oui</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: Medicament Information -->
                        <!-- BEGIN: Stock Information -->
                        <div class="intro-y box p-5 mt-5">
                            <div class="border border-slate-200/60 dark:border-darkmode-400 rounded-md p-5">
                                <div class="font-medium text-base flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5">
                                    <i data-lucide="chevron-down" class="w-4 h-4 mr-2"></i> Informations sur le stock
                                </div>
                                <div class="mt-5">
                                    <div class="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                        <div class="form-label xl:w-64 xl:!mr-10">
                                            <div class="text-left">
                                                <div class="flex items-center">
                                                    <div class="font-medium">Quantité en stock</div>
                                                    <div class="ml-2 px-2 py-0.5 bg-slate-200 text-slate-600 text-xs rounded-md">Obligatoire</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full mt-3 xl:mt-0 flex-1">
                                            <input name="quantite" type="number" min="0" class="form-control" placeholder="Quantité en stock" required>
                                        </div>
                                    </div>
                                    <div class="form-inline items-start flex-col xl:flex-row mt-5 pt-5 first:mt-0 first:pt-0">
                                        <div class="form-label xl:w-64 xl:!mr-10">
                                            <div class="text-left">
                                                <div class="flex items-center">
                                                    <div class="font-medium">Seuil minimum</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full mt-3 xl:mt-0 flex-1">
                                            <input name="seuilMinimum" type="number" min="0" class="form-control" placeholder="Seuil minimum">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: Stock Information -->
                        <div class="flex justify-end flex-col md:flex-row gap-2 mt-5">
                            <button type="reset" class="btn py-3 border-slate-300 dark:border-darkmode-400 text-slate-500 w-full md:w-52">Annuler</button>
                            <button type="submit" class="btn py-3 btn-primary w-full md:w-52">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END: Content -->
        </div>
    </div>
    <!-- BEGIN: JS Assets-->
    <script src="dist/js/app.js"></script>
    <!-- END: JS Assets-->
</body>
</html>