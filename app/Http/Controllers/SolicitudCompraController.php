<?php

namespace App\Http\Controllers;

use App\Models\SolicitudCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudCreadaMail;

class SolicitudCompraController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $solicitudes = SolicitudCompra::with('usuario')->latest()->get();
        } else {
            $solicitudes = SolicitudCompra::with('usuario')
                            ->where('user_id', $user->id)
                            ->latest()
                            ->get();
        }

        return view('solicitudes_compra.index', compact('solicitudes'));
    }

    public function create()
    {
        return view('solicitudes_compra.create');
    }
    
    public function store(Request $request)
{
    $request->validate([
        'articulo_solicitado' => 'required|string|max:255',
        'justificacion'       => 'required|string',
        'email_notificacion'  => 'required|email',
        'archivo'             => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
    ]);

    $pathArchivo = null;
    if ($request->hasFile('archivo')) {
        $pathArchivo = $request->file('archivo')->store('archivos_compras', 'public');
    }

    $ultimoId = SolicitudCompra::max('id') ?? 0;
    $folio = 'COM-' . str_pad($ultimoId + 1, 5, '0', STR_PAD_LEFT);

    $solicitud = SolicitudCompra::create([
        'folio'               => $folio,
        'user_id'             => Auth::id(),
        'email_notificacion'  => $request->email_notificacion,
        'articulo_solicitado' => $request->articulo_solicitado,
        'justificacion'       => $request->justificacion,
        'archivo'             => $pathArchivo,
	'estado'              => 'pendiente',
    ]);

    Mail::to($solicitud->email_notificacion)->send(new SolicitudCreadaMail($solicitud));

    return redirect()->route('solicitudes-compra.index')->with('success', 'Solicitud creada con éxito.');
    
    }

    public function updateStatus(Request $request, SolicitudCompra $solicitud)
{
    $validated = $request->validate([
        'estado' => 'required|string|in:pendiente,en_proceso,completado,cancelado',
    ]);

    $solicitud->update([
        'estado' => $validated['estado']
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Estado actualizado correctamente'
    ]);
}
}
