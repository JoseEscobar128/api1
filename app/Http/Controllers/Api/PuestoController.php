<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Puesto;
use Illuminate\Http\Response;
use Exception;

class PuestoController extends Controller
{
    public function index()
    {
        try {
            $puestos = Puesto::all();

            return response()->json([
                'code' => 'PUE-001',
                'status' => 'success',
                'data' => $puestos
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'code' => 'SRV-500',
                'status' => 'error',
                'message' => 'Error al obtener puestos',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
