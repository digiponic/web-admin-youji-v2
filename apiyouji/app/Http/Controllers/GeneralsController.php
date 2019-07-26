<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use App\Generals;

class GeneralsController extends Controller
{
    public function all()
    {
        $data = Generals::All();
        return $data;
    }

    public function show($id)
    {
        $data = Generals::find($id);
        return $data;
    }

    public function store(Request $request)
    {
        $data = Generals::FirstOrCreate([
            "code"         => $request->json()->get('code'),
            "name"         => $request->json()->get('name'),
            "desc"         => $request->json()->get('desc'),
            "type"         => $request->json()->get('type'),
            "created_user" => null,
            "updated_user" => null,
            "deleted_user" => null,
        ]);
        $status = ($data) ? true : false;
        $msg = array(
            'status'  => $status,
            'message' => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }

    public function update(Request $request, Response $response, $id)
    {
        $data = Generals::FindOrFail($id)->update([
            "code"         => $request->json()->get('code'),
            "name"         => $request->json()->get('name'),
            "desc"         => $request->json()->get('desc'),
            "type"         => $request->json()->get('type'),
            "created_user" => null,
            "updated_user" => null,
            "deleted_user" => null,
        ]);
        $status = ($data) ? true : false;
        $msg = array(
            'status'  => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }

    public function delete($id)
    {
        $data = Generals::FindOrFail($id)->delete();
        $status = ($data) ? true : false;
        $msg = array(
            'status'  => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }

    public function payment()
    {
        $data = Generals::where('kode_tipe', 7)->get();
        foreach ($data as $value) {
            $value->gambar = url('../../public/'.$value->gambar);
        }
        return response()->json(['error' => false, 'msg' => 'Metode Pembayaran', 'data' => $data], 200);
    }

    public function minShopping()
    {
        $data = DB::table('cms_settings')
            ->select('content', 'helper')
            ->where('name', 'minimal_belanja')->first();
        // return response()->json(['error' => false, 'msg' => 'Nominal Minimal Belanja', 'data' => (array)$data], 200);
        $data->content = (int)$data->content;
        return response()->json($data);
    }

    public function cekOngkir($kodeKecamatan)
    {
        $data = DB::table('tb_ongkoskirim')
            ->select('harga_ongkos')
            ->where('kode_kecamatan', $kodeKecamatan)->first();
        if (!$data) {
            $data2 = DB::table('cms_settings')
                ->select('content', 'helper')
                ->where('name', 'ongkos_kirim')->first();
            $data2 = (array)$data2;
            $result = [];
            $result['harga_ongkos'] = $data2['content'];
            return response()->json($result, 200);
        }
        return response()->json((array)$data, 200);
    }

    public function getSlider()
    {
        // $data = DB::table('tb_slider')
        //     ->select('keterangan', 'gambar')
        //     ->where('status', 1)
        //     ->orderBy('created_at', 'desc')
        //     ->limit(5)
        //     ->get();

        // foreach ($data as $value) {
        //     $value->gambar = url('../../public/'.$value->gambar);
        // }       
        
        $path = DB::table('cms_settings')->where('name','cabang')->value('content');
        $path = 'http://'.$_SERVER['SERVER_NAME'].'/'.strtolower($path).'/storage/app/';
        
        $data = DB::table('tb_slider')
                    ->whereNull('deleted_at')
                    ->where('status', 1)
                    ->select('keterangan', 'gambar')
                    ->get();

        foreach ($data as $value) {
            $value->gambar = $path.$value->gambar;
        }
        
        return response()->json($data, 200);
    }
}
