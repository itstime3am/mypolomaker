<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_color extends MY_Ctrl_master {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_color';
		$this->_pageTitle = "สี";
		$this->_pageOptions['display_list_title'] = TRUE;
		$this->_pageOptions = array("type"=>"top_main");
		$this->_listDlgMoreOptions['list_deleteable'] = FALSE;
		$this->_listDlgMoreOptions['list_cancleable'] = TRUE;
	}
	function __prepareEditForm() { //for main panel
		$this->_prepareControlsDefault();

		$this->_setController("name", "ชื่อ", NULL, array("selectable"=>TRUE,"width"=>"200px","class"=>"center", "default"=>TRUE,"order"=>0));
		$this->_setController("polo_count", "โปโล", NULL, array("selectable"=>TRUE,"width"=>"100px","class"=>"center", "default"=>TRUE,"order"=>1));
		$this->_setController("tshirt_count", "เสื้อยืด", NULL, array("selectable"=>TRUE,"width"=>"100px","class"=>"center", "default"=>TRUE,"order"=>2));
		$this->_setController("cap_count", "หมวก", NULL, array("selectable"=>TRUE,"width"=>"100px","class"=>"center", "default"=>TRUE,"order"=>3));
		$this->_setController("jacket_count", "แจ็คเก็ต", NULL, array("selectable"=>TRUE,"width"=>"100px","class"=>"center", "default"=>TRUE,"order"=>4));
		$this->_setController("remark", "ข้อมูลเพิ่มเติม", array("type"=>"txa"), array("selectable"=>TRUE,"default"=>TRUE,"order"=>10));
		
		$this->_setController("rowid", "", array("type"=>"hdn"));
		$this->_setController("polo_cols", "", array("type"=>"hdn", "class"=>"no-validate no-commit"));
		$this->_setController("tshirt_cols", "", array("type"=>"hdn", "class"=>"no-validate no-commit"));
		$this->_setController("cap_cols", "", array("type"=>"hdn", "class"=>"no-validate no-commit"));
		$this->_setController("jacket_cols", "", array("type"=>"hdn", "class"=>"no-validate no-commit"));
		$this->_setController("is_cancel", "", array("type"=>"hdn", "class"=>"no-validate no-commit"));
		$this->_setController("sort_index", "", array("type"=>"hdn"));

		$this->_setController("polo_main", "สีหลัก", array("type"=>"chk", "add_attr"=>'data="polo_cols[]"', "value"=>"main"));
		$this->_setController("polo_line", "สีเส้น", array("type"=>"chk", "add_attr"=>'data="polo_cols[]"', "value"=>"line"));
		$this->_setController("polo_sub", "สีรอง", array("type"=>"chk", "add_attr"=>'data="polo_cols[]"', "value"=>"sub"));
		$this->_setController("polo_hem", "สีกุ๊น", array("type"=>"chk", "add_attr"=>'data="polo_cols[]"', "value"=>"hem"));
		$this->_setController("tshirt_main", "สีหลัก", array("type"=>"chk", "add_attr"=>'data="tshirt_cols[]"', "value"=>"main"));
		$this->_setController("tshirt_line", "สีเส้น", array("type"=>"chk", "add_attr"=>'data="tshirt_cols[]"', "value"=>"line"));
		$this->_setController("tshirt_sub", "สีรอง", array("type"=>"chk", "add_attr"=>'data="tshirt_cols[]"', "value"=>"sub"));
		$this->_setController("tshirt_hem", "สีกุ๊น", array("type"=>"chk", "add_attr"=>'data="tshirt_cols[]"', "value"=>"hem"));
		$this->_setController("cap_front", "ด้านหน้า", array("type"=>"chk", "add_attr"=>'data="cap_cols[]"', "value"=>"front"));
		$this->_setController("cap_back", "ด้านหลัง", array("type"=>"chk", "add_attr"=>'data="cap_cols[]"', "value"=>"back"));
		$this->_setController("cap_brim", "ปีกหมวก", array("type"=>"chk", "add_attr"=>'data="cap_cols[]"', "value"=>"brim"));
		$this->_setController("cap_button", "กระดุม", array("type"=>"chk", "add_attr"=>'data="cap_cols[]"', "value"=>"button"));
		$this->_setController("cap_swr", "กุ๊นขอบแซนวิช", array("type"=>"chk", "add_attr"=>'data="cap_cols[]"', "value"=>"swr"));
		$this->_setController("cap_afh", "รูตาไก่", array("type"=>"chk", "add_attr"=>'data="cap_cols[]"', "value"=>"afh"));
		$this->_setController("jacket_main", "สีหลัก", array("type"=>"chk", "add_attr"=>'data="jacket_cols[]"', "value"=>"main"));
		$this->_setController("jacket_line", "สีเส้น", array("type"=>"chk", "add_attr"=>'data="jacket_cols[]"', "value"=>"line"));
		$this->_setController("jacket_sub", "สีรอง", array("type"=>"chk", "add_attr"=>'data="jacket_cols[]"', "value"=>"sub"));
		$this->_setController("jacket_hem", "สีกุ๊น", array("type"=>"chk", "add_attr"=>'data="jacket_cols[]"', "value"=>"hem"));

		$this->_editDlgMoreOptions["layout"] = array(
			array("name")
			, "สำหรับโปโล"=>array("", "", "", "polo_main", "polo_line", "polo_sub", "polo_hem")
			, "สำหรับเสื้อยืด"=>array("", "", "", "tshirt_main", "tshirt_line", "tshirt_sub", "tshirt_hem")
			, "สำหรับหมวก"=>array("", "cap_front", "cap_back", "cap_brim", "cap_button", "cap_swr", "cap_afh")
			, "สำหรับแจ็กเก็ต"=>array("", "", "", "jacket_main", "jacket_line", "jacket_sub", "jacket_hem")
			, array("remark")
		);
	}

	function __arrSearchControls() {
		$this->_searchDlgMoreOptions["layout"] = array(
			array("return &nbsp;")
			, array("name")
			, array("is_polo", "is_tshirt")
			, array("is_jacket", "is_cap")
		);
		return array(
			array("type" => "txt", "label" => $this->_getDisplayLabel('name'), "name" => "name")
			, array("type" => "chk", "label" => "เสื้อโปโล", "name" => "is_polo") //, "value" => TRUE
			, array("type" => "chk", "label" => "เสื้อยืด", "name" => "is_tshirt") //, "value" => TRUE
			, array("type" => "chk", "label" => "แจ็กเก็ต", "name" => "is_jacket") //, "value" => TRUE
			, array("type" => "chk", "label" => "หมวก", "name" => "is_cap") //, "value" => TRUE
		);
	}
