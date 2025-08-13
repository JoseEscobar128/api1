<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use App\Http\Requests\EmpleadoRequest;
use Illuminate\Support\Facades\Log;

class EmpleadoController extends Controller
{
    /**
     * Registrar un nuevo empleado.
     */
    public function store(EmpleadoRequest $request)
    {
        try {
            Log::info('EmpleadoController@store - Inicio registro empleado', ['request_data' => $request->all()]);

            $existente = Empleado::withTrashed()
                ->where(function ($query) use ($request) {
                    if ($request->rfc) $query->orWhere('rfc', $request->rfc);
                    if ($request->curp) $query->orWhere('curp', $request->curp);
                    if ($request->nss) $query->orWhere('nss', $request->nss);
                })
                ->first();

            if ($existente) {
                Log::info('EmpleadoController@store - Empleado ya existente encontrado', ['empleado_id' => $existente->id, 'trashed' => $existente->trashed()]);

                if ($existente->trashed()) {
                    $existente->restore();
                    $existente->update($request->validated());

                    Log::info('EmpleadoController@store - Empleado restaurado y actualizado', ['empleado_id' => $existente->id]);

                    return response()->json([
                        'code' => 'EMP-003',
                        'status' => 'success',
                        'message' => 'Empleado restaurado y actualizado correctamente',
                        'data' => $existente
                    ], Response::HTTP_OK);
                }

                return response()->json([
                    'code' => 'EMP-002',
                    'status' => 'error',
                    'message' => 'Ya existe un empleado con RFC, CURP o NSS registrado.'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $datos = $request->validated();

            // Log del puesto_id si viene presente
            if (isset($datos['puesto_id'])) {
                Log::info('EmpleadoController@store - puesto_id recibido', ['puesto_id' => $datos['puesto_id']]);
            } else {
                Log::info('EmpleadoController@store - puesto_id no recibido en datos');
            }

            // Si viene en base64, conviértelo a binario
            if (!empty($datos['huella'])) {
                $datos['huella'] = base64_decode($datos['huella']);
            }

            $empleado = Empleado::create($datos);

            Log::info('EmpleadoController@store - Empleado creado exitosamente', ['empleado_id' => $empleado->id]);

            return response()->json([
                'code' => 'EMP-001',
                'status' => 'success',
                'message' => 'Empleado registrado correctamente',
                'data' => tap($empleado, function ($e) {
                    if (!is_null($e->huella)) {
                        $e->huella = base64_encode($e->huella);
                    }
                })
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error('EmpleadoController@store - Error al registrar empleado', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'code' => 'SRV-500',
                'status' => 'error',
                'message' => 'Error al registrar empleado',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Listar todos los empleados.
     */
    public function index()
    {
        try {
            Log::info('EmpleadoController@index - Listando empleados');

            $empleados = Empleado::with('puesto')->get();

            foreach ($empleados as $empleado) {
                if (!is_null($empleado->huella)) {
                    $empleado->huella = base64_encode($empleado->huella);
                }
            }

            return response()->json([
                'code' => 'EMP-004',
                'status' => 'success',
                'data' => $empleados
            ]);
        } catch (Exception $e) {
            Log::error('EmpleadoController@index - Error al obtener empleados', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'code' => 'SRV-500',
                'status' => 'error',
                'message' => 'Error al obtener empleados',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mostrar un empleado por ID.
     */
    public function show($id)
    {
        try {
            Log::info('EmpleadoController@show - Solicitando empleado', ['empleado_id' => $id]);

            $empleado = Empleado::with(['usuario', 'puesto'])->findOrFail($id);

            if (!is_null($empleado->huella)) {
                $empleado->huella = base64_encode($empleado->huella);
            }

            return response()->json([
                'code' => 'EMP-005',
                'status' => 'success',
                'data' => $empleado
            ]);
        } catch (ModelNotFoundException $e) {
            Log::warning('EmpleadoController@show - Empleado no encontrado', ['empleado_id' => $id]);

            return response()->json([
                'code' => 'EMP-404',
                'status' => 'error',
                'message' => 'Empleado no encontrado'
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('EmpleadoController@show - Error al obtener empleado', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'empleado_id' => $id
            ]);

            return response()->json([
                'code' => 'SRV-500',
                'status' => 'error',
                'message' => 'Error al obtener empleado',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Actualizar datos de un empleado.
     */
    public function update(EmpleadoRequest $request, $id)
    {
        try {
            Log::info('EmpleadoController@update - Inicio actualización empleado', ['empleado_id' => $id, 'request_data' => $request->all()]);

            $empleado = Empleado::findOrFail($id);
            $datos = $request->validated();

            if (isset($datos['puesto_id'])) {
                Log::info('EmpleadoController@update - puesto_id recibido', ['puesto_id' => $datos['puesto_id']]);
            } else {
                Log::info('EmpleadoController@update - puesto_id no recibido en datos');
            }

            if (!empty($datos['huella'])) {
                $datos['huella'] = base64_decode($datos['huella']);
            }

            $empleado->update($datos);

            Log::info('EmpleadoController@update - Empleado actualizado exitosamente', ['empleado_id' => $empleado->id]);

            return response()->json([
                'code' => 'EMP-006',
                'status' => 'success',
                'message' => 'Empleado actualizado correctamente',
                'data' => $empleado
            ]);
        } catch (ModelNotFoundException $e) {
            Log::warning('EmpleadoController@update - Empleado no encontrado', ['empleado_id' => $id]);

            return response()->json([
                'code' => 'EMP-404',
                'status' => 'error',
                'message' => 'Empleado no encontrado'
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('EmpleadoController@update - Error al actualizar empleado', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'empleado_id' => $id,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'code' => 'SRV-500',
                'status' => 'error',
                'message' => 'Error al actualizar empleado',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Eliminar un empleado (Soft Delete).
     */
    public function destroy($id)
    {
        try {
            Log::info('EmpleadoController@destroy - Inicio eliminación empleado', ['empleado_id' => $id]);

            $empleado = Empleado::findOrFail($id);

            if ($empleado->usuario) {
                Log::info('EmpleadoController@destroy - Eliminando usuario asociado', ['usuario_id' => $empleado->usuario->id]);
                $empleado->usuario->delete();
            }

            $empleado->delete();

            Log::info('EmpleadoController@destroy - Empleado y usuario asociado eliminados correctamente', ['empleado_id' => $id]);

            return response()->json([
                'code' => 'EMP-007',
                'status' => 'success',
                'message' => 'Empleado y usuario asociado eliminados correctamente'
            ]);
        } catch (ModelNotFoundException $e) {
            Log::warning('EmpleadoController@destroy - Empleado no encontrado', ['empleado_id' => $id]);

            return response()->json([
                'code' => 'EMP-404',
                'status' => 'error',
                'message' => 'Empleado no encontrado'
            ], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            Log::error('EmpleadoController@destroy - Error al eliminar empleado', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'empleado_id' => $id
            ]);

            return response()->json([
                'code' => 'SRV-500',
                'status' => 'error',
                'message' => 'Error al eliminar empleado',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}