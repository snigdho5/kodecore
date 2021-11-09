<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 */
class MY_Controller extends CI_Controller
{

	public $data=array();

	public $styles=array();

	public $scripts=array();

	public $permission = array();

	public $demo='';
	
	function __construct()
	{
		parent::__construct();
		

		date_default_timezone_set("Asia/Kolkata");


		$this->styles=array(
			"https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700",
			assets_url()."plugins/bootstrap/4.1.3/css/bootstrap.min.css",
			assets_url()."plugins/jquery-ui/jquery-ui.min.css",
			"https://use.fontawesome.com/releases/v5.5.0/css/all.css",
			assets_url()."plugins/animate/animate.min.css",
		 //	assets_url()."css/transparent/style.min.css",
		 //	assets_url()."css/transparent/style-responsive.min.css",
		//	assets_url()."css/transparent/theme/default.css",
			assets_url()."css/default/style.min.css",
			assets_url()."css/default/style-responsive.min.css",
			assets_url()."css/default/theme/default.css",
		
	
	
			assets_url()."js/jfiller/css/jquery.filer.css",
			assets_url()."plugins/select2/dist/css/select2.min.css",
			"//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css"
		);

		$this->scripts=array(
			assets_url()."js/jquery.min.js",
			assets_url()."plugins/jquery-ui/jquery-ui.min.js",
			assets_url()."plugins/bootstrap/4.1.3/js/bootstrap.bundle.min.js",
			"//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js",
			assets_url()."js/jquery.validate.min.js",
			assets_url().'js/additional-methods.min.js',
			assets_url().'js/additional-methods.js',
			assets_url()."plugins/pace/pace.min.js",
			assets_url()."plugins/slimscroll/jquery.slimscroll.min.js",
			assets_url()."plugins/js-cookie/js.cookie.js",
		//	assets_url()."js/theme/transparent.min.js",
			assets_url()."js/apps.min.js",
			assets_url()."js/timer/jquery.countdown.min.js",
			assets_url()."js/jfiller/js/jquery.filer.js",
			assets_url()."plugins/select2/dist/js/select2.min.js"
		);
		
		
	

		//Get System Settings	

		$this->data['system_name']=$this->sm->getSetting(array('settings_key'=>'config_system_name'))->settings_value;
		$this->data['check_in_time']=$this->sm->getSetting(array('settings_key'=>'config_system_check_in_time'))->settings_value;
		$this->data['check_in_grace_time']=$this->sm->getSetting(array('settings_key'=>'config_system_check_in_grace_time'))->settings_value;
		$this->data['check_out_time']=$this->sm->getSetting(array('settings_key'=>'config_system_check_out_time'))->settings_value;

		//Get System Settings

		if(session_userdata('isAdminLoggedin')==TRUE && session_userdata('admin_id') && session_userdata('user_group')){

			$user_id 	=	session_userdata('admin_id');

			//echo $user_id;die;

			$this->data['userdata']['groups']=$this->um->getUserByGroup(session_userdata('user_group'));

			if(!empty($this->data['userdata']['groups'])){
				//print_obj($this->data['userdata']['groups']);die;

				// foreach ($this->data['userdata']['groups'] as $key => $value) {
				// 	print_obj(unserialize($value->group_permissions));
				// }

				// die;

				$this->permission=unserialize($this->data['userdata']['groups'][0]->group_permissions);

				$cad_data 	=	$this->cm->getCandidateData(array('candidate_user_id'=>$user_id));

				$this->data['userdata']['cdata']=$cad_data;

				//print_obj($cad_data);die;

				$this->data['time_spent']=$this->um->getUserLogTotal(array('user_id'=>$user_id,'DATE(created_at)'=>date('Y-m-d')));

				$this->data['special_menues']=$this->sm->getMenues(array('menu_type'=>1,'permitted_groups'=>session_userdata('user_group')));

				$this->data['banks']=$this->sm->getBanks();

				
				$other_menues=$this->sm->getMenues(array('menu_type'=>0,'parent_id'=>0,'permitted_groups'=>session_userdata('user_group')));

				//echo $other_menues;

				$this->data['menues'] = array();

				foreach ($other_menues as $parent) {
					// Level 2
					$children_data = array();

					$children = $this->sm->getMenues(array('menu_type'=>0,'parent_id'=>$parent->menu_id,'permitted_groups'=>session_userdata('user_group')));


					foreach ($children as $child) {

						$children_data[] = array(
							'menu_name'  => $child->menu_name,
							'icon'	   => $child->icon,
							'href'  => $child->menu_link
						);
					}

					// Level 1
					$this->data['menues'][] = array(
						'name'     => $parent->menu_name,
						'icon'	   => $parent->icon,
						'children' => $children_data,
						'href'     => $parent->menu_link
					);
				}

				//echo session_userdata('user_group');
				//print_obj($this->data['menues']);die;

				if(!empty($cad_data)){
					$this->data['states']=$this->sm->getStates();

					if($cad_data->candidate_photo_id>0){
		            	$media=$this->sm->getTempFile(array('storage_id'=>$cad_data->candidate_photo_id));

		            	if(!empty($media) && $media->media_disk_path_relative!=''){
		            		$this->data['userdata']['candidate_image']=$media->media_disk_path_relative;
		            	}else{
		                	$this->data['userdata']['candidate_image']=base_url().'uploads/temps/no_image.png';
		                }
		            }else{
		            	$this->data['userdata']['candidate_image']=base_url().'uploads/temps/no_image.png';
		            }



					// $this->data['userdata']['cdata']=$cad_data;
					$this->data['userdata']['medias']=$this->sm->getTempFiles(array('media_type_data_id'=>$cad_data->candidate_id));

					//$this->data['userdata']['medias']['verification_type']=$this->sm->getVerifiedTempFiles(array('media_type_data_id'=>$cad_data->candidate_id));

					//print_obj($this->data['userdata']['medias']);die;
				}else{
					$this->data['userdata']['candidate_image']=base_url().'uploads/temps/user-13.jpg';
				}
			}else{
				$this->session->sess_destroy();
				redirect(base_url());
			}				
		}	
	}	




	public function hasPermission($key, $value) {
		if (isset($this->permission[$key])) {
			return in_array($value, $this->permission[$key]);
		} else {
			return false;
		}
	}
}
