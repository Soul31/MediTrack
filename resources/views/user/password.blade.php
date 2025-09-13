<!DOCTYPE html>
<html lang="en" class="light">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="dist/images/logo.svg" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Icewall admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Icewall Admin Template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>Update Profile - MediTrack</title>
        <!-- BEGIN: CSS Assets-->
        <link rel="stylesheet" href="dist/css/app.css" />
        <!-- END: CSS Assets-->
    </head>
    <!-- END: Head -->
    <body class="main">
        <!-- BEGIN: Top Bar -->
        @include('layouts.topbar', ['pageTitle' => 'Change Password'])
        <!-- END: Top Bar -->
        <!-- BEGIN: Top Menu -->
        @include('layouts.' . Auth::user()->role . '.topmenu')
        <!-- END: Top Menu -->
        <!-- BEGIN: Content -->
        <div class="wrapper wrapper--top-nav">
            <div class="wrapper-box">
                <!-- BEGIN: Content -->
                <div class="content">
                    <div class="intro-y flex items-center mt-8">
                        <h2 class="text-lg font-medium mr-auto">
                            Change Password
                        </h2>
                    </div>
                    <div class="grid grid-cols-12 gap-6">
                        <!-- BEGIN: Profile Menu -->
                        @include('layouts.' . Auth::user()->role . '.profilemenu')
                        <!-- END: Profile Menu -->
                        <div class="col-span-12 lg:col-span-8 2xl:col-span-9">
                            <!-- BEGIN: Change Password -->
                            <div class="intro-y box lg:mt-5">
                                <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                    <h2 class="font-medium text-base mr-auto">
                                        Change Password
                                    </h2>
                                </div>
                                <div class="p-5">
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form action="{{ route('change-password.submit') }}" method="POST">
                                        @csrf
                                        <div>
                                            <label for="change-password-form-1" class="form-label">Old Password</label>
                                            <input id="change-password-form-1" name="old_password" type="password" class="form-control" placeholder="Enter old password" required>
                                        </div>
                                        <div class="mt-3">
                                            <label for="change-password-form-2" class="form-label">New Password</label>
                                            <input id="change-password-form-2" name="new_password" type="password" class="form-control" placeholder="Enter new password" required>
                                        </div>
                                        <div class="mt-3">
                                            <label for="change-password-form-3" class="form-label">Confirm New Password</label>
                                            <input id="change-password-form-3" name="new_password_confirmation" type="password" class="form-control" placeholder="Same password as before" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-4">Change Password</button>
                                    </form>
                                </div>
                            </div>
                            <!-- END: Change Password -->
                        </div>
                    </div>
                </div>
                <!-- END: Content -->
            </div>
        </div>
        <!-- END: Content -->
        <!-- BEGIN: JS Assets-->
        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=["your-google-map-api"]&libraries=places"></script>
        <script src="dist/js/app.js"></script>
        <!-- END: JS Assets-->
    </body>
</html>