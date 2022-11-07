@extends('layouts.app')

@section('content')
<div class="container px-4">
    <div class="row justify-content-around">
        <x-card class="col-sm-12 col-md-4">
            <a href="{{route('employees.index')}}" class="text-body">
                <p class="h1">Employees</p>
            </a>
        </x-card>
        <x-card class="col-sm-12 col-md-4">
            <a href="{{route('companies.index')}}" class="text-body">
                <p class="h1">Companies</p>
            </a>
        </x-card>
    </div>
</div>
@endsection
