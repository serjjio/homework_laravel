@extends('main')


@section('content')
    @foreach($streets as $street)
        <b>{{ $street->city->name }}</b> - {{ $street->type }}. {{$street->name }} <br>
    @endforeach
@endsection
