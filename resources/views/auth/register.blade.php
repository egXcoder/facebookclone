@extends('layouts.auth')

@section('content')
<div class="card mt-5">
    <div class="card-header">
        <i class="fas fa-user-plus"></i> Create New Account
    </div>
    <div class="card-body rounded-lg shadow">
        <form method="POST" action="{{ route('register') }}" id="register">
            @csrf

            <div class="form-group row">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                    value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Name">

                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autocomplete="email" placeholder="Email address">

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password" placeholder="Password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                    autocomplete="new-password" placeholder="Confirm Password">
            </div>

            <div class="form-group row mb-0">
                <button type="submit" class="btn btn-success mx-auto w-50">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection