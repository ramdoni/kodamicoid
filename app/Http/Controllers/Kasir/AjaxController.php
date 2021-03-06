<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kodami\Models\Mysql\RekeningBankUser;

class AjaxController extends Controller
{
    protected $respon;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        /**
         * [$this->respon description]
         * @var [type]
         */
        $this->respon = ['message' => 'error', 'data' => []];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ;
    }

    /**
     * [submitSimpananSukarela description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function submitSimpananSukarela(Request $request)
    {
        if($request->ajax())
        {   
            $deposit                    = new \Kodami\Models\Mysql\Deposit();
            $deposit->no_invoice        = no_invoice();
            $deposit->user_id           = $request->user_id;
            $deposit->status            = 3;
            $deposit->nominal           = $request->nominal;
            $deposit->type              = 4;
            $deposit->proses_user_id    = \Auth::user()->id;
            $deposit->save(); 

            # update table user anggota
            $user_anggota = UserAnggota::where('user_id', $request->user_id)->first();
            if(!$user_anggota)
            {
                $user_anggota = new UserAnggota();
                $user_anggota->simpanan_sukarela = $request->nominal;
            }
            else
            {
                $user_anggota->simpanan_sukarela = $user_anggota->simpanan_sukarela + $request->nominal;
            }
            $user_anggota->save();

            $this->respon = ['message' => 'success', 'link_cetak' => route('kasir.anggota.cetak-kwitansi', ['id'=>$deposit->id,'jenis_transaksi'=>'0'])];

            return response()->json($this->respon);
        }
    }
}