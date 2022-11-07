<?php

namespace App\Http\Controllers;

Use Cache;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $expiration = 60; // minutes
        $key = 'companies_' . ($request->query('page') ? $request->query('page') : '');

        $companiesEndpoint = 'http://host.docker.internal:8003/api/companies';
        $companiesEndpoint .= $request->query('page')
            ? '?page='.$request->query('page')
            : '';

        $response = Cache::remember($key, $expiration, function() use($companiesEndpoint) {
            return \Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get($companiesEndpoint)->json();
        });
        
        $content = $response;

        $companiesPages = $this->generatePagesUrl(
            $request->url(),
            $content['current_page'],
            $content['last_page']
        );
        
        return view('companies.index', [
            'companies' => $content['data'],
            'pages' => $companiesPages
        ]);
    }

    public function show(Request $request, $id)
    {
        $expiration = 60; // minutes
        $key = 'company_' . $id;

        $companiesEndpoint = 'http://host.docker.internal:8003/api/companies/'.$id;
        
        $response = Cache::remember($key, $expiration, function() use($companiesEndpoint) {
            return \Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get($companiesEndpoint)->json();
        });

        $content = $response;

        return $content;

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
