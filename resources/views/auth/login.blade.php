@extends('layouts.auth-material')

@section('auth_title', 'Login')

@section('auth_content')
<form role="form" class="text-start" method="POST" action="{{ route('login') }}">
    @csrf

    <div class="input-group input-group-outline my-3 is-filled">
        {{-- Adicionado 'is-filled' para o label flutuar se houver old('email') --}}
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
    </div>
    @error('email')
        <div class="text-danger text-xs mt-1">{{ $message }}</div>
    @enderror

    <div class="input-group input-group-outline mb-3">
        <label class="form-label">Senha</label>
        <input type="password" class="form-control" name="password" required autocomplete="current-password">
    </div>
     @error('password')
        <div class="text-danger text-xs mt-1">{{ $message }}</div>
    @enderror

    <div class="form-check form-switch d-flex align-items-center mb-3">
        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
        <label class="form-check-label mb-0 ms-3" for="rememberMe">Lembrar-me</label>
    </div>

    <div class="text-center">
        <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Entrar</button>
    </div>

    <p class="mt-4 text-sm text-center">
        Não tem uma conta?
        <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Registrar-se</a>
    </p>

     @if (Route::has('password.request'))
        <p class="mt-2 text-sm text-center">
            <a href="{{ route('password.request') }}" class="text-primary text-gradient font-weight-bold">Esqueceu sua senha?</a>
        </p>
    @endif

</form>
@endsection
