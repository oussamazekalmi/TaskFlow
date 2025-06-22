<!DOCTYPE html>
<html>
    @props(['title'])
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title }}</title>
        <link rel="shortcut icon" href="{{ asset('img/ENCGO.png') }}" type="image/x-icon">
        <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
        <style>
            .toast-center {
                top: 7%;
                left: 35%;
                transform: translate(-50%, -50%);
                position: fixed;
                z-index: 9999;
            }

            .toast-success {
                background-color: black;
                color: #ffffff;
            }

            .toast {
                min-width: 400px !important;
                font-size: 10px;
                opacity: 1 !important;
                box-shadow: none !important;
            }

            .toast:hover {
                box-shadow: none !important;
            }

            .toast-message {
                font-size: 13px;
            }
        </style>
    </head>

    <body>
        <div class="wrapper">
            @include('partiels.header')
            <div class="container-fluid" style="z-index: 999;">
                {{ $slot }}
            </div>
        </div>

        <script src="{{ asset('js/script.js') }}"></script>
        <script>
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-center", // Centrer le toast
                "timeOut": "5000",  // Durée d'affichage (en millisecondes)
                "extendedTimeOut": "1000",
                "newestOnTop": true,  // Nouveaux toasts au-dessus
                "preventDuplicates": true, // Empêcher les doublons
                "showMethod": "fadeIn",   // Méthode d'apparition
                "hideMethod": "fadeOut",  // Méthode de disparition
            }
            @if(Session::has('success'))
                toastr.success("{{ session('success') }}");
            @endif
        </script>
    </body>

</html>
