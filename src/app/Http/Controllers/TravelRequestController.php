<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TravelRequestStatusNotification;

class TravelRequestController extends Controller
{
    // Criar novo pedido
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:departure_date',
        ]);

        $travel = TravelRequest::create([
            'user_id' => Auth::id(),
            'destination' => $validated['destination'],
            'departure_date' => $validated['departure_date'],
            'return_date' => $validated['return_date'],
            'status' => 'solicitado',
        ]);

        return response()->json($travel, 201);
    }

    // Atualizar pedido completo (admin ou dono)
    public function update(Request $request, $id)
    {
        $travel = TravelRequest::findOrFail($id);

        $isAdmin = Auth::user()->role->value === 'admin';

        if (!$isAdmin) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $rules = [
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:departure_date',
        ];

        if ($request->has('status')) {
            $rules['status'] = 'in:solicitado,aprovado,cancelado';
        }

        $validated = $request->validate($rules);

        $oldStatus = $travel->status;

        $travel->fill($validated);
        $travel->save();

        if (
            isset($validated['status']) &&
            in_array($validated['status'], ['aprovado', 'cancelado']) &&
            $validated['status'] !== $oldStatus
        ) {
            $travel->refresh(); // Garante dados atualizados
            $travel->user->notify(new TravelRequestStatusNotification($travel));
        }

        return response()->json(['message' => 'Pedido atualizado com sucesso.']);
    }

    // Listar todos os pedidos (com filtros)
    public function index(Request $request)
    {
        $query = TravelRequest::with('user');

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        if ($destination = $request->destination) {
            $query->where('destination', 'like', "%{$destination}%");
        }

        if ($from = $request->from) {
            $query->whereDate('departure_date', '>=', $from);
        }

        if ($to = $request->to) {
            $query->whereDate('return_date', '<=', $to);
        }

        $viagens = $query->orderByDesc('id')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'viagens' => $viagens,
                'user' => Auth::user(),
            ]);
        }

        return inertia('Dashboard', [
            'viagens' => $viagens,
            'user' => Auth::user(),
        ]);
    }

    public function show($id)
    {
        $travel = TravelRequest::findOrFail($id);

        if ($travel->user_id !== Auth::id()) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        return response()->json($travel);
    }

    public function updateStatus(Request $request, $id)
    {
        $travel = TravelRequest::with('user')->findOrFail($id);

        if ($travel->user_id === Auth::id()) {
            return response()->json(['message' => 'Você não pode atualizar seu próprio pedido.'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:aprovado,cancelado',
        ]);

        $oldStatus = $travel->status;
        $travel->status = $validated['status'];
        $travel->save();

        if ($oldStatus !== $validated['status']) {
            $travel->user->notify(new TravelRequestStatusNotification($travel));
        }

        return response()->json(['message' => 'Status atualizado com sucesso.']);
    }

    public function cancel($id)
    {
        $travel = TravelRequest::findOrFail($id);

        if ($travel->user_id !== Auth::id()) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        if ($travel->status !== 'aprovado') {
            return response()->json(['message' => 'Somente pedidos aprovados podem ser cancelados.'], 422);
        }

        $travel->status = 'cancelado';
        $travel->save();

        return response()->json(['message' => 'Pedido cancelado com sucesso.']);
    }

    public function destroy($id)
    {
        $travel = TravelRequest::findOrFail($id);

        if ($travel->user_id !== Auth::id() && Auth::user()->role->value !== 'admin') {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $travel->delete();

        return response()->json(['message' => 'Pedido excluído com sucesso.']);
    }
}
