<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_polo_pattern extends MY_Ctrl_crud {
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
				'standard_pattern'=>array('where'=>array('is_cancel'=>0,'is_polo'=>1),'order_by'=>'sort_index')
				,'fabric'=>array('where'=>array('is_cancel'=>0,'is_polo'=>1),'order_by'=>'sort_index')
				,'neck_type'=>array('where'=>array('is_cancel'=>0,'is_polo'=>1),'order_by'=>'sort_index')
				,'neck_hem'=>array('table_prefix'=>'m_','where'=>array('is_cancel'=>0),'order_by'=>'sort_index')
				,'m_shape'=>array('table_name'=>'m_base_shape','where'=>array('is_cancel'=>0,'is_polo'=>1,'is_male'=>1),'order_by'=>'sort_index')
				,'f_shape'=>array('table_name'=>'m_base_shape','where'=>array('is_cancel'=>0,'is_polo'=>1,'is_female'=>1),'order_by'=>'sort_index')
				,'main_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND polo_cols LIKE '%,main,%'",'order_by'=>'sort_index')
				,'line_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND polo_cols LIKE '%,sub,%'",'order_by'=>'sort_index')
				,'sub_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND polo_cols LIKE '%,line,%'",'order_by'=>'sort_index')
				,'option_hem'=>array('table_prefix'=>'m_','where'=>array('is_cancel'=>0),'order_by'=>'sort_index')
				,'option_hem_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND polo_cols LIKE '%,hem,%'",'order_by'=>'sort_index')
				,'clasper_type'=>array('where'=>array('is_cancel'=>0,'is_polo'=>1),'order_by'=>'sort_index')
				,'clasper_ptrn'=>array('where'=>array('is_cancel'=>0,'is_polo'=>1),'order_by'=>'sort_index')
				,'collar_type'=>array('where'=>array('is_cancel'=>0,'is_polo'=>1),'order_by'=>'sort_index')
				,'sleeves_type'=>array('where'=>array('is_cancel'=>0,'is_polo'=>1),'order_by'=>'sort_index')
				,'flap_type'=>array('where'=>array('is_cancel'=>0,'is_polo'=>1),'order_by'=>'sort_index')
				,'flap_side_ptrn'=>array('where'=>array('is_cancel'=>0,'is_polo'=>1),'order_by'=>'sort_index')
				,'pocket_type'=>array('where'=>array('is_cancel'=>0,'is_polo'=>1),'order_by'=>'sort_index')
				,'pen_pattern'=>array('where'=>array('is_cancel'=>0,'is_polo'=>1),'order_by'=>'sort_index')
				,'order_screen'=>array('where'=>array('is_cancel'=>0,'is_polo'=>1),'order_by'=>'sort_index')
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
		
		$this->_setController('standard_pattern_rowid', '');
		$this->_setController('fabric_rowid', '');
		$this->_setController('neck_type_rowid', '');
		$this->_setController('neck_type_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>140));
		$this->_setController('neck_hem_rowid', ''); //- new 
		$this->_setController('neck_hem_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>140)); //- new
		$this->_setController('m_shape_rowid', 'ทรงเสื้อชาย'); //- new 
		$this->_setController('f_shape_rowid', 'ทรงเสื้อหญิง'); //- new 
		$this->_setController('main_color_rowid', 'สีผ้าหลัก'); //- new 
		//$this->_setController('line_color_rowid', 'สีวิ่งเส้น'); //- new 
		$this->_setController('sub_color1_rowid', 'สีรอง1', array('sel_options'=>$this->_selOptions['sub_color'])); //- new 
		$this->_setController('sub_color2_rowid', 'สีรอง2', array('sel_options'=>$this->_selOptions['sub_color'])); //- new 
		$this->_setController('sub_color3_rowid', 'สีรอง3', array('sel_options'=>$this->_selOptions['sub_color'])); //- new 
		$this->_setController('color_detail', '', array('type'=>'txa', 'rows'=>1)); //- new
		$this->_setController('collar_type_rowid', 'รูปแบบปก');
		$this->_setController('collar_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>140));
		$this->_setController('collar_detail2', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>140));  //- new
		$this->_setController('m_clasper_type_rowid', 'ทรงสาบเสื้อชาย', array('sel_options'=>$this->_selOptions['clasper_type']));
		$this->_setController('f_clasper_type_rowid', 'ทรงสาบเสื้อหญิง', array('sel_options'=>$this->_selOptions['clasper_type']));
		$this->_setController('clasper_ptrn_rowid', 'รูปแบบสาบกระดุม');
		$this->_setController('clasper_detail', 'กระดุม/สีกระดุม(ระบุพิเศษ)', array('type'=>'txa', 'rows'=>1, 'maxlength'=>30));
		$this->_setController('clasper_detail2', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>140)); //- new
		$this->_setController('m_sleeves_type_rowid', 'แขนเสื้อชาย', array('sel_options'=>$this->_selOptions['sleeves_type']));
		$this->_setController('f_sleeves_type_rowid', 'แขนเสื้อหญิง', array('sel_options'=>$this->_selOptions['sleeves_type']));
		$this->_setController('sleeves_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>140));
		$this->_setController('flap_type_rowid', 'รูปแบบชายเสื้อ');
		$this->_setController('flap_type_detail', '', array('type'=>'txt', 'maxlength'=>30));
		$this->_setController('flap_side_ptrn_rowid', '');
		$this->_setController('flap_side_ptrn_detail', '', array('type'=>'txt', 'maxlength'=>30));
		$this->_setController('m_flap_side', 'เสื้อผู้ชาย', array('type'=>'chk'));
		$this->_setController('f_flap_side', 'เสื้อผุ้หญิง', array('type'=>'chk'));
		$this->_setController('pocket_type_rowid', '');
		$this->_setController('pocket_type_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>60));
		$this->_setController('m_pocket', 'กระเป๋าเสื้อผู้ชาย', array('type'=>'chk'));
		$this->_setController('f_pocket', 'กระเป๋าเสื้อผู้หญิง', array('type'=>'chk'));
		$this->_setController('pen_pattern_rowid', '');
		$this->_setController('pen_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>60));
		$this->_setController('m_pen', 'เสื้อผู้ชาย', array('type'=>'chk'));
		$this->_setController('f_pen', 'เสื้อผุ้หญิง', array('type'=>'chk'));
		$this->_setController('is_pen_pos_left', 'แขนซ้าย', array('type'=>'chk')); //- new
		$this->_setController('is_pen_pos_right', 'แขนขวา', array('type'=>'chk')); //- new
		$this->_setController('remark1', '', array('type'=>'txa','rows'=>2,'maxlength'=>140));
		$this->_setController('remark2', '', array('type'=>'txa','rows'=>2,'maxlength'=>140));

		// ++ option table join ++
		//$this->_setController('option_hem_rowid', 'เพิ่มกุ๊น', array('type'=>'rdo', 'sel_options'=>$this->_selOptions['option_hem']));
		//$this->_setController('option_hem_color_rowid', 'สีกุ๊น', array('type'=>'sel', 'sel_options'=>$this->_selOptions['option_hem_color']));
		$this->_setController('option_is_mfl', 'เสื้อผู้ชาย', array('type'=>'chk','class'=>'set-disabled')); //new
		$this->_setController('option_male_fix_length', '', array('type'=>'txt', 'maxlength'=>30)); //new
		$this->_setController('option_is_ffl', 'เสื้อผู้หญิง', array('type'=>'chk','class'=>'set-disabled')); //new
		$this->_setController('option_female_fix_length', '', array('type'=>'txt', 'maxlength'=>30)); //new
		$this->_setController('option_is_no_neck_tag', 'ไม่ติดป้ายคอใดๆทั้งสิ้น', array('type'=>'chk')); //new
		$this->_setController('option_is_customer_size_tag', 'ติดป้ายไซส์ของลูกค้า', array('type'=>'chk')); //new
		$this->_setController('option_is_no_plmk_size_tag', 'ติดป้ายไซส์ ไม่เอา POLOMAKER', array('type'=>'chk')); //new
		//$this->_setController('option_is_no_back_clasper', 'ไม่เอาสาบหลัง', array('type'=>'chk')); //new
		$this->_setController('option_is_pakaging_tpb', 'พับแพ็คเสื้อใส่ถุงใส', array('type'=>'chk')); //new
		$this->_setController('option_is_no_packaging_sep_tpb', 'ไม่ต้องพับแพ็ค-แต่ขอถุงเสื้อแยกมา', array('type'=>'chk')); //new
		// -- option table join --


		$this->_arrControlLayout = array(
			array('code', '')
			,'แบบแพทเทิร์น' => array('standard_pattern_rowid', '')
			,'ชนิดผ้า' => array('fabric_rowid', '')
			,'แบบคอเสื้อ' => array('neck_type_rowid', 'neck_type_detail')
			,'แบบกุ๊นคอเสื้อ' => array('neck_hem_rowid', 'neck_hem_detail')
			,'แบบทรงเสื้อ' => array('m_shape_rowid', 'f_shape_rowid')
			,'สีเสื้อ' => array(
				array('main_color_rowid', 'line_color_rowid')
				,array('sub_color1_rowid', 'sub_color2_rowid', 'sub_color3_rowid')
				,array('option_hem_rowid', 'option_hem_color_rowid')
				,array('color_detail')
			)
			,'แบบปก' => array(
				array('collar_type_rowid', '')
				,array('collar_detail')
				,array('collar_detail2')
			)
			,'แบบสาบกระดุม' => array(
				array('m_clasper_type_rowid', 'f_clasper_type_rowid')
				,array('clasper_ptrn_rowid', 'clasper_detail')
				,array('clasper_detail2')
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
				,array('', 'option_is_pakaging_tpb', 'option_is_no_packaging_sep_tpb') // 'option_is_no_back_clasper'
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

		$pass['title'] = "แบบเสื้อโปโลสำเร็จรูป";
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
}