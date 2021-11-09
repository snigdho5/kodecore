<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Main extends CI_Controller {

	public function __construct() {
		parent:: __construct();
	}

	public function index()
	{
		//
	}

	//cont us
	public function onGetContactUs()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1)
		{

			$contdata = $this->mm->getContData($p=null,$many=TRUE);
			if($contdata){
				foreach ($contdata as $key => $value) {
					$this->data['cont_data'][] = array(
						'cont_id'  => $value->cont_id,
						'fullname'  => $value->name,
						'phone'  => $value->phone,
						'message'  => $value->message,
						'email'  => $value->email,
						'added_source'  => $value->added_source,
						'iplocation'  => $value->created_ip,
						'dtime'  => $value->dtime
					);
				}
			}
			else{
				$this->data['cont_data'] = '';
			}
			$this->load->view('main/vw_contacts', $this->data, false);
		

		}
		else{
			redirect(base_url());
		}
			
		
	}


	public function onDeleteContUs()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in')==1 && $this->session->userdata('usergroup')==1)
		{
		   if($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD')=='POST'){

			$cont_id = xss_clean($this->input->post('contid'));
			$contdata = $this->mm->getContData(array('cont_id'  => $cont_id),$many=FALSE);

			if($contdata){
				//del
				$delcont = $this->mm->delCont(array('cont_id' => $cont_id));

				if($delcont){
					$return['deleted'] = 'success';
				}
				else{
					$return['deleted'] = 'failure';
				}
					
			}
			else{
				$return['deleted'] = 'not_exists';
			}

			header('Content-Type: application/json');
			echo json_encode($return);	

			}else{
				redirect(base_url());
			}
		}
 	}
	
	

}
