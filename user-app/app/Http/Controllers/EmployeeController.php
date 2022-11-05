<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employeesEndpoint = 'http://host.docker.internal:8002/api/employees';
        $employeesEndpoint .= $request->query('page')
            ? '?page='.$request->query('page')
            : '';

        $response = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->get($employeesEndpoint);
        
        $content = $response->json();

        $employeesPages = $this->generatePagesUrl(
            $request->url(),
            $content['current_page'],
            $content['last_page']
        );
        
        return view('employees.index', [
            'employees' => $content['data'],
            'pages' => $employeesPages
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
}
