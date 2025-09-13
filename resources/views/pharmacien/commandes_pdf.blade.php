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
        Liste des Commandes
        @php
            $filters = [];
            if(request('search_id')) $filters[] = 'ID: #CMD-'.request('search_id');
            if(request('search_status')) $filters[] = 'Statut: '.ucfirst(request('search_status'));
            if(request('search_date')) $filters[] = 'Date: '.request('search_date');
        @endphp
        @if(count($filters))
            <br>
            <small>
                <strong>Filtres :</strong> {{ implode(' | ', $filters) }}
            </small>
        @endif
    </h2>
    <div class="meta">
        <strong>Généré le :</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
        <strong>Par :</strong> {{ Auth::user()->prenom }} {{ Auth::user()->nom }} ({{ Auth::user()->email }})
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Acheteur</th>
                <th>Status</th>
                <th>methode du paiment</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>#{{$order->id}}</td>
                    <td>
                        {{$order->patient_name}}
                    </td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->payment_method }}</td>
                    <td>{{ number_format($order->total, 2, ',', ' ') }} MAD</td>
                    <td>{{ $order->creation_time ? \Carbon\Carbon::parse($order->creation_time)->format('d/m/Y H:i') : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>