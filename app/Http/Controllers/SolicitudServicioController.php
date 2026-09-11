<?php

namespace App\Http\Controllers;

use App\Models\SolicitudServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudCreadaMail;

class SolicitudServicioController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Admin ve todo, usuario común solo las suyas
        if ($user->isAdmin()) {
            $solicitudes = SolicitudServicio::with('usuario')->latest()->get();
        } else {
            $solicitudes = SolicitudServicio::with('usuario')
                            ->where('user_id', $user->id)
                            ->latest()
                            ->get();
        }

        return view('solicitudes_servicio.index', compact('solicitudes'));
    }

    public function create()
    {
        return view('solicitudes_servicio.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'descripcion_problema' => 'required|string',
        'email_notificacion'   => 'required|email',
        'archivo'             => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
    ]);

    $pathArchivo = null;
    if ($request->hasFile('archivo')) {
        $pathArchivo = $request->file('archivo')->store('archivos_servicios', 'public');
    }

    $ultimoId = SolicitudServicio::max('id') ?? 0;
    $folio = 'SER-' . str_pad($ultimoId + 1, 5, '0', STR_PAD_LEFT);

    $solicitud = SolicitudServicio::create([
        'folio'                => $folio,
        'user_id'              => Auth::id(),
        'email_notificacion'   => $request->email_notificacion,
        'descripcion_problema' => $request->descripcion_problema,
        'archivo'              => $pathArchivo,
        'estado'               => 'pendiente',
    ]);

    Mail::to($solicitud->email_notificacion)->send(new SolicitudCreadaMail($solicitud));

    return redirect()->route('solicitudes-servicio.index')->with('success', 'Solicitud registrada con éxito.');
    
    }

    public function updateStatus(Request $request, $id)
{
    $validated = $request->validate([
        'estado' => 'required|string|in:pendiente,en_proceso,completado,cancelado',
    ]);

    $solicitud = SolicitudCompra::findOrFail($id);
    $solicitud->update([
        'estado' => $validated['estado']
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Estado actualizado correctamente'
    ]);
}
	public function cancelar(Request $request, $id)
{
    $solicitud = SolicitudServicio::findOrFail($id); // Cambiar a SolicitudCompra si es en ese controlador

    // Validar que pertenezca al usuario autenticado o sea administrador
    if ($solicitud->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
        abort(403, 'No autorizado.');
    }

    if ($solicitud->estado !== 'pendiente') {
        return back()->with('error', 'Solo se pueden cancelar solicitudes pendientes.');
    }

    $solicitud->update([
        'estado' => 'cancelado'
    ]);

    return back()->with('success', 'Solicitud cancelada correctamente.');
}
}
