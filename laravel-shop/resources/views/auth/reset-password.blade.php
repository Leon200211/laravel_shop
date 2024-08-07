@extends('layouts.auth')

@section('title', 'Восстановление пароль')

@section('content')
    <x-forms.auth-forms title="Восстановление пароль" action="{{ route('password-reset.handle') }}" method="POST">
        @csrf

        <x-forms.text-input
            name="token"
            type="hidden"
            value="{{ $token }}"
            hidden
            required
        />

        <x-forms.text-input
            name="email"
            type="email"
            placeholder="E-mail"
            value="{{ request('email') }}"
            :isError="$errors->has('email')"
            required
        />
        @error('email')
        <x-forms.error>
            {{ $message }}
        </x-forms.error>
        @enderror

        <x-forms.text-input
            name="password"
            type="password"
            placeholder="Пароль"
            :isError="$errors->has('password')"
            required
        />
        @error('password')
        <x-forms.error>
            {{ $message }}
        </x-forms.error>
        @enderror

        <x-forms.text-input
            name="password_confirmation"
            type="password"
            placeholder="Подтверждение пароля"
            :isError="$errors->has('password_confirmation')"
            required
        />
        @error('password_confirmation')
        <x-forms.error>
            {{ $message }}
        </x-forms.error>
        @enderror

        <x-forms.primary-button>
            Обновить пароль
        </x-forms.primary-button>

        <x-slot:socialAuth></x-slot:socialAuth>
        <x-slot:buttons></x-slot:buttons>
    </x-forms.auth-forms>

@endsection
