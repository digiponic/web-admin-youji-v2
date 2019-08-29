<?php namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Session;	
	use DB;
	use CRUDBooster;

	class AdminTbProdukController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "50";
			$this->orderby = "gudang,asc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "tb_produk";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Gudang","name"=>"gudang","join"=>"tb_general,keterangan"];
			$this->col[] = ["label"=>"Gambar","name"=>"gambar","image"=>true];
			$this->col[] = ["label"=>"Keterangan","name"=>"keterangan"];
			$this->col[] = ["label"=>"Kategori","name"=>"kategori","join"=>"tb_general,keterangan"];
			$this->col[] = ["label"=>"Satuan Jual","name"=>"satuan_jual_keterangan","visible" => false];
			$this->col[] = ["label"=>"Satauan Bahan","name"=>"satuan_bahan_keterangan","visible" => false];
			$this->col[] = ["label"=>"Stok Jual","name"=>"stok_jual","callback"=>function($row){				
				return number_format($row->stok_jual,0,',','.').' |@ '.$row->satuan_jual_keterangan;
			}];
			$this->col[] = ["label"=>"Stok Bahan","name"=>"stok_bahan","callback"=>function($row){
				return number_format($row->stok_bahan,0,',','.').' | '.$row->satuan_bahan_keterangan;
			}];
			$this->col[] = ["label"=>"Harga","name"=>"harga_jual","callback"=>function($row){				
				return 'Rp '.number_format($row->harga_jual,0,',','.');
			}];
			# END COLUMNS DO NOT REMOVE THIS LINE

			$kode = DB::table('tb_produk')->max('id') + 1;
			$kode = 'PRD/'.str_pad($kode,5,0,STR_PAD_LEFT);

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Kode','name'=>'kode','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10','readonly'=>'1','value'=>$kode];
			// $this->form[] = ['label'=>'Cabang','name'=>'cabang','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 2'];
			$this->form[] = ['label'=>'Gudang','name'=>'gudang','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 8'];
			$this->form[] = ['label'=>'Nama Produk','name'=>'keterangan','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Jenis','name'=>'jenis','type'=>'select2','validation'=>'integer|required','width'=>'col-sm-10','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 4'];
			$this->form[] = ['label'=>'Kategori','name'=>'kategori','type'=>'select2','validation'=>'integer','width'=>'col-sm-10','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 3'];
			$this->form[] = ['label'=>'Satuan Bahan','help'=>'*Digunakan untuk gudang','name'=>'satuan_bahan','type'=>'select2','validation'=>'integer','width'=>'col-sm-10','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 5'];
			$this->form[] = ['label'=>'Satuan Jual','help'=>'*Digunakan untuk berjualan','name'=>'satuan_jual','type'=>'select2','validation'=>'integer','width'=>'col-sm-10','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 5'];
			// if(CRUDBooster::myPrivilegeId() != 4)
			// 	$this->form[] = ['label'=>'Harga Beli','help'=>'*harga per satuan','name'=>'harga_beli','type'=>'money','validation'=>'min:0','width'=>'col-sm-10','value'=>0];
			// if(CRUDBooster::myPrivilegeId() != 6)
			$this->form[] = ['label'=>'Harga','help'=>'*harga per satuan jual','name'=>'harga_jual','type'=>'money','validation'=>'min:0','width'=>'col-sm-10','value'=>0];
			$this->form[] = ['label'=>'Gambar','name'=>'gambar','type'=>'upload','width'=>'col-sm-10','validation'=>'image|max:1000','upload_encrypt'=>true];
			$this->form[] = ['label'=>'Deskripsi','name'=>'deskripsi','type'=>'textarea','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Kode','name'=>'kode','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10','readonly'=>true,'value'=>$kode];
			//$this->form[] = ['label'=>'Cabang','name'=>'cabang','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 2'];
			//$this->form[] = ['label'=>'Nama Produk','name'=>'keterangan','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Jenis','name'=>'jenis','type'=>'select','validation'=>'integer|required','width'=>'col-sm-10','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 4'];
			//$this->form[] = ['label'=>'Kategori','name'=>'kategori','type'=>'select','validation'=>'integer','width'=>'col-sm-10','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 3'];
			//$this->form[] = ['label'=>'Satuan','name'=>'satuan','type'=>'select','validation'=>'integer','width'=>'col-sm-10','datatable'=>'tb_general,keterangan','datatable_where'=>'kode_tipe = 5'];
			//$this->form[] = ['label'=>'Harga','name'=>'harga','type'=>'number','validation'=>'integer|min:0','width'=>'col-sm-10','value'=>0];
			# OLD END FORM

			/*
	        | ----------------------------------------------------------------------
	        | Sub Module
	        | ----------------------------------------------------------------------
			| @label          = Label of action
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        |
	        */
	        $this->sub_module = array();
			$this->sub_module[] = ['label'=>'','path'=>'tb_produk_detail','parent_columns'=>'kode,keterangan','foreign_key'=>'kode_produk','button_color'=>'info','button_icon'=>'fa fa-bars','showIf'=>'[jenis] == 22'];
			$this->sub_module[] = ['title'=>'Kartu Stok Jual','path'=>'tb_produk_stok_jual','parent_columns'=>'gudang_keterangan,kode,keterangan,satuan_jual_keterangan,stok_jual','parent_columns_alias'=>'Gudang,Kode,Keterangan,Satuan,Stok','foreign_key'=>'kode_produk','button_color'=>'warning','button_icon'=>'fa fa-cube'];
			$this->sub_module[] = ['title'=>'Kartu Stok Bahan','path'=>'tb_produk_stok_bahan','parent_columns'=>'gudang_keterangan,kode,keterangan,satuan_bahan_keterangan,stok_bahan','parent_columns_alias'=>'Gudang,Kode,Keterangan,Satuan,Stok','foreign_key'=>'kode_produk','button_color'=>'danger','button_icon'=>'fa fa-cubes'];

	        /*
	        | ----------------------------------------------------------------------
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   	= Default is primary. (primary, warning, succecss, info)
	        | @id 	   		= Id of action
	        | @title 	   	= Title of action
	        | @onclick 	   = OnClick JS of action
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1 ss
	        |
	        */
	        $this->addaction = array();

	        /*
	        | ----------------------------------------------------------------------
	        | Add More Button Selected
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button
	        | Then about the action, you should code at actionButtonSelected method
	        |
	        */
	        $this->button_selected = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------
	        | @message = Text of message
	        | @type    = warning,success,danger,info
	        |
	        */
	        $this->alert        = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add more button to header button
	        | ----------------------------------------------------------------------
	        | @label = Name of button
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        |
	        */
	        $this->index_button = array();
			$this->index_button[] = ['label'=>'Produksi Stok Jual','url'=>CRUDBooster::mainpath("produksi"),"icon"=>"fa fa-fw fa-industry",'color'=>'danger'];


	        /*
	        | ----------------------------------------------------------------------
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
	        |
	        */
			$this->table_row_color = array();
			$this->table_row_color[] = ['condition'=>"[stok_jual] == 0","color"=>"danger"];


	        /*
	        | ----------------------------------------------------------------------
	        | You may use this bellow array to add statistic at dashboard
	        | ----------------------------------------------------------------------
	        | @label, @count, @icon, @color
	        |
	        */
			$this->index_statistic = array();
			$this->index_statistic[] = ['label'=>'Total Produk','count'=>DB::table('tb_produk')->whereNull('deleted_at')->count(),'icon'=>'fa fa-file','color'=>'primary'];
			$this->index_statistic[] = ['label'=>'Stok Jual Menipis','count'=>DB::table('tb_produk')->where('stok_jual','<=',5)->whereNull('deleted_at')->count(),'icon'=>'fa fa-cube','color'=>'warning'];
			$this->index_statistic[] = ['label'=>'Stok Jual Kosong','count'=>DB::table('tb_produk')->where('stok_jual',0)->whereNull('deleted_at')->count(),'icon'=>'fa fa-cube','color'=>'danger'];



	        /*
	        | ----------------------------------------------------------------------
	        | Add javascript at body
	        | ----------------------------------------------------------------------
	        | javascript code in the variable
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code before index table
	        | ----------------------------------------------------------------------
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code after index table
	        | ----------------------------------------------------------------------
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include Javascript File
	        | ----------------------------------------------------------------------
	        | URL of your javascript each array
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        $this->load_js[] = asset("vendor/crudbooster/assets/select2/dist/js/select2.min.js");
	        $this->load_js[] = asset("js/produksi.js");



	        /*
	        | ----------------------------------------------------------------------
	        | Add css style at body
	        | ----------------------------------------------------------------------
	        | css code in the variable
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;



	        /*
	        | ----------------------------------------------------------------------
	        | Include css File
	        | ----------------------------------------------------------------------
	        | URL of your css each array
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        $this->load_css[] = asset("vendor/crudbooster/assets/select2/dist/css/select2.min.css");
	        $this->load_css[] = asset("css/produksi.css");


	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for button selected
	    | ----------------------------------------------------------------------
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here

	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate query of index result
	    | ----------------------------------------------------------------------
	    | @query = current sql query
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
			// if(!CRUDBooster::isSuperAdmin() || CRUDBooster::myPrivilegeId() != 2){
			// 	$query->where('cabang',CRUDBooster::myCabang());			
			// }
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate row of index table html
	    | ----------------------------------------------------------------------
	    |
	    */
	    public function hook_row_index($column_index,&$column_value) {
			//Your code here
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before add data is execute
	    | ----------------------------------------------------------------------
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {
	        //Your code here
			$postdata['created_user'] = CRUDBooster::myName();
			$postdata['stok_bahan'] = 0;
			$postdata['stok_jual'] = 0;
			$postdata['gudang_keterangan'] = DB::table('tb_general')->where('id',$postdata['gudang'])->value('keterangan');
			$postdata['satuan_bahan_keterangan'] = DB::table('tb_general')->where('id',$postdata['satuan_bahan'])->value('keterangan');
			$postdata['satuan_jual_keterangan'] = DB::table('tb_general')->where('id',$postdata['satuan_jual'])->value('keterangan');
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after add public static function called
	    | ----------------------------------------------------------------------
	    | @id = last insert id
	    |
	    */
	    public function hook_after_add($id) {
	        //Your code here
			$stok = array(
				'kode_produk'	=> $id,
				'tanggal'		=> date('Y-m-d H:i:s'),
				'stok_masuk'	=> 0,
				'stok_keluar'	=> 0,
				'keterangan'	=> 'Stok produk baru'
			);

			DB::table('tb_produk_stok_jual')->insert($stok);
			DB::table('tb_produk_stok_bahan')->insert($stok);
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before update data is execute
	    | ----------------------------------------------------------------------
	    | @postdata = input post data
	    | @id       = current id
	    |
	    */
	    public function hook_before_edit(&$postdata,$id) {
			//Your code here
			$postdata['updated_user'] = CRUDBooster::myName();
			$postdata['gudang_keterangan'] = DB::table('tb_general')->where('id',$postdata['gudang'])->value('keterangan');
			$postdata['satuan_bahan_keterangan'] = DB::table('tb_general')->where('id',$postdata['satuan_bahan'])->value('keterangan');
			$postdata['satuan_jual_keterangan'] = DB::table('tb_general')->where('id',$postdata['satuan_jual'])->value('keterangan');
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_edit($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_delete($id) {
	        //Your code here
			DB::table('tb_produk')->where('id', $id)->update(['deleted_user' => CRUDBooster::myName()]);
	    }

		//By the way, you can still create your own method in here... :)
		
		public function getProduksi()
		{
			$data = [];
			$data['url'] = CRUDBooster::apiPath();
			$data['page_title'] = 'Produksi Stok Jual';
			$data['produk'] = DB::table('tb_produk')->whereNull('deleted_at')->get();

			//Please use cbView method instead view method from laravel
			$this->cbView('produksi_add', $data);
		}

		public function postAddProduksi(Request $request)
		{
			$form = $request->all();
			// dd($form);

			$prd = CRUDBooster::first('tb_produk', $form['produk']);
			$jumlah = $prd->stok_jual + $form['jumlah'];

			DB::table('tb_produk')->where('id', $form['produk'])->update([
				'stok_jual'		=> $jumlah,
				'stok_bahan'	=> $form['sisa_bahan'],
			]);

			DB::table('tb_produk_stok_bahan')->insert([
				'kode_produk'	=> $form['produk'],
				'tanggal'		=> date('Y-m-d H:i:s'),
				'stok_masuk'	=> 0,
				'stok_keluar'	=> $form['jumlah_bahan'],
				'keterangan'	=> 'Pengurangan stok untuk produksi '.$form['jumlah'].' @'.$form['satuan_jual'],
				'created_user'	=> CRUDBooster::myName()
			]);

			DB::table('tb_produk_stok_jual')->insert([
				'kode_produk'	=> $form['produk'],
				'tanggal'		=> date('Y-m-d H:i:s'),
				'stok_masuk'	=> $form['jumlah'],
				'stok_keluar'	=> 0,
				'keterangan'	=> 'Penambahan stok dari produksi '.$form['jumlah_bahan'].' '.$form['satuan_bahan'],
				'created_user'	=> CRUDBooster::myName()
			]);

			$message = 'Produksi stok jual berhasil';
			$type = 'primary';
			CRUDBooster::redirectBack($message, $type);

		}


	}