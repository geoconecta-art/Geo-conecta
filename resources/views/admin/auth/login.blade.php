<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('/assets/css/dashlite.css?ver=2.4.0') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('/assets/css/theme.css?ver=2.4.0') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }} ">
</head>

<style>
    #left-side {
        /* background: url(/img/nuevo-degradado.png) no-repeat center center / cover !important; */
        background: url('assets/images/mundoNegro.jpg') no-repeat center center / cover !important;
    }

    .card {
        /*background-color: #ffffff57;*/
        background-color: #fff;
        border-radius: 1rem;
    }

    .nk-block-title,
    .nk-block-des,
    .form-label {
        color: #000;
    }
    
    @media screen and (width < 768px ){
        #right-side{
            background: url('assets/images/mundoNegro.jpg') no-repeat center center / cover !important;
        }

        #logo_ordenamiento{
            width: 75%;
        }
    }
    
</style>

<body class="nk-body bg-white npc-default pg-auth">
    
    <div class="container col-12 vh-100 d-flex m-0 p-0">
        <div class="col-7 d-none d-md-block vh-100" id="left-side"></div>
        <div class="col-12 col-md-5 vh-100 p-5 d-flex flex-column justify-content-center" id="right-side" style="background: #f3f4f6;">
            <div class="card">
                <div class="m-2 d-flex justify-content-center">
                    <img src="{{asset('assets/images/atizapan_logo.jpg')}}" alt="Logo Atizapán" id="logo_ordenamiento">
                    {{-- <div id="direction">
                        <span id="direction-title-1">
                            Ordenamiento Territorial y
                        </span>
                        <span id="direction-title-2">
                            Desarrollo Urbano
                        </span>
                    </div> --}}
                </div>

                <div class="card-inner card-inner-lg">
                    <div class="nk-block-head">
                        <div class="nk-block-head-content">
                            <h4 class="nk-block-title">Ingresar</h4>
                            
                        </div>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="email">Usuario</label>
                            </div>
                            <input type="text" class="form-control form-control-lg white-shadow"
                                placeholder="Introduce usuario" name="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="password">Contraseña</label>
                            </div>
                            <div class="form-control-wrap">
                                <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                </a>
                                <input type="password" class="form-control form-control-lg white-shadow" id="password"
                                    placeholder="Introduce contraseña" name="password" required>
                            </div>
                        </div>

                        @error('credentials')
                        <div class="invalid-feedback d-block mb-3" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror

                        <div class="form-group">
                            <button class="btn btn-lg btn-primary btn-block">Ingresar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('assets/js/bundle.js?ver=2.4.0') }}"></script>
    <script src="{{ asset('assets/js/scripts.js?ver=2.4.0') }}"></script>

</body>
</html>