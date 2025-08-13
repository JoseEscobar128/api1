<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Puesto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
