<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

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
            'company_id' => $row['company_id'],
            'imported' => true,
            'import_file' => $this->fileName
        ]);
    }
}
