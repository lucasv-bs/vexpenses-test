<?php

namespace App\Models;

use App\Imports\EmployeeImport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function import()
    {
        // Import all employees
        $import = Excel::import(new EmployeeImport($this->employees_file),$this->employees_file);

        // Register file imported
        $this->imported = true;
        $this->save();

        return $this;
    }
}
