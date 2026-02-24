@extends('public.main_layout')
@section('dynamic_content')
@section('title', 'Edu:Authentication')
@section('site_name', 'Edu:Authentication')
<div class="div_auth">
    <div class="div_log_auth">
        <h2 class="h2_auth">Bejelentkezés</h2>
        <form method="POST" action="{{route('auth.login')}}">
            @csrf
            <input class="input_auth" type="text" name="username" placeholder="Felhasználónév"/><br/>
            <input class="input_auth" type="password" name="password" placeholder="Jelszó"/><br/>
            <button class="button_auth" type="submit">Bejelentkezés</button>
        </form>
    </div>
    <div class="div_reg_auth">
        <h2>Regisztráció</h2>
    <form method="POST" action="{{route('auth.register')}}">
            @csrf
            <input class="input_auth" type="text" name="lastname" placeholder="Keresztnév"/>
            <input class="input_auth" type="text" name="firstname" placeholder="Vezetéknév"/><br/>
            <input class="input_auth" type="email" name="email" placeholder="Email"/><br/>
            <input class="input_auth" type="text" name="username" placeholder="Felhasználónév"/><br/>
            <input class="input_auth" type="password" name="password" placeholder="Jelszó"/><br/>
            <input class="input_auth" type="password" name="password_again" placeholder="Jelszó Újra"/><br/>
            <button class="button_auth" type="submit">Regisztrálás</button>
        </form>
    </div>
</div>
@endsection
