<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ControllerLogin;
use App\ModelUser; 

class AnggotaController extends ControllerLogin
{	
	/**
	 * [index description]
	 * @return [type] [description]
	 */
    public function index()
    {
    	$data = ModelUser::where('access_id', 2)->get();

    	return view('admin.anggota.index', compact('data'));
    }

    /**
     * [confirm description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function confirm($id)
    {
        $params['data']         = Modeluser::where('id', $id)->first();
        $params['deposit']      = \Kodami\Models\Mysql\Deposit::where('type', 1)->where('user_id', $id)->first();

        return view('admin.anggota.confirm')->with($params);
    }

    /**
     * [confirmSubmit description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function confirmSubmit(Request $request)
    {
        if($request->status == 1)
        {
            $status = \Kodami\Models\Mysql\Deposit::where('id', $request->deposit_id)->first();
            $status->status = 3;
            $status->save(); 

            $user = \App\UserModel::where('id', $status->user_id)->first();

            /** Rubah status angota jadi active */
            $user = ModelUser::where('id', $status->user_id)->first();
            $user->status = 2;
            $user->save();

            // Insert Simpanan Pokok
            $deposit                = new \Kodami\Models\Mysql\Deposit();
            $deposit->no_invoice    = $status->no_invoice; 
            $deposit->status        = 3;
            $deposit->type          = 3; // Simpanan Pokok
            $deposit->user_id       = $status->user_id;
            $deposit->nominal       = get_setting('simpanan_pokok');
            $deposit->save();  

            // Insert Simpanan Wajib
            $deposit                = new \Kodami\Models\Mysql\Deposit();
            $deposit->no_invoice    = $status->no_invoice; 
            $deposit->status        = 3; 
            $deposit->type          = 5; // Simpanan Wajib
            $deposit->user_id       = $status->user_id;
            $deposit->nominal       = $user->durasi_pembayaran * get_setting('simpanan_wajib');
            $deposit->save();

            // Insert Simpanan Sukarela
            $deposit                = new \Kodami\Models\Mysql\Deposit();
            $deposit->no_invoice    = $status->no_invoice; 
            $deposit->status        = 3; 
            $deposit->type          = 4; // Simpanan Sukarela
            $deposit->user_id       = $status->user_id;
            $deposit->nominal       = $user->first_simpanan_sukarela + $status->code;
            $deposit->save();
        }
        else
        {
            $deposit = \Kodami\Models\Mysql\Deposit::where('id', $id)->first();
            $deposit->status = 4;
            $deposit->save();

             /** Rubah status angota jadi reject */
            $user = ModelUser::where('id', $deposit->user_id)->first();
            $user->status = 3;
            $user->save();
        }

        return redirect()->route('admin.anggota.index')->with('message-success', 'Data berhasil di konfirmasi');
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        return view('admin.anggota.create');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $user = ModelUser::where('id', $id)->first();
        $data['data'] 	= $user;
        $data['id'] 	= $id;
        
        return view('admin.anggota.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data =  ModelUser::where('id', $id)->first();
        
        $data->nik         = $request->nik; 
        $data->name        = $request->nama; 
        $data->jenis_kelamin= $request->jenis_kelamin; 
        $data->email        = $request->email;
        $data->telepon      = $request->telepon;
        $data->agama        = $request->agama;
        $data->tempat_lahir = $request->tempat_lahir;
        $data->tanggal_lahir = $request->tanggal_lahir;

        if ($request->hasFile('file_ktp')) {
            
            $image = $request->file('file_ktp');
            
            $name = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/file_ktp/'. Auth::user()->id);
            
            $image->move($destinationPath, $name);

            $data->foto_ktp = $name;
        }

        if ($request->hasFile('file_photo')) {
            
            $image = $request->file('file_photo');
            
            $name = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/file_photo/'. Auth::user()->id);
            
            $image->move($destinationPath, $name);
            
            $data->foto = $name;
        }
        

        $data->save();

        return redirect()->route('anggota.index')->with('message-success', 'Data berhasil disimpan'); 
    }


    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = ModelUser::where('id', $id)->first();
        $data->delete();

        return redirect()->route('anggota.index')->with('message-sucess', 'Data berhasi di hapus');
    }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $no_anggota = date('y').date('m').date('d'). (ModelUser::all()->count() + 1);

        $this->validate($request,[
            'nik'               => 'required|unique:users',
            'telepon'           => 'required',
            'name'              => 'required',
            'email'             => 'required|email|unique:users',
            'password'          => 'required',
            'confirmation'      => 'required|same:password',
        ]);
        
        $data               =  new ModelUser();
        $data->no_anggota   = $no_anggota;
        $data->nik          = $request->nik; 
        $data->name         = $request->nama; 
        $data->jenis_kelamin= $request->jenis_kelamin; 
        $data->email        = $request->email;
        $data->telepon      = $request->telepon;
        $data->agama        = $request->agama;
        $data->tempat_lahir = $request->tempat_lahir;
        $data->tanggal_lahir= $request->tanggal_lahir;
        $data->password             = bcrypt($request->password); 
        $data->access_id    = 2; // Akses sebagai anggota
        $data->status       = 1; // menunggu pembayaran 
        $data->save();

        if ($request->hasFile('file_ktp')) {
            
            $image = $request->file('file_ktp');
            
            $name = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/file_ktp/'. $data->id);
            
            $image->move($destinationPath, $name);

            $data->foto_ktp = $name;
        }

        if ($request->hasFile('file_photo')) {
            
            $image = $request->file('file_photo');
            
            $name = time().'.'.$image->getClientOriginalExtension();
            
            $destinationPath = public_path('/file_photo/'. $data->id);
            
            $image->move($destinationPath, $name);
            
            $data->foto = $name;
        }
        

        $data->save();

        return redirect()->route('anggota.index')->with('message-success', 'Data berhasil disimpan'); 
   }
}