/*
	public function index() {
		$this->add_css(
			array(
				'public/css/jquery/ui/1.10.4/cupertino/jquery-ui.min.css',
				'public/css/jquery/dataTable/1.9.4/dataTables_jui.css',
				'public/css/jquery/dataTable/TableTools/2.1.5/TableTools_JUI.css'
			)
		);
		$this->add_js(
			array(
				'public/js/jquery/1.11.0/jquery.js',
				'public/js/jquery/ui/1.10.4/jquery-ui.min.js',
				//'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
				'public/js/jquery/dataTable/1.9.4/jquery.dataTables.min.js',
				//'public/js/jquery/dataTable/1.10.0/jquery.dataTables.min.js',
				'public/js/jquery/dataTable/TableTools/2.1.5/ZeroClipboard.js',
				'public/js/jquery/dataTable/TableTools/2.1.5/TableTools.min.js',
				//'public/js/jquery/dataTable/TableTools/2.2.1/dataTables.tableTools.min.js',
				'public/js/jsUtilities.js',
				'public/js/jsGlobalConstants.js'
			)
		);

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();

		$this->_setController("name", "ชื่อ", NULL, array("selectable"=>TRUE,"width"=>"200px","class"=>"center", "default"=>TRUE,"order"=>0));
		$this->_setController("polo_count", "โปโล", NULL, array("selectable"=>TRUE,"width"=>"100px","class"=>"center", "default"=>TRUE,"order"=>1));
		$this->_setController("tshirt_count", "เสื้อยืด", NULL, array("selectable"=>TRUE,"width"=>"100px","class"=>"center", "default"=>TRUE,"order"=>2));
		$this->_setController("cap_count", "หมวก", NULL, array("selectable"=>TRUE,"width"=>"100px","class"=>"center", "default"=>TRUE,"order"=>3));
		$this->_setController("jacket_count", "แจ็คเก็ต", NULL, array("selectable"=>TRUE,"width"=>"100px","class"=>"center", "default"=>TRUE,"order"=>4));
		$this->_setController("remark", "ข้อมูลเพิ่มเติม", array("type"=>"txa"), array("selectable"=>TRUE,"default"=>TRUE,"order"=>10));
		
		$this->_setController("rowid", "", array("type"=>"hdn"));
		$this->_setController("polo_cols", "", array("type"=>"hdn", "class"=>"no-validate no-commit"));
		$this->_setController("tshirt_cols", "", array("type"=>"hdn", "class"=>"no-validate no-commit"));
		$this->_setController("cap_cols", "", array("type"=>"hdn", "class"=>"no-validate no-commit"));
		$this->_setController("jacket_cols", "", array("type"=>"hdn", "class"=>"no-validate no-commit"));
		$this->_setController("is_cancel", "", array("type"=>"hdn", "class"=>"no-validate no-commit"));
		$this->_setController("sort_index", "", array("type"=>"hdn"));

		$this->_setController("polo_main", "สีหลัก", array("type"=>"chk", "add_attr"=>'data="polo_cols[]"', "value"=>"main"));
		$this->_setController("polo_line", "สีเส้น", array("type"=>"chk", "add_attr"=>'data="polo_cols[]"', "value"=>"line"));
		$this->_setController("polo_sub", "สีรอง", array("type"=>"chk", "add_attr"=>'data="polo_cols[]"', "value"=>"sub"));
		$this->_setController("polo_hem", "สีกุ๊น", array("type"=>"chk", "add_attr"=>'data="polo_cols[]"', "value"=>"hem"));
		$this->_setController("tshirt_main", "สีหลัก", array("type"=>"chk", "add_attr"=>'data="tshirt_cols[]"', "value"=>"main"));
		$this->_setController("tshirt_line", "สีเส้น", array("type"=>"chk", "add_attr"=>'data="tshirt_cols[]"', "value"=>"line"));
		$this->_setController("tshirt_sub", "สีรอง", array("type"=>"chk", "add_attr"=>'data="tshirt_cols[]"', "value"=>"sub"));
		$this->_setController("tshirt_hem", "สีกุ๊น", array("type"=>"chk", "add_attr"=>'data="tshirt_cols[]"', "value"=>"hem"));
		$this->_setController("cap_front", "ด้านหน้า", array("type"=>"chk", "add_attr"=>'data="cap_cols[]"', "value"=>"front"));
		$this->_setController("cap_back", "ด้านหลัง", array("type"=>"chk", "add_attr"=>'data="cap_cols[]"', "value"=>"back"));
		$this->_setController("cap_brim", "ปีกหมวก", array("type"=>"chk", "add_attr"=>'data="cap_cols[]"', "value"=>"brim"));
		$this->_setController("cap_button", "กระดุม", array("type"=>"chk", "add_attr"=>'data="cap_cols[]"', "value"=>"button"));
		$this->_setController("cap_swr", "กุ๊นขอบแซนวิช", array("type"=>"chk", "add_attr"=>'data="cap_cols[]"', "value"=>"swr"));
		$this->_setController("cap_afh", "รูตาไก่", array("type"=>"chk", "add_attr"=>'data="cap_cols[]"', "value"=>"afh"));
		$this->_setController("jacket_main", "สีหลัก", array("type"=>"chk", "add_attr"=>'data="jacket_cols[]"', "value"=>"main"));
		$this->_setController("jacket_line", "สีเส้น", array("type"=>"chk", "add_attr"=>'data="jacket_cols[]"', "value"=>"line"));
		$this->_setController("jacket_sub", "สีรอง", array("type"=>"chk", "add_attr"=>'data="jacket_cols[]"', "value"=>"sub"));
		$this->_setController("jacket_hem", "สีกุ๊น", array("type"=>"chk", "add_attr"=>'data="jacket_cols[]"', "value"=>"hem"));

		$pass['left_panel'] = $this->add_view(
			'_public/_search_panel'
			, array(
				'controls' => array(
					array(
						"type" => "txt"
						, "label" => $this->_getDisplayLabel('name')
						, "name" => "name"
					)
				)
				, "layout" => array(
					array("return &nbsp;")
					, array("name")
				)
			)
			, TRUE
		);
		
		$template['dataview_fields'] = $this->_arrDataViewFields;
		$template['list_deleteable'] = FALSE;
		$template['list_cancelable'] = TRUE;
		$template['edit_template'] = $this->load->view(
			'_public/_form'
			, array(
				'controls' => $this->_arrGetEditControls(),
				'layout' => array(
					array("name")
					, "สำหรับโปโล"=>array("", "", "", "polo_main", "polo_line", "polo_sub", "polo_hem")
					, "สำหรับเสื้อยืด"=>array("", "", "", "tshirt_main", "tshirt_line", "tshirt_sub", "tshirt_hem")
					, "สำหรับหมวก"=>array("", "cap_front", "cap_back", "cap_brim", "cap_button", "cap_swr", "cap_afh")
					, "สำหรับแจ็กเก็ต"=>array("", "", "", "jacket_main", "jacket_line", "jacket_sub", "jacket_hem")
					, array("remark")
				)
			)
			, TRUE
		);	
		$pass['work_panel'] = $this->add_view('_public/_list', $template, TRUE);

		$this->add_js('public/js/master_color/form.js');
		$pass['title'] = "สีผ้า";
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
*/
}