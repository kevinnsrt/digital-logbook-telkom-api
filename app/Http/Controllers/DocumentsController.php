<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\Notifications;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    //
    public function index(){

        $data = Documents::with('user')->get();
        return response()->json(
            $data
            , 200);
    }

    public function create(){
        return view('crud.create');
    }

    public function history(){
        return view('crud.history');
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


    $data = Documents::create([
        'title'    => $validated['title'],
        'customer' => $validated['customer'],
        'mitra'    => $validated['mitra'],
        'price'    => $validated['price'],
        'jangka_waktu' => $validated['jangka_waktu'],
        'status'   => 'ready',
        'user_id'  => 1, 
        'admin_id' => 1, 
    ]);
    
    $firebase->sendToTopic(
        'logbook_updates', // Nama topik yang di-subscribe sama anak-anak Flutter
        'Dokumen Baru Tersedia! 📄', // Judul Notifikasi
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
            $admin->fcm_token, // Token target (HP Admin)
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

    // 4. Kirim notifikasi pakai sendToToken ke HP user tersebut
    if ($documentOwner && $documentOwner->fcm_token) {
        $firebase->sendToToken(
            $documentOwner->fcm_token, // Token HP si user yang request
            '✅ Request NPK Disetujui', // Judul
            "Request pengambilan untuk dokumen '{$data->title}' telah disetujui oleh Admin." // Isi pesan
        );
    }

  return response()->json([
    'success' => true,
    'message' => 'Approved',
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
        // 'user_id'  => 1, 
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
