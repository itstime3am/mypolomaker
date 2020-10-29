<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Polo_pattern extends MY_Ctrl_crud {
	function __construct() 
	{
		parent::__construct();
		$this->modelName = 'Mdl_polo_pattern';
	}
	
	public function index()
	{
		$this->add_css(
			array(
				'public/css/jquery/ui/1.10.4/cupertino/jquery-ui.min.css',
				//'public/css/jquery/dataTable/1.10.0/jquery.dataTables.min.css',
				//'public/css/jquery/dataTable/TableTools/2.2.1/dataTables.tableTools.min.css'
				'public/css/jquery/dataTable/1.9.4/dataTables_jui.css',
				'public/css/jquery/dataTable/TableTools/2.1.5/TableTools_JUI.css'
			)
		);
		$this->add_js(
			array(
				'public/js/jquery/1.11.0/jquery.js',
				'public/js/jquery/ui/1.10.4/jquery-ui.min.js',
				'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
				'public/js/jquery/dataTable/1.9.4/jquery.dataTables.min.js',
				//'public/js/jquery/dataTable/1.10.0/jquery.dataTables.min.js',
				'public/js/jquery/dataTable/TableTools/2.1.5/ZeroClipboard.js',
				//'public/js/jquery/dataTable/TableTools/2.1.5/TableTools.min.js',
				'public/js/jquery/dataTable/TableTools/2.2.1/dataTables.tableTools.min.js',
				'public/js/jsUtilities.js',
				'public/js/jsGlobalConstants.js'
			)
		);
		
		$this->_selOptions = $this->_prepareSelectOptions(array(
				'base_pattern'=>array('where'=>array('is_polo'=>1)), 
				'standard_pattern'=>array('where'=>array('is_polo'=>1)), 
				'neck_type'=>array('where'=>array('is_polo'=>1)), 
				'fabric'=>array('where'=>array('is_polo'=>1)), 
				'clasper_type', 
				'clasper_ptrn', 
				'collar_type'=>array('where'=>array('is_polo'=>1)), 
				'sleeves_type'=>array('where'=>array('is_polo'=>1)), 
				'flap_type'=>array('where'=>array('is_polo'=>1)), 
				'flap_side_ptrn'=>array('where'=>array('is_polo'=>1)), 
				'pocket_type'=>array('where'=>array('is_polo'=>1)), 
				//'pen_type'=>array('where'=>array('is_polo'=>1)), 
				'pen_pattern'=>array('where'=>array('is_polo'=>1)),
				'pen_position'=>array('where'=>array('is_polo'=>1))
			)
		);

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();
		//++ set special attributes
		$this->_setController("code", "รหัส", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>0));
		$this->_setController("fabric", "ชนิดผ้า", array(), array("selectable"=>TRUE, "default"=>TRUE,"order"=>1)); //
		$this->_setController("color", "สีหลัก", array("maxlength"=>30), array("selectable"=>TRUE, "default"=>TRUE,"order"=>2));
		$this->_setController('m_clasper_type', "ทรงสาบกระดุมผู้ชาย", array(), array("selectable"=>TRUE,"order"=>3));
		$this->_setController('f_clasper_type', "ทรงสาบกระดุมผู้หญิง", array(), array("selectable"=>TRUE,"order"=>4));
		$this->_setController("clasper_ptrn", "แบบสาบกระดุม", array(), array("selectable"=>TRUE,"order"=>5));
		$this->_setController("neck_type_rowid", ""); //แบบคอเสื้อ
		//$this->_setController("neck_type_detail", "", array("type"=>"txa", "maxlength"=>140));
		$this->_setController("base_pattern_rowid", ""); //แบบทรงเสื้อ
		//$this->_setController("base_pattern_detail", "", array("type"=>"txa", "maxlength"=>140));
		$this->_setController("standard_pattern_rowid", ""); //แบบเสื้อ
		//$this->_setController("standard_pattern_detail", "", array("type"=>"txa", "maxlength"=>140));
		$this->_setController("fabric_rowid", ""); //ชนิดผ้า
		$this->_setController("color_add1", "สีตัดต่อ", array("type"=>"txa", "maxlength"=>50));
		//$this->_setController("color_add2", "");
		$this->_setController('m_clasper_type_rowid', "ทรงสาบกระดุมชาย", array('sel_options'=>$this->_selOptions['clasper_type']));
		$this->_setController('f_clasper_type_rowid', "ทรงสาบกระดุมหญิง", array('sel_options'=>$this->_selOptions['clasper_type']));
		$this->_setController("clasper_ptrn_rowid", "แบบสาบกระดุม");
		$this->_setController("clasper_detail", "", array("type"=>"txt", "maxlength"=>85));
		$this->_setController("collar_type_rowid", ""); //แบบปก
		$this->_setController("collar_detail", "", array("type"=>"txa", "maxlength"=>90));
		$this->_setController("m_sleeves_type_rowid", "แบบแขนเสื้อชาย", array('sel_options'=>$this->_selOptions['sleeves_type']));
		$this->_setController("f_sleeves_type_rowid", "แบบแขนเสื้อหญิง", array('sel_options'=>$this->_selOptions['sleeves_type']));
		$this->_setController("sleeves_detail", "", array("type"=>"txt", "maxlength"=>50));
		$this->_setController("flap_type_rowid", ""); //ชายเสื้อ
		$this->_setController("flap_type_detail", "", array("type"=>"txt", "maxlength"=>20));
		$this->_setController("m_flap_side", "เสื้อผู้ชาย", array("type"=>"chk"));
		$this->_setController("f_flap_side", "เสื้อผุ้หญิง", array("type"=>"chk"));
		$this->_setController("flap_side_ptrn_rowid", "แบบผ่าข้าง");
		$this->_setController("flap_side_ptrn_detail", "", array("type"=>"txt", "maxlength"=>50));
		$this->_setController("m_pocket", "เสื้อผู้ชาย", array("type"=>"chk"));
		$this->_setController("f_pocket", "เสื้อผู้หญิง", array("type"=>"chk"));
		$this->_setController("pocket_type_rowid", "แบบกระเป๋า");
		$this->_setController("pocket_type_detail", "", array("type"=>"txa", "maxlength"=>50));
		$this->_setController("m_pen", "เสื้อผู้ชาย", array("type"=>"chk"));
		$this->_setController("f_pen", "เสื้อผู้หญิง", array("type"=>"chk"));
		$this->_setController("pen_pattern_rowid", "", array("type"=>"rdo"));
		//$this->_setController("pen_type_rowid", "");
		$this->_setController("pen_position_rowid", "", array("type"=>"rdo"));
		$this->_setController("pen_detail", "", array("type"=>"txa", "maxlength"=>50));
		$this->_setController("remark1", "", array("type"=>"txa", "maxlength"=>85));
		$this->_setController("remark2", "", array("type"=>"txa", "maxlength"=>120));
		//$this->_setController("remark3", "", array("type"=>"txa", "maxlength"=>180));
		//-- set special attributes	

		$this->_arrControlLayout = array(
			array('code', ''),
			"แบบคอเสื้อ" => array('neck_type_rowid', 'neck_type_detail'),
			"แบบทรงเสื้อ" => array('base_pattern_rowid', 'base_pattern_detail'),
			"แบบเสื้อ" => array('standard_pattern_rowid', 'standard_pattern_detail'),
			"ชนิดผ้า" => array('fabric_rowid', ''),
			'สี' => array(
				array('color', ''), 
				array('color_add1', '')
			),
			'สาบกระดุม' => array(
				array('m_clasper_type_rowid', 'f_clasper_type_rowid'),
				array('clasper_ptrn_rowid', 'clasper_detail')
			),
			'ปก' => array('collar_type_rowid', 'collar_detail'),
			'แขนเสื้อ' => array(
				array('m_sleeves_type_rowid', 'f_sleeves_type_rowid'),
				array('sleeves_detail')
			),
			'ชายเสื้อ' => array(
				array('flap_type_rowid', 'flap_type_detail')
			),
			'ผ่าข้าง' => array(
				array('m_flap_side', 'f_flap_side', '', ''),
				array('flap_side_ptrn_rowid', 'flap_side_ptrn_detail')
			),
			'กระเป๋าเสื้อ' => array(
				array('m_pocket', 'f_pocket', '', ''),
				array('pocket_type_rowid', 'pocket_type_detail')
			),
			'ที่เสียบปากกา' => array(
				array('m_pen', 'f_pen', '', ''),
				"แบบ" => array('pen_pattern_rowid'),
				"ตำแหน่งที่เสียบปากกา" => array('pen_position_rowid', 'pen_detail')
			),
			'รายละเอียดเพิ่มเติม' => array(
				array('remark1'),
				array('remark2')/*,
				array('remark3')*/
			)
		);
		
		$pass['left_panel'] = $this->add_view(
			'_public/_search_panel', 
			array(
				'controls' => array(
					array(
						"type" => "txt",
						"label" => "รหัส",
						"name" => "code"
					),
					array(
						"type" => "sel",
						"label" => "ชนิดผ้า",
						"name" => "fabric_rowid",
						"sel_options" => $this->_selOptions['fabric'],
						"sel_val" => "rowid",
						"sel_text" => "name"
					)
				)
			),
			TRUE
		);
		
		$template['dataview_fields'] = $this->_arrDataViewFields;
		$template['edit_template'] = $this->load->view(
			'_public/_form', 
			array(
				'controls' => $this->_arrGetEditControls(),
				'layout' => $this->_arrControlLayout
			),
			TRUE
		);
			
		$pass['work_panel'] = $this->add_view('_public/_list', $template, TRUE);

		$pass['title'] = "แบบเสื้อโปโลสำเร็จรูป";
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
}