<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_tshirt_pattern extends MY_Ctrl_crud {
	function __construct() 
	{
		parent::__construct();
		$this->modelName = 'Mdl_tshirt_pattern';
	}
	
	public function index()
	{
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
				'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
				'public/js/jquery/dataTable/1.9.4/jquery.dataTables.min.js',
				'public/js/jquery/dataTable/TableTools/2.2.1/ZeroClipboard.js',
				'public/js/jquery/dataTable/TableTools/2.2.1/dataTables.tableTools.min.js',
				'public/js/jsUtilities.js',
				'public/js/jsGlobalConstants.js'
			)
		);
		
		$this->_selOptions = $this->_prepareSelectOptions(array(
				'standard_pattern'=>array('where'=>array('is_cancel'=>0,'is_tshirt'=>1),'order_by'=>'sort_index')
				,'neck_type'=>array('where'=>array('is_cancel'=>0,'is_tshirt'=>1),'order_by'=>'sort_index')
				,'fabric'=>array('where'=>array('is_cancel'=>0,'is_tshirt'=>1),'order_by'=>'sort_index')
				,'main_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND tshirt_cols LIKE '%,main,%'",'order_by'=>'sort_index')
				,'line_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND tshirt_cols LIKE '%,line,%'",'order_by'=>'sort_index')
				,'sub_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND tshirt_cols LIKE '%,sub,%'",'order_by'=>'sort_index')
				,'option_hem'=>array('table_prefix'=>'m_','where'=>array('is_cancel'=>0),'order_by'=>'sort_index')
				,'option_hem_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND tshirt_cols LIKE '%,hem,%'",'order_by'=>'sort_index')
				,'collar_type'=>array('where'=>array('is_cancel'=>0,'is_tshirt'=>1),'order_by'=>'sort_index')
				,'sleeves_type'=>array('where'=>array('is_cancel'=>0,'is_tshirt'=>1),'order_by'=>'sort_index')
				,'flap_type'=>array('where'=>array('is_cancel'=>0,'is_tshirt'=>1),'order_by'=>'sort_index')
				,'flap_side_ptrn'=>array('where'=>array('is_cancel'=>0,'is_tshirt'=>1),'order_by'=>'sort_index')
				,'pocket_type'=>array('where'=>array('is_cancel'=>0,'is_tshirt'=>1),'order_by'=>'sort_index')
				,'pen_pattern'=>array('where'=>array('is_cancel'=>0,'is_tshirt'=>1),'order_by'=>'sort_index')
				,'order_screen'=>array('where'=>array('is_cancel'=>0,'is_tshirt'=>1),'order_by'=>'sort_index')
			)
		);

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();
		//++ set special attributes
		$this->_setController("code", "รหัส", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>0));
		$this->_setController("fabric", "ชนิดผ้า", array(), array("selectable"=>TRUE, "default"=>TRUE,"order"=>1));
		$this->_setController("color", "สีหลัก", array(), array("selectable"=>TRUE, "default"=>TRUE,"order"=>2));
		$this->_setController("sleeve_type", "แบบแขนเสื้อ", array(), array("selectable"=>TRUE, "default"=>TRUE,"order"=>3));

		$this->_setController('standard_pattern_rowid', ''); //แบบทรงเสื้อ
		$this->_setController('fabric_rowid', ''); //ชนิดผ้า
		$this->_setController('neck_type_rowid', ''); //แบบคอเสื้อ
		$this->_setController('neck_type_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>140));
		$this->_setController('m_collar_type_rowid', 'แบบซกคอชาย', array('sel_options'=>$this->_selOptions['collar_type'])); //new
		$this->_setController('f_collar_type_rowid', 'แบบซกคอหญิง', array('sel_options'=>$this->_selOptions['collar_type'])); //new
		$this->_setController('collar_detail', '', array('type'=>'txa', 'maxlength'=>85));
		$this->_setController('main_color_rowid', 'สีผ้าหลัก'); //- new 
		//$this->_setController('line_color_rowid', 'สีวิ่งเส้น'); //- new 
		$this->_setController('sub_color1_rowid', 'สีรอง1', array('sel_options'=>$this->_selOptions['sub_color'])); //- new 
		$this->_setController('sub_color2_rowid', 'สีรอง2', array('sel_options'=>$this->_selOptions['sub_color'])); //- new 
		$this->_setController('sub_color3_rowid', 'สีรอง3', array('sel_options'=>$this->_selOptions['sub_color'])); //- new 
		$this->_setController('color_detail', '', array('type'=>'txa', 'rows'=>1)); //- new
		$this->_setController('m_sleeves_type_rowid', 'แขนเสื้อชาย', array('sel_options'=>$this->_selOptions['sleeves_type'])); //- new 
		$this->_setController('f_sleeves_type_rowid', 'แขนเสื้อหญิง', array('sel_options'=>$this->_selOptions['sleeves_type'])); //- new 
		$this->_setController('sleeves_detail', '', array('type'=>'txa', 'maxlength'=>85));
		$this->_setController('flap_type_rowid', 'รูปแบบชายเสื้อ');
		$this->_setController('flap_type_detail', '', array('type'=>'txa', 'maxlength'=>85));
		$this->_setController('flap_side_ptrn_rowid', ''); //แบบผ่าข้าง
		$this->_setController('flap_side_ptrn_detail', '', array('type'=>'txt', 'maxlength'=>50));
		$this->_setController('m_flap_side', 'เสื้อผู้ชาย', array('type'=>'chk'));
		$this->_setController('f_flap_side', 'เสื้อผุ้หญิง', array('type'=>'chk'));
		$this->_setController('pocket_type_rowid', ''); //แบบกระเป๋าเสื้อ
		$this->_setController('pocket_type_detail', '', array('type'=>'txt', 'maxlength'=>50));
		$this->_setController('m_pocket', 'กระเป๋าเสื้อชาย', array('type'=>'chk'));
		$this->_setController('f_pocket', 'กระเป๋าเสื้อหญิง', array('type'=>'chk'));
		$this->_setController('pen_pattern_rowid', ''); //new
		$this->_setController('pen_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>60)); //new
		$this->_setController('m_pen', 'เสื้อผู้ชาย', array('type'=>'chk')); //new
		$this->_setController('f_pen', 'เสื้อผุ้หญิง', array('type'=>'chk')); //new
		$this->_setController('is_pen_pos_left', 'แขนซ้าย', array('type'=>'chk')); //new
		$this->_setController('is_pen_pos_right', 'แขนขวา', array('type'=>'chk')); //new
		$this->_setController('remark1', '', array('type'=>'txa','rows'=>2, 'maxlength'=>140));
		$this->_setController('remark2', '', array('type'=>'txa','rows'=>2, 'maxlength'=>140));
		
		// ++ option table join ++
		//$this->_setController('option_hem_rowid', 'เพิ่มกุ๊น', array('type'=>'rdo', 'sel_options'=>$this->_selOptions['option_hem'])); //- new 
		//$this->_setController('option_hem_color_rowid', 'สีกุ๊น', array('type'=>'sel', 'sel_options'=>$this->_selOptions['option_hem_color']));
		$this->_setController('option_is_mfl', 'เสื้อผู้ชาย', array('type'=>'chk','class'=>'set-disabled')); //new
		$this->_setController('option_male_fix_length', '', array('type'=>'txt', 'maxlength'=>30)); //new
		$this->_setController('option_is_ffl', 'เสื้อผู้หญิง', array('type'=>'chk','class'=>'set-disabled')); //new
		$this->_setController('option_female_fix_length', '', array('type'=>'txt', 'maxlength'=>30)); //new
		$this->_setController('option_is_no_neck_tag', 'ไม่ติดป้ายคอใดๆทั้งสิ้น', array('type'=>'chk')); //new
		$this->_setController('option_is_customer_size_tag', 'ติดป้ายไซส์ของลูกค้า', array('type'=>'chk')); //new
		$this->_setController('option_is_no_plmk_size_tag', 'ติดป้ายไซส์ ไม่เอา POLOMAKER', array('type'=>'chk')); //new
		$this->_setController('option_is_pakaging_tpb', 'พับแพ็คเสื้อใส่ถุงใส', array('type'=>'chk')); //new
		$this->_setController('option_is_no_packaging_sep_tpb', 'ไม่ต้องพับแพ็ค-แต่ขอถุงเสื้อแยกมา', array('type'=>'chk')); //new
		// -- option table join --

		$this->_arrControlLayout = array(
			array('code', '')
			,'แบบแพทเทิร์น' => array('standard_pattern_rowid', '')
			,'ชนิดผ้า' => array('fabric_rowid', '')
			,'แบบคอเสื้อ' => array('neck_type_rowid', 'neck_type_detail')
			,'แบบซก/ริบคอ' => array(
				array('m_collar_type_rowid', 'f_collar_type_rowid')
				,array('collar_detail')
			)
			,'สีเสื้อ' => array(
				array('main_color_rowid', 'line_color_rowid')
				,array('sub_color1_rowid', 'sub_color2_rowid', 'sub_color3_rowid')
				,array('option_hem_rowid', 'option_hem_color_rowid')
				,array('color_detail')
			)
			,'แบบแขนเสื้อ' => array(
				array('m_sleeves_type_rowid', 'f_sleeves_type_rowid')
				,array('sleeves_detail')
			)
			,'แบบชายเสื้อ' => array(
				array('flap_type_rowid', 'flap_type_detail')
				,array('return <span class="table-title frm-edit-row-title">ความยาวเสื้อพิเศษ</span>', 'option_is_mfl', 'option_male_fix_length')
				,array('', 'option_is_ffl', 'option_female_fix_length')
			)
			,'แบบผ่าข้าง' => array(
				array('flap_side_ptrn_rowid', 'flap_side_ptrn_detail')
				,array('', 'm_flap_side', 'f_flap_side', '')
			)
			,'แบบกระเป๋า' => array(
				array('pocket_type_rowid', 'pocket_type_detail')
				,array('', 'm_pocket', 'f_pocket', '')
			)
			,'แบบที่เสียบปากกา' => array(
				array('pen_pattern_rowid', 'pen_detail')
				,array('return <span class="table-title frm-edit-row-title">ที่เสียบปากกา</span>', 'm_pen', 'f_pen', '')
				,array('return <span class="table-title frm-edit-row-title">ตำแหน่งที่เสียบปากกา</span>', 'is_pen_pos_left', 'is_pen_pos_right', '')
			)
			,'ข้อมูลพิเศษอื่นๆ' => array(
				array('option_is_no_neck_tag', 'option_is_customer_size_tag', 'option_is_no_plmk_size_tag')
				,array('option_is_pakaging_tpb', 'option_is_no_packaging_sep_tpb', '')
			)
			,'รายละเอียดเพิ่มเติม' => array(
				array('remark1')
				,array('remark2')
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

		$pass['title'] = "แบบเสื้อยืดสำเร็จรูป";
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
}