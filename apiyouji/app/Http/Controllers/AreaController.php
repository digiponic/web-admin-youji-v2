<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;

class AreaController extends Controller
{
    public function data(Request $request)
    {
        $param = $request->json()->all();

        // cek apakah ada parameter
        if (empty($param)) {
            $data = DB::table('tb_provinsi')->whereNull('deleted_at')->where('is_visible', 1)->get();
            return response()->json(['error' => false, 'msg' => 'Daftar Provinsi', 'data' => $data], 200);
        } else {
            if ($param['tingkat'] == 'kota') {
                $data = DB::table('tb_kota')
                    ->where('kode_provinsi', $param['kode'])
                    ->where('is_visible', 1)
                    ->whereNull('deleted_at')->get();
            } else {
                $data = DB::table('tb_kecamatan')
                    ->where('kode_kota', $param['kode'])
                    ->where('is_visible', 1)
                    ->whereNull('deleted_at')->get();
            }
            /*$area = DB::table('tb_provinsi')
                ->where('kode', $param['kode'])
                ->where('is_visible', 1)
                ->whereNull('deleted_at')
                ->first();
            // cek kode di area provinsi
            if (empty($area)) {
                // cek kode di area kota
                $area = DB::table('tb_kota')
                    ->where('kode', $param['kode'])
                    ->where('is_visible', 1)
                    ->whereNull('deleted_at')
                    ->first();
                if (empty($area)) {
                    // jika kosong
                    $data = [];
                } else {
                    $data = DB::table('tb_kecamatan')
                        ->where('kode_kota', $area->id)
                        ->where('is_visible', 1)
                        ->whereNull('deleted_at')->get();
                }
            } else {
                $data = DB::table('tb_kota')
                    ->where('kode_provinsi', $area->id)
                    ->whereNull('deleted_at')
                    ->get();
                if (empty($data)) {
                    $area = DB::table('tb_kota')
                        ->where('kode', $param['kode'])
                        ->first();
                    $data = DB::table('tb_kecamatan')
                        ->where('kode_kota', $area->id)
                        ->whereNull('deleted_at')
                        ->get();
                }
            }*/
            if ($data) {
                return response()->json(['error' => false, 'msg' => 'Daftar ' . ucfirst($param['tingkat']), 'data' => $data], 200);
            }
            return response()->json(['error' => false, 'msg' => 'Pelanggan Tidak Ditemukan', 'data' => []], 200);
        }
    }

}
