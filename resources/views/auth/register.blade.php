{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fresh-order</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Recursive&display=swap");

        * {
            box-sizing: border-box;
        }

        html {
            font-size: 100px;
        }

        @media (max-width: 768px) {
            html {
                font-size: calc(100vw / 768 * 100);
            }
        }

        body {
            min-height: 100vh;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            font-family: "Recursive", sans-serif;
            font-size: 0.2rem;
            position: relative;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            z-index: 1;
        }

        .bg-box {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #286aaf;
            z-index: -1;
            display: flex;
            overflow: hidden;
        }

        .bg-box div {
            flex: 1;
            margin: 0 0.3rem;
            background-repeat: no-repeat;
            background-size: 100% auto;
            border-radius: 0.5rem;
        }

        .bg-box .bg1 {
            background-image: url(https://images.pexels.com/photos/4808621/pexels-photo-4808621.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260);
            background-position: left center;
            -webkit-animation: fadeinBottom 1s both;
            animation: fadeinBottom 1s both;
        }

        .bg-box .bg2 {
            background-image: url(https://images.pexels.com/photos/7852738/pexels-photo-7852738.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260);
            background-position: right center;
            -webkit-animation: fadeinTop 1s 0.3s both;
            animation: fadeinTop 1s 0.3s both;
        }

        .form {
            margin: auto;
            width: 5rem;
            padding: 0.5rem;
            color: #07417d;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 1rem 0 1rem 0;
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .form label {
            display: block;
            font-weight: bold;
        }

        .form input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            display: block;
            width: 100%;
            margin: 0.1rem 0 0.2rem;
            border: none;
            border-radius: 0.3rem;
            padding: 0.1rem 0.3rem;
            padding-left: 0.5rem;
            font-family: inherit;
        }

        .form input:focus,
        .form input:focus-within {
            outline: none;
            box-shadow: 0 0 5px 2px rgba(255, 255, 255, 0.5);
        }

        .form ion-icon {
            font-size: 1.2em;
        }

        .form .input-box {
            position: relative;
        }

        .form .prefix {
            position: absolute;
            color: lightgrey;
            top: 0;
            bottom: 0;
            left: 0.2rem;
            margin: auto;
        }

        .form .switch-btn {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            padding: 0.2rem;
            margin: auto;
            cursor: pointer;
        }

        .form .send-btn {
            display: inline-block;
            padding: 0.1rem 0.3rem;
            color: #fff;
            background-color: #286aaf;
            border-radius: 0.3rem;
            cursor: pointer;
        }

        .form .send-btn ion-icon {
            vertical-align: middle;
        }

        @-webkit-keyframes fadeinBottom {
            from {
                opacity: 0;
                transform: translateY(3rem);
            }

            to {
                opacity: 1;
                transform: translateY(1rem);
            }
        }

        @keyframes fadeinBottom {
            from {
                opacity: 0;
                transform: translateY(3rem);
            }

            to {
                opacity: 1;
                transform: translateY(1rem);
            }
        }

        @-webkit-keyframes fadeinTop {
            from {
                opacity: 0;
                transform: translateY(-3rem);
            }

            to {
                opacity: 1;
                transform: translateY(-1rem);
            }
        }

        @keyframes fadeinTop {
            from {
                opacity: 0;
                transform: translateY(-3rem);
            }

            to {
                opacity: 1;
                transform: translateY(-1rem);
            }
        }
    </style>
</head>

<body>

    <div class="bg-box">
        <div class="bg1"></div>
        <div class="bg2"></div>
    </div> <div class="form">
    <form action="{{ route('register') }}" method="POST">
        @csrf
        @method("POST")

        <label for="name">Name</label>
        <div class="input-box">
            <ion-icon class="prefix" name="person-outline"></ion-icon>
            <input id="name" name="name" type="name" spellcheck="false" />
        </div>

        <label for="account">Email</label>
        <div class="input-box">
            <ion-icon class="prefix" name="person-outline"></ion-icon>
            <input id="account" name="email" type="email" spellcheck="false" />
        </div>
        <label for="password">Password</label>
        <div class="input-box">
            <ion-icon class="prefix" name="lock-closed-outline"></ion-icon>
            <input id="password" name="password" type="password" spellcheck="false" />
            <ion-icon class="switch-btn" name="eye-off-outline"></ion-icon>
        </div>
        <label for="password_confirmation">Password Confirmation</label>
        <div class="input-box">
            <ion-icon class="prefix" name="lock-closed-outline"></ion-icon>
            <input id="password_confirmation" name="password_confirmation" type="password" spellcheck="false" />
            {{-- <ion-icon class="switch-btn" name="eye-off-outline"></ion-icon> --}}
        </div>

       <button style="background: transparent; border: none">
        <div class="send-btn">Register
            <ion-icon name="arrow-forward-circle-outline"></ion-icon>
        </div></button>
        <br>
        <br>
        do you have an account ? <a href="{{ route('login') }}">Login</a>
        <br>
        {{-- or go to <a href="">home</a> --}}
    </form> </div>


</body>
<script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
<script>
    const pswInput = document.querySelector('#password');
const switchBtn = document.querySelector('.switch-btn');
switchBtn.addEventListener('click', togglePswVisibility);

function togglePswVisibility() {
  const isHide = pswInput.type === 'password';
  pswInput.setAttribute('type', isHide ? 'text' : 'password');
  switchBtn.setAttribute('name', isHide ? 'eye-outline' : 'eye-off-outline');
}



</script>

</html>
