@extends('layouts.auth')

@section('content')
<div class="card mt-5">
    <div class="card-body rounded-lg shadow">
        <form method="POST" action="{{ route('login') }}" id="login">
            @csrf
            <div class="form-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus
                    placeholder="{{ __('E-Mail Address') }}">

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary w-100 p-2">
                {{ __('Login') }}
            </button>

            <input type="hidden" name="remember" value="true">

            <div class="d-flex justify-content-center">
                @if (Route::has('password.request'))
                <a class="btn btn-link mt-2" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
                @endif
            </div>

            <hr class="my-3">

            <div class="d-flex justify-content-center">
                <a href="" class="btn btn-success">Create New Account</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ClientJS/0.1.11/client.min.js"></script>
<script>
    let client = new ClientJS();
    let fingerprint = client.getCustomFingerprint(client.getOS(),client.getOSVersion(),client.getCPU(),client.getScreenPrint());
    let user_agent = client.getUserAgent();
    $("form#login").prepend("<input type='hidden' name='fingerprint' value='"+fingerprint+"'>");
    $("form#login").prepend("<input type='hidden' name='user_agent' value='"+user_agent+"'>");
</script>
@endsection