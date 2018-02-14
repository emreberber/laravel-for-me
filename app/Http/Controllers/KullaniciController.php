<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kullanici;
use App\Models\KullaniciDetay;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\KullaniciKayitMail;
class KullaniciController extends Controller
{

    // aşağıda bulunna diğer metodlara kullanıcı giris yapmayanlar için oluyor
    public function __construct(){
        $this->middleware('guest')->except('oturumukapat'); // oturumukapat metodu hariç
    }

    public function giris_form(){
        return view('kullanici.oturumac');
    }

    public function giris(){
        $this->validate(request(), [
            'email' => 'required|email',
            'sifre' => 'required'
        ]);
        //$kullanici->detay()->save(new KullaniciDetay());  // boş kayıt yapar.

        // sifre olarak değil de password olarak göndermemiz gerekiyor
        $credentials = [
            'email'       => request('email'), 
            'password'    => request('sifre'),
            'yonetici_mi' => 1,
            'aktif_mi'    => 1
        ];
        if(auth()->attempt($credentials, request()->has('benihatirla'))){
            request()->session()->regenerate();
            // yetki hatası alınca giris sayfasına ; giris yapınca da intented icindeki sayfaya yönlendirilecektir.
            return redirect()->intended('/');
        }
        else{
            $errors = ['email' => 'Hatalı Giriş'];
            return back()->withErrors($errors);
        }
    }

    public function kaydol_form(){
        return view('kullanici.kaydol');
    }

    public function kaydol(){
        $this->Validate(request(), [
            'adsoyad' => 'required|min:5|max:60',
            'email'   => 'required|email|unique:kullanici',
            'sifre'   => 'required|confirmed|min:5|max:15'
        ]);

        $kullanici = Kullanici::create([
            'adsoyad'             => request('adsoyad'),
            'email'               => request('email'),
            'sifre'               => Hash::make(request('sifre')),
            'aktivasyon_anahtari' => Str::random(60),
            'aktif_mi'            => 0
        ]);

        // Mail göndermede hata veriyor
        // Mail::to(request('email'))->send(new KullaniciKayitMail($kullanici));

        auth()->login($kullanici);  // laravelin kendi kullanıcı login nimeti

        return redirect()->route('anasayfa');
    }

    public function oturumukapat(){
        auth()->logout();
        request()->session()->flush();
        request()->session()->regenerate();
        return redirect()->route('anasayfa');
    }
}
