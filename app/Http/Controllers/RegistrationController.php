<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'registration_date' => 'required|date',
            // kolom lainnya
        ]);

        // Simpan data pendaftaran pasien
        $registration = Registration::create([
            'patient_id' => $validated['patient_id'],
            'registration_date' => $validated['registration_date'],
            'status' => 'menunggu',  // status pendaftaran, bisa disesuaikan
        ]);

        // Generate nomor antrean otomatis
        $queue_number = Queue::where('registration_id', $registration->id)
                              ->count() + 1; // nomor antrean berikutnya

        // Simpan data antrean dengan nomor yang sudah digenerate
        Queue::create([
            'registration_id' => $registration->id,
            'queue_number' => $queue_number,
            'status' => 'menunggu',  // status antrean
        ]);

        // Kembalikan response atau redirect
        return redirect()->route('registrations.index');
    }
}
