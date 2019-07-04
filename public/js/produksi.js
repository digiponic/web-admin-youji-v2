$(document).ready(function(){
    $('.select2').select2();

    var api_path = $('#url').val();
    var stok_bahan = 0;
    var sisa_bahan = 0;
    var packVal = 0;

    $('#produk').change(function(){
        var id = $(this).val();
        $.ajax({
            method: 'GET',
            url: api_path + '/produk',
            data: {id: id},
            success: function(res){
                console.log('Response: ', res);
                var prd = res[0];

                stok_bahan = prd.stok_bahan;
                $('#stok_bahan').val(stok_bahan);

                // Parsing string to int only
                packVal = prd.satuan_jual_keterangan.replace(/[^0-9\.]+/g, "");
                packVal = (packVal === '') ? 1 : parseInt(packVal);                

                $('#satuan_jual').val(prd.satuan_jual_keterangan);
                $('#satuan_bahan').val(prd.satuan_bahan_keterangan);

                var satuan_jual_str = '@ ' + prd.satuan_jual_keterangan;
                var satuan_bahan_str = '@ ' + prd.satuan_bahan_keterangan;
                $('.satuan_jual').html(satuan_jual_str);
                $('.satuan_bahan').html(satuan_bahan_str);
            },
            error: function(err){
                console.log(err);                
            }
        });
    });

    setInterval(function(){

        var jumlah = $('#jumlah').val();

        var jumlah_bahan = (jumlah * packVal);
        sisa_bahan = stok_bahan - jumlah_bahan; 

        $('#sisa_bahan').val(sisa_bahan);
        $('#jumlah_bahan').val(jumlah_bahan);

        if(sisa_bahan < 0){
            $('#btnSave').prop('disabled', true);
        }else{
            $('#btnSave').prop('disabled', false);
        }
        
    }, 500);

});