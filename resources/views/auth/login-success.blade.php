<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Berhasil | APM</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Meta Refresh Fallback just in case JS fails -->
    <meta http-equiv="refresh" content="3;url={{ $redirectUrl }}">

    <style>
        body { font-family: 'Poppins', sans-serif; }

        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: #4ade80;
            stroke-miterlimit: 10;
            box-shadow: inset 0px 0px 0px #4ade80;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }

        .success-checkmark .check-circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 2;
            stroke-miterlimit: 10;
            stroke: #4ade80;
            fill: none;
            animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .success-checkmark .check-path {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes stroke {
            100% { stroke-dashoffset: 0; }
        }
        @keyframes scale {
            0%, 100% { transform: none; }
            50% { transform: scale3d(1.1, 1.1, 1); }
        }
        @keyframes fill {
            100% { box-shadow: inset 0px 0px 0px 30px rgba(74, 222, 128, 0.1); }
        }
    </style>
</head>
<body class="bg-gray-50 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl shadow-xl w-full max-w-sm p-8 text-center border border-gray-100 transform transition-all translate-y-0 opacity-100 animate-fade-in-up">

        <!-- Animated Checkmark -->
        <div class="mb-6 relative">
            <svg class="success-checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="check-circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="check-path" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>

            <div class="absolute left-1/2 -translate-x-1/2 -bottom-3 w-9 h-9 rounded-full bg-white border border-gray-100 shadow-md flex items-center justify-center pointer-events-none">
                <img src="{{ asset('images/APM_Logo.webp') }}" class="w-6 h-6 object-contain opacity-90" alt="APM Logo">
            </div>
        </div>

        <!-- Text -->
        <h2 class="text-2xl font-black text-gray-900 mb-2">Login Berhasil!</h2>
        <p class="text-sm text-gray-500 mb-6 font-medium">
            Selamat datang kembali, <strong class="text-[#CD422B]">{{ $userName }}</strong>.<br>
            Sedang menyiapkan dasbor Anda...
        </p>

        <!-- Simple loading bar -->
        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-[#CD422B] w-0 animate-[progress_1.5s_ease-out_forwards]"></div>
        </div>
    </div>

    <!-- Additional tailwind inline animation classes -->
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes progress {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }
    </style>

    <script>
        // Use JS to redirect immediately after animation finishes (~1.8 seconds)
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.location.replace("{{ $redirectUrl }}");
            }, 1800);
        });
    </script>
</body>
</html>
