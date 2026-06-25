<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\Notifications;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    //
public function index(Request $request){
    $query = Documents::with('user');

    if ($request->has('search') && $request->search != '') {
        $keyword = $request->search;

        $query->where(function($q) use ($keyword) {
            $q->where('title', 'LIKE', "%{$keyword}%")
              ->orWhere('status', 'LIKE', "%{$keyword}%")
              ->orWhere('jangka_waktu', 'LIKE', "%{$keyword}%"); 
        });
    }

    // Eksekusi query
    $data = $query->latest()->get(); 

    return response()->json($data, 200);
}

public function total(){
    $totalDocuments = Documents::count();
    $totalUsers = User::count();
    $totalTaken = Documents::where('status','taken')->count();
    
    return response()->json([
        'totalTaken' => $totalTaken,
        'totalUsers'  => $totalUsers,
        'totalDocuments'  => $totalDocuments
    ], 200);
}

    public function create(){
        return view('crud.create');
    }

    public function history(){
       $data = Documents::where('status', 'taken')->with('user')->get();
       return response()->json($data,200);
    }

// create function
public function add(Request $request, FirebaseService $firebase) {
    $validated = $request->validate([
        'title' => 'required|max:255',
        'customer' => 'required|max:255',
        'mitra' => 'required|max:255',
        'price' => 'required|numeric',
        'jangka_waktu' => 'required|max:255',
    ]);

    $user = $request->user();
    $userId = $user->id ;


    $data = Documents::create([
        'title'    => $validated['title'],
        'customer' => $validated['customer'],
        'mitra'    => $validated['mitra'],
        'price'    => $validated['price'],
        'jangka_waktu' => $validated['jangka_waktu'],
        'status'   => 'ready',
        'user_id'  => $request->userId, 
        'admin_id' => $request->userId, 
    ]);
    
    $firebase->sendToTopic(
        'logbook_updates', 
        'Dokumen Baru Tersedia! 📄',
        'Dokumen NPK Judul. ' . $data->title . ' layanan ' . $data->jangka_waktu. ' baru ditambahkan.' // Isi Notifikasi
    );

  return response()->json([
    'success' => true,
    'message' => 'Data NPK Berhasil Ditambahkan',
    'data'    => $data
    ], 200);

}

// delete function
public function destroy($id)
{
    $data = Documents::findOrFail($id);

    if ($data->status =! 'ready'){
    return response()->json([
    'success' => true,
    'message' => 'Tidak dapat menghapus data',
    ], 200);
    }
    $data->delete();

    return response()->json([
    'success' => true,
    'message' => 'Data NPK Berhasil Dihapus',
    ], 200);
}


// edit function
public function update(Request $request) {

    $validated = $request->validate([
        'title' => 'required|max:255',
        'customer' => 'required|max:255',
        'mitra' => 'required|max:255',
        'price' => 'required|numeric',
    ]);

    $data = Documents::find($request->id);

    if (!$data) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }
    $data->update([
        'title'    => $validated['title'],
        'customer' => $validated['customer'],
        'mitra'    => $validated['mitra'],
        'price'    => $validated['price'],
        'status'   => 'ready',
        'user_id'  => 1, 
        'admin_id' => 1, 
    ]);

  return response()->json([
    'success' => true,
    'message' => 'Data NPK Berhasil Diedit',
    'data'    => $data
    ], 200);

}

// pending function
public function pending(Request $request, $id, FirebaseService $firebase) {

    $data = Documents::find($id);

    if (!$data) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    $user = $request->user();
    $userId = $user ? $user->id : 1;

    $data->update([
        'status'   => 'pending',
        'user_id'  => $userId, 
        'admin_id' => 1, 
    ]);

    $user = $request->user();

    $admin = \App\Models\User::where('role', 'admin')->first(); 
    
    if ($admin && $admin->fcm_token) {
        $firebase->sendToToken(
            $admin->fcm_token, 
            '⏳ Request Pengambilan NPK',
            "User " . ($user ? $user->name : 'Anggota') . " mengajukan request untuk mengambil NPK '{$data->title}'."
        );
    }

  return response()->json([
    'success' => true,
    'message' => 'Pending',
    'data'    => $data
    ], 200);

}

// approved function
public function approved(Request $request, FirebaseService $firebase) {

    $data = Documents::find($request->id);

    if (!$data) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }
    $data->update([
        'status'   => 'approved',
        'admin_id' => 1, 
    ]);

   $documentOwner = \App\Models\User::find($data->user_id);

    if ($documentOwner && $documentOwner->fcm_token) {
        $firebase->sendToToken(
            $documentOwner->fcm_token, 
            '✅ Request NPK Disetujui',
            "Request pengambilan untuk dokumen '{$data->title}' telah disetujui oleh Admin." // Isi pesan
        );
    }

  return response()->json([
    'success' => true,
    'message' => 'Approved',
    'data'    => $data
    ], 200);

}

public function reject(Request $request, FirebaseService $firebase) {

    $data = Documents::find($request->id);

    if (!$data) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }
    $data->update([
        'status'   => 'ready',
        'admin_id' => 1, 
    ]);

   $documentOwner = \App\Models\User::find($data->user_id);

    if ($documentOwner && $documentOwner->fcm_token) {
        $firebase->sendToToken(
            $documentOwner->fcm_token, 
            'Request NPK Ditolak',
            "Request pengambilan untuk dokumen '{$data->title}' telah ditolak oleh Admin."
        );
    }
    $data->update([
        'user_id'   => 1,
    ]);

  return response()->json([
    'success' => true,
    'message' => 'Rejected',
    'data'    => $data
    ], 200);

}

// taken function
public function taken(Request $request) {

    $time = now();
    $data = Documents::find($request->id);

    if (!$data) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }
    $data->update([
        'status'   => 'taken',
        'user_id'  => $request->user()->id, 
        'admin_id' => 1, 
        'taken_at'=> $time,
    ]);

    Notifications::create([
        'title' => '🚀 Dokumen NPK Selesai Diambil',
        'message' => "Proses selesai! Dokumen '{$data->title}' telah resmi diambil oleh pihak terkait.",
        'status_type' => 'taken',
        'user_id' => auth()->id(),
    ]);

  return response()->json([
    'success' => true,
    'message' => 'Taken',
    'data'    => $data
    ], 200);

}



}
