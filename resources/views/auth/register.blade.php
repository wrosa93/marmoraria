@extends('layouts.auth-material')

@section('auth_title', 'Registrar')

@section('auth_content')
<form role="form" class="text-start" method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name -->
    <div class="input-group input-group-outline my-3">
        <label class="form-label">Nome</label>
        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
    </div>
    @error('name')
        <div class="text-danger text-xs mt-1">{{ $message }}</div>
    @enderror

    <!-- Email Address -->
    <div class="input-group input-group-outline my-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="username">
    </div>
    @error('email')
        <div class="text-danger text-xs mt-1">{{ $message }}</div>
    @enderror

    <!-- Password -->
    <div class="input-group input-group-outline mb-3">
        <label class="form-label">Senha</label>
        <input type="password" class="form-control" name="password" required autocomplete="new-password">
    </div>
    @error('password')
        <div class="text-danger text-xs mt-1">{{ $message }}</div>
    @enderror

    <!-- Confirm Password -->
    <div class="input-group input-group-outline mb-3">
        <label class="form-label">Confirmar Senha</label>
        <input type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
    </div>
     @error('password_confirmation')
        <div class="text-danger text-xs mt-1">{{ $message }}</div>
    @enderror

    <div class="text-center">
        <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Registrar</button>
    </div>

    <p class="mt-4 text-sm text-center">
        Já possui uma conta?
        <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Entrar</a>
    </p>
</form>
@endsection
