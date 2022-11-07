<?php

namespace App\Imports;

use App\Models\Employee;
use Cache;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Symfony\Component\HttpFoundation\Response;

class EmployeeImport implements SkipsOnError,ToModel,WithHeadingRow,WithUpserts
{
    use Importable, SkipsErrors;

    private string $fileName;

    public function __construct(string $fileName) 
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return ['registration_number', 'company_id'];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Employee([
            'name' => $row['name'],
            'birth_date' => $row['birth_date'],
            'registration_number' => $row['registration_number'],
            'company_id' => $this->getEmployeeCompany($row['company_id']),
            'imported' => true,
            'import_file' => $this->fileName
        ]);
    }

    private function getEmployeeCompany(int $companyId)
    {
        $expiration = 60 * 24; // one day
        $key = 'company_' . $companyId;

        $companiesEndpoint = 'http://host.docker.internal:8003/api/companies/'.$companyId;
        
        $response = Cache::remember($key, $expiration, function() use($companiesEndpoint) {
            return \Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get($companiesEndpoint)->json();
        });

        if( array_key_exists('exception', $response) ) {
            return null;
        }

        return $response[0]['id'];
    }
}
