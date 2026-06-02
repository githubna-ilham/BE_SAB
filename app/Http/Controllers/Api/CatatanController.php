<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Catatan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Catatan::orderBy('dibuat_pada', 'desc')->get();

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function show(int $id): JsonResponse
    {
        $c = Catatan::find($id);
        if (! $c) {
            return response()->json(['success' => false, 'message' => 'Catatan tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'data' => $c]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'judul' => 'required|string|max:150',
            'isi' => 'required|string',
            'kategori' => 'required|string|max:50',
            'dibuat_pada' => 'nullable|date',
        ]);
        $data['dibuat_pada'] = $data['dibuat_pada'] ?? now();
        $c = Catatan::create($data);

        return response()->json(['success' => true, 'data' => $c], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $c = Catatan::find($id);
        if (! $c) {
            return response()->json(['success' => false, 'message' => 'Catatan tidak ditemukan.'], 404);
        }
        $data = $request->validate([
            'judul' => 'required|string|max:150',
            'isi' => 'required|string',
            'kategori' => 'required|string|max:50',
        ]);
        $c->update($data);

        return response()->json(['success' => true, 'data' => $c]);
    }

    public function destroy(int $id): JsonResponse
    {
        $c = Catatan::find($id);
        if (! $c) {
            return response()->json(['success' => false, 'message' => 'Catatan tidak ditemukan.'], 404);
        }
        $c->delete();

        return response()->json(['success' => true, 'message' => 'Catatan dihapus.']);
    }
}
