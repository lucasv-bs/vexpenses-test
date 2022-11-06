@extends('layouts.app')

@section('content')
    <div class="container px-4">
        <div class="row justify-content-around">
            <x-card class="col-12">
                <x-flash-message />
                
                <h1>Employees</h1>
                
                <form method="POST" action="{{route('employees.upload')}}" enctype="multipart/form-data"
                    class="needs-validation">
                    @csrf

                    <div class="mb-3 input-group">
                        <input type="file" name="employees_file" id="employeesFile"
                            class="form-control @error('employees_file') is-invalid @enderror" 
                            aria-describedby="btnEmployeesFileUpload" aria-label="Upload">
                        <button class="btn btn-primary" id="btnEmployeesFileUpload" type="submit">Upload</button>

                        @error('employees_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
                
                <div>
                    <table class="table table-striped table-hover table-responsive align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Birth Date</th>
                                <th>Registration Number</th>
                                <th>Company</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $employee['name'] }}</td>
                                    <td>{{ $employee['birth_date'] }}</td>
                                    <td>{{ $employee['registration_number'] }}</td>
                                    <td>{{ $employee['company_id'] }}</td>
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
