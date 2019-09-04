<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ModelPembelian;
use App\ModelBarang;
use Validator;

class Pembelian extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ModelPembelian::all();
        return view('pembelian', compact('data'));
    }

    /**
     * Show the form for creating a new resource. 
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pembelian_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'kd_barang' => 'required',
            'jumlah' => 'required',
            'total_harga' => 'required',
        ]); 
        //-> validasi

        //ini yang menambah data
        $data = new ModelPembelian();
        $data->kd_barang = $request->kd_barang;
        $data->jumlah = $request->jumlah;
        $data->total_harga = $request->total_harga;
        $data->save();

        //ini merubah data dari controller barang
        $databeli = ModelBarang::where('kd_barang', $request->kd_barang)->first();

        // script kurang tambah stok
        $databeli->stok = $databeli->stok + $request->jumlah;
        $databeli->save();

        return redirect()->route('pembelian.index')->with('alert_message','Berhasil menambahkan data!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ModelPembelian::where('id', $id)->get();
        return view('pembelian_edit', compact('data'));
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
        $this->validate($request, [
            'kd_barang' => 'required',
            'jumlah' => 'required',
            'total_harga' => 'required',
        ]);

        $data = ModelPembelian::where('id', $id)->first();
        $data->kd_barang = $request->kd_barang;
        $data->jumlah = $request->jumlah;
        $data->total_harga = $request->total_harga;
        $data->save();

        return redirect()->route('pembelian.index')->with('alert_message', 'Berhasil Mengubah data!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data =ModelPembelian::where('id', $id)->first();
        $data->delete();

        return redirect()->route('pembelian.index')->with('alert_message', 'Berhasil menghapus data:)');
    }
}
