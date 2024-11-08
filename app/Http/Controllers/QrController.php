<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class QrController extends Controller
{
    public function AdminQr($id)
    {
        $user = User::findOrFail($id);
    
        $qrToken = $user->qr_token;
    
        $qrCode = QrCode::size(400)->generate(route('masuk', ['qrToken' => $qrToken]));
    
        $filename = "qrcodes/" . Str::random(10) . ".svg"; // Generating a random filename
        Storage::disk('public')->put($filename, $qrCode);
    
        return view('qrcode', ['filename' => $filename, 'user' => $user]);
    }
    

    public function UserQr($id)
    {
        $user = User::findOrFail($id); 
        $qrToken = $user->qr_token;
    
        $url = 'https://sivinaries.my.id/login?qrToken=' . $qrToken;
        $qrCode = QrCode::size(400)->generate($url);
    
        $filename = "qrcodes/" . Str::random(10) . ".svg"; // Generating a random filename
        Storage::disk('public')->put($filename, $qrCode);
    
        return view('qrcode', ['filename' => $filename, 'user' => $user]);
    }
        
}
