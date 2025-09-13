<!DOCTYPE html>
<html lang="en" class="light">
<!-- BEGIN: Head -->
<head>
    <meta charset="utf-8">
    <link href="/dist/images/logo.svg" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'MediTrack' }}</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="/dist/css/app.css" />
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->
<body class="main">

<!-- BEGIN: Mobile Menu -->

<x-mobile-menu/>

<!-- END: Mobile Menu -->

<!-- BEGIN: Top Bar -->
<x-topbar :breadcrum="$breadcrum" />
<!-- END: Top Bar -->

<!-- Flash Messages Container -->
<div class="fixed top-20 right-5 z-50 w-full flex items-center justify-center max-w-full">
    @if(session('success'))
        <div class="alert alert-success-soft show flex items-center mb-2" role="alert">
            <i data-lucide="check-circle" class="w-6 h-6 mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger-soft show flex items-center mb-2" role="alert">
            <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger-soft show flex items-center mb-2" role="alert">
            <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<div class="wrapper">
    <div class="wrapper-box">
        {{ $slot }}
    </div>
</div>

<!-- BEGIN: JS Assets-->
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
<script src="/dist/js/app.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    // Auto-hide flash messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 1s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 1000);
            });
        }, 5000);

        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
<!-- END: JS Assets-->
</body>
</html>
