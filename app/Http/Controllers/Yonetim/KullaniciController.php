<?php

namespace App\Http\Controllers\Yonetim;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Kullanici;
use Illuminate\Support\Facades\Hash;
use App\Models\KullaniciDetay;

class KullaniciController extends Controller
{
    public function oturumac(){
        if(request()->isMethod('POST')){
            $this->validate(request(), [
                'email' => 'required|email',
                'sifre' => 'required'
            ]);

            $credentials = [
                'email'       => request()->get('email'),
                'password'       => request()->get('sifre'),
                'yonetici_mi' => 1
            ];

            // musteri arayüzünden bağımsız başka bir giriş işlemini guard ile sağladık
            if(Auth::guard('yonetim')->attempt($credentials , request()->has('benihatirla'))){
                return redirect()->route('yonetim.anasayfa');
            }
            else{
                return back()->withInput()->withErrors(['email'=>'Giris Hatali']);
            }
        }
        return view('yonetim.oturumac');
    }

    public function oturumukapat(){
        Auth::guard('yonetim')->logout();
        request()->session()->flush();
        request()->session()->regenerate();

        return redirect()->route('yonetim.oturumac');
    }

    public function index(){

        if(request()->filled('aranan')){
            // arama yapıldıktan sonra arama yapılan kelime tekrar inputta gözükmesi için
            // gelen değeri sessionda tutması için flash() kullanıyoruz
            // view içinde de old() metoduyla çekebiliyoruz.
            request()->flash();
            $aranan = request('aranan');
            $list   = Kullanici::where('adsoyad', 'like', "%$aranan%")
                ->orWhere('email', 'like', "%$aranan%")
                ->orderByDesc('olusturulma_tarihi')
                ->paginate(8);
        }
        else{
            $list = Kullanici::orderByDesc('olusturulma_tarihi')->paginate(8);
        }
   
        return view('yonetim.kullanici.index', compact('list'));
    }


    // id göndermezse default 0 alacak bu yeni kayıt deme
    // id gelirse de bunun duzenleme oldugunu anlayacagız
    public function form($id = 0){
        // id 0'dan buyuk değilse boş kullanıcı gönderiyoruz
        $entry = new Kullanici;
        if($id>0){
            $entry = Kullanici::find($id);
        }

        return view('yonetim.kullanici.form', compact('entry'));
    }

    public function kaydet($id = 0){
        $this->validate(request(), [
            'adsoyad' => 'required',
            'email'   => 'required|email'
        ]);


        $data = request()->only('adsoyad', 'email');
        // sifre alanı doldurulmussa
        if(request()->filled('sifre')){
            // eger sifre alanı doldurulmussa yukarıda güncellenecek olan bilgilere yani $data'ya sifreyi de ekle
            $data['sifre'] = Hash::make(request('sifre'));
        }

        // eğer checkbox işaretlenmişse request ile gönderilir.Ve biz bunu has() metodu ile anlarız
        $data['aktif_mi'] = request()->has('aktif_mi') && request('aktif_mi') == 1 ? 1 : 0 ;
        $data['yonetici_mi'] = request()->has('yonetici_mi') && request('aktif_mi') == 1 ? 1 : 0 ;
  
        if($id > 0){
            // güncelle
            $entry = Kullanici::where('id', $id)->firstOrFail();
            $entry->update($data);

            
        }
        else{
            // kaydet
            $entry = Kullanici::create($data);
        }

        KullaniciDetay::updateOrCreate(
            ['kullanici_id' => $entry->id], // hangi alana göre islem yapacagını belirttik.
            [
                'adres' => request('adres'),
                'telefon' => request('telefon'),
                'ceptelefonu' => request('ceptelefonu')
            ] 
            );

        return redirect()
            ->route('yonetim.kullanici.duzenle', $entry->id)
            ->with('mesaj', ($id > 0 ? 'Güncellendi' : 'Kaydedildi'))
            ->with('mesaj_tur', 'success');
    }


    public function sil($id){

        // DB'de kullanıcıyı silmez.Silinme tarihi alanını doldurur.Tekrar aynı mail ile giris yapamazsın

       // Kullanici::destroy($id); bu da farklı bir gösterim
        $kullanici =  Kullanici::where('id', $id)->delete();

        return redirect()
            ->route('yonetim.kullanici')
            ->with('mesaj', 'Kullanıcı silindi')
            ->with('mesaj_tur', 'success');
    }

}
