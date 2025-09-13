<!DOCTYPE html>
<html>
<head>
    <title>Commandes PDF</title>
    <style>
        body { font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 3px 5px; text-align: left; }
        th { background: #f0f0f0; }
        h2 { margin-bottom: 5px; }
        .meta { margin-bottom: 5px; }
        .header { display: flex; align-items: center; margin-bottom: 10px; }
        .logo { height: 32px; margin-right: 10px; }
        .company-name { font-size: 20px; font-weight: bold; color: #2d3748; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('dist/images/logo.svg') }}" class="logo" alt="MediTrack Logo">
        <span class="company-name">MediTrack</span>
    </div>
    <h2>
        Liste des Médicaments Commandés
    </h2>
    <div class="meta">
        <strong>Généré le :</strong> {{ now()->format('d/m/Y H:i') }}<br>
        <strong>Par :</strong> {{ Auth::user()->prenom }} {{ Auth::user()->nom }} ({{ Auth::user()->email }})
    </div>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
                <th>Posologie</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotal = 0;
            @endphp
            @forelse($lignes as $ligne)
                @php
                    $lineTotal = ($ligne->montant ?? 0) * ($ligne->quantite ?? 0);
                    $grandTotal += $lineTotal;
                @endphp
                <tr>
                    <td>
                        {{ $ligne->medicament ? $ligne->medicament->nom : 'N/A' }}
                    </td>
                    <td>
                        {{ number_format($ligne->montant ?? 0, 2, ',', ' ') }} MAD
                    </td>
                    <td>{{ $ligne->quantite }}</td>
                    <td>
                        {{ number_format($lineTotal, 2, ',', ' ') }} MAD
                    </td>
                    <td>
                        {{ $ligne->posologie ?? '-' }} mg
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Aucun médicament trouvé</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="3" style="text-align: center; font-weight: bold;">Total général</td>
                <td colspan="2" style="font-weight: bold;text-align: right;">
                    <!-- Display the grand total -->
                   $ {{ number_format($grandTotal, 2, ',', ' ') }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>