@extends("crudbooster::admin_template")
@section("content")
 
   <div class="panel panel-default">
      <div class="panel-heading">
         Produksi Stok Jual
      </div>
      <div class="panel-body">
         <form class='form-horizontal' method='post' id="form" action='{{ CRUDBooster::mainpath('add-produksi') }}'>
            {{ csrf_field() }}
            <input type="hidden" id="url" value="{{ $url }}">
            <input type="hidden" id="jumlah_bahan" name="jumlah_bahan">
            <input type="hidden" id="satuan_jual" name="satuan_jual">
            <input type="hidden" id="satuan_bahan" name="satuan_bahan">

            <div class="form-group">
               <label class="control-label col-sm-2">
                  Produk <span class="text-danger" title='This field is required'>*</span>
               </label>
               <div class="col-sm-10">
                  <select class="form-control select2" name="produk" id="produk" required>
                     <option value="">Silahkan pilih produk</option>
                     @foreach ($produk as $item)
                        <option value="{{ $item->id }}">{{ $item->keterangan }}</option>
                     @endforeach
                  </select>
               </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-2">
                  Stok Bahan
               </label>
               <div class="col-sm-2">                  
                  <input class="form-control" type="text" id="stok_bahan" name="stok_bahan" value="0" readonly>
               </div>
               <label class="control-label satuan_bahan">
                  @ 
               </label>               
            </div>
            <div class="form-group">
               <label class="control-label col-sm-2">
                  Jumlah Produksi
               </label>
               <div class="col-sm-2">                  
                  <input class="form-control" type="number" id="jumlah" name="jumlah" value="0" onkeyup="this.value = (this.value[0] === '-') ? ('-' + this.value.replace(/[^0-9]/g, '')) : (this.value.replace(/[^0-9]/g, ''));" pattern="^-?\d+">
               </div>
               <label class="control-label satuan_jual">
                     @ 
               </label>
            </div>
            <div class="form-group">
                  <label class="control-label col-sm-2">
                     Sisa Stok Bahan
                  </label>
                  <div class="col-sm-2">                  
                     <input class="form-control" type="text" id="sisa_bahan" name="sisa_bahan" value="0" readonly>
                  </div>
                  <label class="control-label satuan_bahan">
                     @ 
                  </label>
               </div>
            <div class="box-footer">
               <div class="form-group">
                  <label class="control-label col-sm-2"></label>
                  <div class="col-sm-10">
                    <input type="submit" name="submit" id="btnSave" value='Save' class='btn btn-success'>
                    {{-- <input type="button" name="print" id="btnPrint" value='Print' class='btn btn-primary'> --}}
                  </div>
               </div>
            </div><!-- /.box-footer-->
         </form>

      </div>
   </div>

@endsection
