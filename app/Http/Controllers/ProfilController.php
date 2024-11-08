<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function vprofil()
    {
        $profil = Profil::all();

        if (request()->is('api/*')) {
            return response()->json($profil);
        }

        return view('profil', compact('profil'));
    }
    
    public function veditprofil($id)
    {
        $profil = Profil::find($id);
        return view('editprofil', ['profil' => $profil]);
    }

    public function show($id)
    {
        $profil = Profil::find($id);
        return response()->json(['profil' => $profil], 200);
    }

    public function updateprofil(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'alamat' => 'required',
            'jam' => 'required',
            'no_wa' => 'required',
            'deskripsi' => 'required',
        ]);
    
        $profilData = $request->only(['name', 'alamat', 'jam', 'no_wa', 'deskripsi']);
    
        Profil::where('id', $id)->update($profilData);
    
        $updatedProfil = Profil::findOrFail($id);
    
        return redirect(route('profil'))->with('success', 'Profil Sukses Diupdate!');
    }    
    
    

}
