@extends('layouts.app')

@section('content')
    <div class="container px-4">
        <div class="row justify-content-around">
            <x-card class="col-12">
                
                <h1>Companies</h1>
                
                <div>
                    <table class="table table-striped table-hover table-responsive align-middle">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>CNPJ</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companies as $company)
                                <tr>
                                    <td>{{ $company['corporate_name'] }}</td>
                                    <td>{{ $company['cnpj'] }}</td>
                                    <td class="text-center">
                                        <a href="" class="btn btn-success">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            @foreach ($pages as $page)
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $page['url'] != null 
                                        ? $page['url']
                                        : '#' }}"
                                    >{{ html_entity_decode($page['label']) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </x-card>
        </div>
    </div>
@endsection
