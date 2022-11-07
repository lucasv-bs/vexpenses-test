<?php

namespace App\Http\Controllers;

Use Cache;

use App\Jobs\EmployeeFileUploaded;
use App\Models\EmployeeFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $expiration = 60; // minutes
        $key = 'employees_' . ($request->query('page') ? $request->query('page') : '');

        $employeesEndpoint = 'http://host.docker.internal:8002/api/employees';
        $employeesEndpoint .= $request->query('page')
            ? '?page='.$request->query('page')
            : '';
        
        $response = Cache::remember($key, $expiration, function() use($employeesEndpoint) {
            return \Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get($employeesEndpoint)->json();
        });

        $content = $response;

        $employeesPages = $this->generatePagesUrl(
            $request->url(),
            $content['current_page'],
            $content['last_page']
        );

        $employees = $this->getEmployeesCompany($request, $content['data']);
        
        return view('employees.index', [
            'employees' => $employees,
            'pages' => $employeesPages
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'employees_file' => [
                'required',
                File::types('csv')
                ->max(2 * 2014)
            ]
        ]);

        $uploadedFile['original_filename'] = $request->file('employees_file')->getClientOriginalName();
        $uploadedFile['employees_file'] = $request->file('employees_file')->store('employees', 's3');

        Storage::setVisibility($uploadedFile['employees_file'], 'public');

        $employee = EmployeeFile::create($uploadedFile);

        EmployeeFileUploaded::dispatch($employee->toArray());

        return back(Response::HTTP_CREATED)->with([
            'message' => 'Your file has been successfully uploaded. Wait a moment and refresh the page to see the updated data.',
            'status' => 'success'
        ]);
    }

    private function generatePagesUrl($urlBase, $currentPage, $lastPage)
    {
        $pages = [];
        $previousPage = [
            'url' => "#",
            'label' => "&laquo; Previous"
        ];
        $nextPage = [
            'url' => "#",
            'label' => "Next &raquo;"
        ];        
        if($currentPage > 1) {
            $previousPage['url'] = $urlBase . '?page='.$currentPage - 1;
        }
        if($currentPage < $lastPage) {
            $nextPage['url'] = $urlBase . '?page='.$currentPage + 1;
        }

        $pages[] = $previousPage;
        for($i = 1; $i <= $lastPage; $i++) {
            $pages[] = [
                'url' => $urlBase . '?page='.$i,
                'label' => $i
            ];
        }
        $pages[] = $nextPage;

        return $pages;
    }

    private function getEmployeesCompany(Request $request, $employeesData)
    {
        foreach($employeesData as $index => $employee) {
            $company = (new CompanyController)->show($request, $employee['company_id']);
            
            $employee['company_name'] = $company[0]['corporate_name'];

            $employeesData[$index] = $employee;
        }

        return $employeesData;
    }
}
