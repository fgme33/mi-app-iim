<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\SolicitudArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudCreadaMail;

class SolicitudController extends Controller
{
    public function index()
	{
    $user = Auth::user();

    if ($user->isAdmin()) {
        $solicitudes = Solicitud::with(['usuario', 'archivos'])->latest()->get();
    } else {
        $solicitudes = Solicitud::with(['usuario', 'archivos'])
                        ->where('user_id', $user->id)
                        ->latest()
                        ->get();
    }

    return view('solicitudes.index', compact('solicitudes'));
}

    public function store(Request $request)
    {
        $request->validate([
            'tipo'               => 'required|in:servicio,compra',
            'email_notificacion' => 'required|email',
            'descripcion'        => 'required|string',
            'archivos.*'         => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $ultimoId = Solicitud::max('id') ?? 0;
            $folio = 'SOL-' . str_pad($ultimoId + 1, 5, '0', STR_PAD_LEFT);

            $solicitud = Solicitud::create([
                'folio'              => $folio,
                'tipo'               => $request->tipo,
                'user_id'            => Auth::id(),
                'email_notificacion' => $request->email_notificacion,
                'descripcion'        => $request->descripcion,
                'estado'             => 'Inicio'
            ]);

            if ($request->hasFile('archivos')) {
                foreach ($request->file('archivos') as $file) {
                    $nombreOriginal = $file->getClientOriginalName();
                    $ruta = $file->store('adjuntos_solicitudes', 'public');

                    SolicitudArchivo::create([
                        'solicitud_id'   => $solicitud->id,
                        'nombre_original' => $nombreOriginal,
                        'ruta_archivo'   => $ruta
                    ]);
                }
            }

            DB::commit();
	    Mail::to($solicitud->email_notificacion)->send(new SolicitudCreadaMail($solicitud));

            return response()->json([
                'success' => true,
                'message' => 'Solicitud creada con éxito con el folio ' . $folio
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }
}
