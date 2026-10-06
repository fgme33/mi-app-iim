<?php

namespace App\Http\Controllers;

use App\Models\SolicitudCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitudCreadaMail;
use App\Models\Articulo;

class SolicitudCompraController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $query = SolicitudCompra::with(['usuario', 'detalles.articulo'])->latest();

    if (!$user->isAdmin()) {
        $query->where('user_id', $user->id);
    }

    $solicitudes = $query->get();

    return view('solicitudes_compra.index', compact('solicitudes'));
}
    public function create()
    {
         $articulos = Articulo::orderBy('nombre')->pluck('nombre');
	 return view('solicitudes_compra.create', compact('articulos'));
    }
    
   public function store(Request $request)
{
    if ($request->hasFile('archivo') && !$request->file('archivo')->isValid()) {
        return back()->withErrors([
            'archivo' => 'El archivo es demasiado grande o no se pudo subir. Máximo 5MB.',
        ])->withInput();
    }

    $validated = $request->validate([
        'justificacion'       => 'required|string',
        'email_notificacion'  => 'required|email',
        'archivo'              => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        'articulos'            => 'required|array|min:1',
        'articulos.*'          => 'required|string|max:255',
        'cantidades'           => 'required|array|min:1',
        'cantidades.*'         => 'required|integer|min:1',
    ], [
        'articulos.required'     => 'Debes agregar al menos un artículo.',
        'articulos.*.required'   => 'El nombre del artículo no puede quedar vacío.',
        'cantidades.*.required'  => 'Indica la cantidad de cada artículo.',
        'cantidades.*.integer'   => 'La cantidad debe ser un número.',
        'cantidades.*.min'       => 'La cantidad mínima es 1.',
    ]);

    if (count($validated['articulos']) !== count($validated['cantidades'])) {
        return back()->withErrors(['articulos' => 'Ocurrió un error con los artículos enviados.'])->withInput();
    }

    $pathArchivo = null;
    if ($request->hasFile('archivo')) {
        $pathArchivo = $request->file('archivo')->store('archivos_compras', 'public');
    }

    $ultimoId = SolicitudCompra::max('id') ?? 0;
    $folio = 'COM-' . str_pad($ultimoId + 1, 5, '0', STR_PAD_LEFT);

    // Resumen de texto para mantener compatibilidad con el campo original
    $resumen = collect($validated['articulos'])
        ->map(fn($nombre, $i) => trim($nombre) . ' x' . $validated['cantidades'][$i])
        ->implode(', ');

    $solicitud = SolicitudCompra::create([
        'folio'                => $folio,
        'user_id'              => Auth::id(),
        'email_notificacion'   => $validated['email_notificacion'],
        'articulo_solicitado'  => $resumen,
        'justificacion'        => $validated['justificacion'],
        'archivo'               => $pathArchivo,
        'estado'                => 'pendiente',
    ]);

    foreach ($validated['articulos'] as $i => $nombreArticulo) {
        $articulo = Articulo::firstOrCreate([
            'nombre' => trim($nombreArticulo),
        ]);

        $solicitud->detalles()->create([
            'articulo_id' => $articulo->id,
            'cantidad'    => $validated['cantidades'][$i],
        ]);
    }

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

	public function cancelar(Request $request, $id)
{
    $solicitud = SolicitudCompra::findOrFail($id); // O SolicitudCompra según corresponda

    // Opcional: Validar que pertenezca al usuario autenticado y esté pendiente
    if ($solicitud->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
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
