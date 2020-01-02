<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromCollection, ShouldAutoSize
{
    use Exportable;

    private array $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all(array_keys($this->columns))->prepend($this->columns);
    }
}
