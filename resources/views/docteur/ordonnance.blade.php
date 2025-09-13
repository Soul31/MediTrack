<x-docteur.dlayout title="Ordonnance : {{ $ordonnance->id }} | Docteur" :breadcrum="['Ordonnances', 'ordonnance', $ordonnance->id]" activePage="-1">
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Détails d'Ordonnance
        </h2>
    </div>
    <!-- BEGIN: Transaction Details -->
    <div class="intro-y grid grid-cols-11 gap-5 mt-5">
        <div class="col-span-12 lg:col-span-4 2xl:col-span-3">
            <div class="box p-5 rounded-md">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Détails d'Ordonnance</div>
                    <a class="flex items-center text-primary whitespace-nowrap" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="arrow-left-right" class="w-4 h-4 mr-1"></i> Annuler Ordonnance </a>
                </div>
                <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i> ID : <a href="" class="underline decoration-dotted ml-1">{{ $ordonnance->id }}</a> </div>
                <div class="flex items-center mt-3"> <i data-lucide="calendar" class="w-4 h-4 text-slate-500 mr-2"></i> Date d'Ordonnance : {{ $ordonnance->created_at }} </div>
                <div class="flex items-center mt-3"> <i data-lucide="clock" class="w-4 h-4 text-slate-500 mr-2"></i> Statut d'Ordonnance: <span class="rounded px-2 ml-1
                    @switch($ordonnance->statut)
                                @case('en attente')
                                 text-pending bg-pending/20
                                 @break
                                @case('valide')
                                 text-success bg-success/20
                                 @break
                                @case('refus')
                                 text-danger bg-opacity-20 bg-danger
                                 @break
                                @case('livre')
                                 text-gray-600 bg-slate-300
                                 @break
                            @endswitch
                ">{{ $ordonnance->statut }}</span> </div>
            </div>
            <div class="box p-5 rounded-md mt-5">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Détails du patient</div>
                </div>
                <div class="flex items-center"> <i data-lucide="clipboard" class="w-4 h-4 text-slate-500 mr-2"></i> Nom: <div class="underline decoration-dotted ml-1">{{ $ordonnance->patient->user->nom.' '.$ordonnance->patient->user->prenom }}</div> </div>
                <div class="flex items-center mt-3"> <i data-lucide="calendar" class="w-4 h-4 text-slate-500 mr-2"></i> Telephone: +71828273732 </div>
                <div class="flex items-center mt-3"> <i data-lucide="map-pin" class="w-4 h-4 text-slate-500 mr-2"></i> Adresse: 260 W. Storm Street New York, NY 10025. </div>
            </div>
            <div class="box p-5 rounded-md mt-5">
                <div class="flex items-center border-slate-200/60 dark:border-darkmode-400 font-medium">
                    <i data-lucide="credit-card" class="w-4 h-4 text-slate-500 mr-2"></i> Grand Total:
                    <div class="ml-auto">{{ $ordonnance->total }} DH</div>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-7 2xl:col-span-8">
            <div class="box p-5 rounded-md">
                <div class="flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5 mb-5">
                    <div class="font-medium text-base truncate">Liste des Médicaments</div>
                </div>
                <div class="overflow-auto lg:overflow-visible -mt-3">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="whitespace-nowrap !py-5">Médicament</th>
                            <th class="whitespace-nowrap text-right">Prix Unitaire</th>
                            <th class="whitespace-nowrap text-right">Qte</th>
                            <th class="whitespace-nowrap text-right">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ordonnance->lignes as $ligne)
                            <tr>
                                <td class="!py-4">
                                    <div class="flex items-center">
                                        <a href="#" class="font-medium whitespace-nowrap ml-4 w-48 truncate">{{ $ligne->medicament->nom }}</a>
                                    </div>
                                </td>
                                <td class="text-right">{{ $ligne->medicament->prix }} DH</td>
                                <td class="text-right">{{ $ligne->quantite }}</td>
                                <td class="text-right">{{ $ligne->montant }} DH</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Transaction Details -->
    <!-- BEGIN: Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="p-5 text-center">
                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                        <div class="text-3xl mt-5">Vous êtes sûr?</div>
                        <div class="text-slate-500 mt-2">
                            Voulez-vous vraiment annuler cette ordonnance ?
                            <br>
                            Ce processus ne peut pas être annulé.
                        </div>
                    </div>
                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Annuler</button>
                        <button type="button" class="btn btn-danger w-auto">Annuler l'Ordonnance</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Delete Confirmation Modal -->
    <script>
        // Delete Confirmation Modal Handler
        document.addEventListener('DOMContentLoaded', function() {
            // Store the commande ID when delete button is clicked
            let ordonnanceIdToDelete = {{ $ordonnance->id }};


            // Handle the actual delete confirmation
            document.querySelector('#delete-confirmation-modal .btn-danger').addEventListener('click', function() {
                if (!ordonnanceIdToDelete) return;

                // Create a form dynamically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('docteur-delete-ordonnance', '') }}/${ordonnanceIdToDelete}`;
                form.style.display = 'none';

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Submit the form
                document.body.appendChild(form);
                form.submit();
            });

            // Close modal after submission
            const modal = tailwind.Modal.getOrCreateInstance(document.getElementById('delete-confirmation-modal'));
            modal.hide();
        });
    </script>
</x-docteur.dlayout>
