<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use App\Customer;
use App\CustomerAddress;

class CustomerAddressController extends Controller
{
    public function all()
    {
        $data = CustomerAddress::All();
        return response()->json(['error' => false, 'msg' => 'Daftar Alamat Pelanggan', 'data' => $data], 200);
    }

    public function show($idPelanggan)
    {
        // $data = CustomerAddress::where('id_customer', $idPelanggan)->orderBy('utama', 'desc')->get();
        $data = DB::table('tb_alamat_pelanggan as ap')
                    ->join('tb_provinsi as p','p.id','=','ap.kode_provinsi')
                    ->join('tb_kota as k','k.id','=','ap.kode_kota')
                    ->join('tb_kecamatan as kc','kc.id','=','ap.kode_kecamatan')
                    ->where('ap.id_customer', $idPelanggan)
                    ->whereNull('ap.deleted_at')
                    ->select('ap.*','p.keterangan as provinsi','k.keterangan as kota','kc.keterangan as kecamatan')
                    ->get();
        if ($data) {
            return response()->json(['error' => false, 'msg' => 'Alamat Pelanggan #' . $idPelanggan, 'data' => $data], 200);
        }
        return response()->json(['error' => false, 'msg' => 'Data tidak ditemukan', 'data' => []], 200);
    }

    public function store(Request $request)
    {
        $insert = CustomerAddress::FirstOrCreate([
            "id_customer"    => $request->json()->get('id_customer'),
            "keterangan"     => $request->json()->get('keterangan'),
            "kode_provinsi"  => $request->json()->get('kode_provinsi'),
            "kode_kota"      => $request->json()->get('kode_kota'),
            "kode_kecamatan" => $request->json()->get('kode_kecamatan'),
            "kodepos"        => $request->json()->get('kodepos'),
            "rt"             => $request->json()->get('rt'),
            "rw"             => $request->json()->get('rw'),
            "alamat"         => $request->json()->get('alamat'),
        ]);

        if ($insert) {
            return response()->json(['error' => false, 'msg' => 'Alamat Berhasil Ditambahkan', 'data' => null], 200);
        }
        return response()->json(['error' => true, 'msg' => 'Something Gone Wrong', 'data' => null], 500);
    }

    public function batch(Request $request)
    {
        $data = $request->json()->all();
        $exec = CustomerAddress::insert($data);

        $status = ($exec) ? true : false;

        $msg = array(
            'status'  => $status,
            'message' => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }

    public function update(Request $request, $id)
    {
        $update = CustomerAddress::FindOrFail($id)->update([
            "keterangan"     => $request->json()->get('keterangan'),
            "kode_provinsi"  => $request->json()->get('kode_provinsi'),
            "kode_kota"      => $request->json()->get('kode_kota'),
            "kode_kecamatan" => $request->json()->get('kode_kecamatan'),
            "kodepos"        => $request->json()->get('kodepos'),
            "rt"             => $request->json()->get('rt'),
            "rw"             => $request->json()->get('rw'),
            "alamat"         => $request->json()->get('alamat'),
        ]);

        if ($update) {
            return response()->json(['error' => false, 'msg' => 'Alamat Berhasil Diubah', 'data' => null], 200);
        }
        return response()->json(['error' => true, 'msg' => 'Something Gone Wrong', 'data' => null], 500);
    }

    public function delete($id)
    {
        $data = CustomerAddress::findOrFail($id)->delete();
        $status = ($data) ? true : false;

        if ($status) {
            return response()->json(['error' => false, 'msg' => 'Alamat Berhasil Dihapus', 'data' => null], 200);
        }
        return response()->json(['error' => true, 'msg' => 'Something Gone Wrong', 'data' => null], 500);

    }

    public function makeDefault($idPelanggan, $idAlamat)
    {

        CustomerAddress::where('id_customer', $idPelanggan)
            ->update(['utama' => 0]);

        $default = CustomerAddress::find($idAlamat);
        $default->utama = 1;
        $default->save();

        if ($default) {
            return response()->json(['error' => false, 'msg' => 'Alamat Utama Berhasil Diubah', 'data' => null], 200);
        }
        return response()->json(['error' => true, 'msg' => 'Something Gone Wrong', 'data' => null], 500);
    }
}
