@extends('layouts.auth')


@section('content')

    @auth
    <form action="{{ route('logOut') }}" method="POST">
        @csrf
        @method('delete')

        <button type="submit">Выйти</button>
    </form>
    @endauth

@endsection
