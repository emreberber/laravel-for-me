<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class AnasayfaController extends Controller
{
    public function index(){
        $kategoriler = Kategori::where('ust_id', null)->take(8)->get();
        return view('anasayfa', compact('kategoriler'));
    }
}
