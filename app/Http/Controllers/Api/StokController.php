<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StokController extends Controller
{
    protected function logRequest(Request $request, $response)
    {
        Log::channel('api')->info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user' => Auth::user() ? Auth::user()->id : 'unauthenticated',
            'ip' => $request->ip(),
            'request' => $request->all(),
            'response' => $response
        ]);
    }
    
    public function index()
    {
        $stoks = Stok::all();

        $response = [
            'message' => 'Stock list retrieved successfully',
            'data' => $stoks
        ];

        $this->logRequest(request(), $response);

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string',
            'jumlah_stok' => 'required|integer',
            'nomor_seri' => 'required|string',
            'additional_info' => 'nullable|json',
            'gambar_barang' => 'nullable|image|max:2048',
        ]);

        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['updated_by'] = Auth::user()->id;

        if ($request->hasFile('gambar_barang')) {
            $validatedData['gambar_barang'] = $request->file('gambar_barang')->store('gambar_barang', 'public');
        }

        $stok = Stok::create($validatedData);

        $response = [
            'message' => 'Stock created successfully',
            'data' => $stok
        ];

        $this->logRequest($request, $response);
        
        return response()->json($response, 201);
    }

    public function show($id)
    {
        $stok = Stok::find($id);

        if (!$stok) {
            $response = [
                'message' => 'Stock not found'
            ];
            $this->logRequest(request(), $response);
            return response()->json($response, 404);
        }

        $response = [
            'message' => 'Stock details retrieved successfully',
            'data' => $stok
        ];

        $this->logRequest(request(), $response);

        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $stok = Stok::find($id);

        if (!$stok) {
            $response = [
                'message' => 'Stock not found'
            ];
            $this->logRequest($request, $response);
            return response()->json($response, 404);
        }

        $validatedData = $request->validate([
            'nama_barang' => 'sometimes|string',
            'jumlah_stok' => 'sometimes|integer',
            'nomor_seri' => 'sometimes|string',
            'additional_info' => 'nullable|json',
            'gambar_barang' => 'nullable|image|max:2048',
        ]);

        $validatedData['updated_by'] = auth()->id();
        
        if ($request->hasFile('gambar_barang')) {
            $validatedData['gambar_barang'] = $request->file('gambar_barang')->store('gambar_barang', 'public');
        }

        $stok->update($validatedData);

        $response = [
            'message' => 'Stock updated successfully',
            'data' => $stok
        ];

        $this->logRequest($request, $response);

        return response()->json($response);
    }

    public function destroy($id)
    {
        $stok = Stok::find($id);
        
        if (!$stok) {
            $response = [
                'message' => 'Stock not found'
            ];
            $this->logRequest(request(), $response);
            return response()->json($response, 404);
        }

        $stok->delete();

        $response = [
            'message' => 'Stock deleted successfully'
        ];

        $this->logRequest(request(), $response);

        return response()->json($response);
    }
}
