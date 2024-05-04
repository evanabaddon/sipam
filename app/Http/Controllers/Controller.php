<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $tagihanBelumLunas;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->tagihanBelumLunas = Pemakaian::where('status', 'belum dibayar')->count();
            // Menyimpan tagihan belum lunas ke dalam variable $tagihanBelumLunas
            view()->share('tagihanBelumLunas', $this->tagihanBelumLunas); // Menyimpannya ke dalam variabel yang bisa diakses di semua view
            return $next($request);
        });
    }
}
