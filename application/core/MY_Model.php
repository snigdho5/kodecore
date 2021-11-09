<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 */
class MY_Model extends CI_Model
{
	

	public $table='';
	public $table_joined='';
	public $primary_key='id';


	public function store($data,$batch=FALSE,$return_query=FALSE){
		if($return_query==FALSE){
			if($batch==FALSE){
				$this->db->insert($this->table,$data);
				return $this->db->insert_id();
			}else{
				return $this->db->insert_batch($this->table,$data);
			}	
		}else{
			$this->db->insert($this->table,$data);
			return $this->db->last_query();
			//return $this->db->set($data)->get_compiled_insert($this->table);
		}	
	}

	public function store_replace($data,$return_query=FALSE){
		if($return_query==FALSE){
			return $this->db->replace($this->table,$data);
		}else{
			$this->db->replace($this->table,$data);
			return $this->db->last_query();
		}
	}

	public function modify($data,$param,$batch=FALSE,$return_query=FALSE){
		if($return_query==FALSE){
			if($batch==FALSE){
				return $this->db->update($this->table,$data,$param);
			}else{
				return $this->db->update_batch($this->table,$data,$param);
			}
		}else{
			if($batch==FALSE){
				$this->db->update($this->table,$data,$param);
			}else{
				$this->db->update_batch($this->table,$data,$param);
			}
			return $this->db->last_query();
		}	
	}

	public function remove($param,$truncate=0,$return_query=FALSE){
		if($return_query==FALSE){
			if($truncate==0){
				return $this->db->delete($this->table,$param);
			}else if($truncate==1){
				return $this->db->empty_table($this->table);
			}else if($truncate==2){
				return $this->db->truncate($this->table);
			}
		}else{
			if($truncate==0){
				$this->db->delete($this->table,$param);
			}else if($truncate==1){
				$this->db->empty_table($this->table);
			}else if($truncate==2){
				$this->db->truncate($this->table);
			}
			return $this->db->last_query();
		}	
	}

	public function remove_where_in($param_field,$param_value,$return_query=FALSE){
		if($return_query==FALSE){
			return $this->run_raw('DELETE FROM '.$this->table.' WHERE '.$param_field.' IN('.$param_value.')');
		}else{
			return $this->run_raw('DELETE FROM '.$this->table.' WHERE '.$param_field.' IN('.$param_value.')',TRUE);
		}
	}

	public function get_joined($fields,$joined_fields,$param=null,$order_by=null,$order=null,$limit=null,$start=null,$result_type='object',$return_multi=FALSE,$return_query=FALSE){

		$this->db->select($fields);

		if(is_array($this->table_joined)){
			foreach ($this->table_joined as $key => $value) {
				$this->db->join($value,$joined_fields[$key]);
			}
		}else{
			$this->db->join($this->table_joined,$joined_fields);
		}

		if($return_query==FALSE){
			if($return_multi==FALSE){
				return $this->db->where($param)->get($this->table)->first_row();
			}else{
				if($param!=null){
					$this->db->where($param);
				}
				
				if($order!=null && $order_by!=null){
					$this->db->order_by($order_by,$order);
				}

				if($limit!=null && $start!=null){
					$this->db->limit($limit, $start);
				}

				if($result_type=='object'){
					return $this->db->get($this->table)->result();
				}else if($result_type=='array'){
					return $this->db->get($this->table)->result_array();
				}	
			}	
		}else{
			return $this->db->last_query();
		}	
	}

	public function get_single($param,$return_query=FALSE){
		if($return_query==FALSE){
			return $this->db->get_where($this->table,$param)->first_row();
		}else{
			$this->db->get_where($this->table,$param)->first_row();
			return $this->db->last_query();
		}
	}

	public function get_many($param=null,$order_by=null,$order=null,$return_query=FALSE){
		if($param==null || empty($param)){
			if($return_query==FALSE){
				if($order!=null && $order_by!=null){
					$this->db->order_by($order_by,$order);
				}
				return $this->db->get($this->table)->result();
			}else{
				if($order!=null && $order_by!=null){
					$this->db->order_by($order_by,$order);
				}
				$this->db->get($this->table)->result();
				return $this->db->last_query();
			}
			
		}else{
			if($return_query==FALSE){

				if(count($param)>1){

					foreach ($param as $key => $value) {
						$this->db->where($key,$value);
					}

					if($order!=null && $order_by!=null){
						$this->db->order_by($order_by,$order);
					}

					return $this->db->get($this->table)->result();
				}else{
					$this->db->where($param);
					if($order!=null && $order_by!=null){
						$this->db->order_by($order_by,$order);
					}

					return $this->db->get($this->table)->result();
				}
			}else{

				if(count($param)>1){

					foreach ($param as $key => $value) {
						$this->db->where($key,$value);
					}

					if($order!=null && $order_by!=null){
						$this->db->order_by($order_by,$order);
					}

					$this->db->get($this->table)->result();
				}else{

					$$this->db->where($param);
					if($order!=null && $order_by!=null){
						$this->db->order_by($order_by,$order);
					}

					return $this->db->get($this->table)->result();
				}

				return $this->db->last_query();
			}
		}
	}

	public function get($param=null,$search_str=null,$orlike=null,$order_by='id',$order='DESC',$limit=null,$offset=null,$return_query=FALSE){


		if(isset($this->primary_key)){
			$order_by=$this->primary_key;
		}

		if(($param==null || empty($param)) && ($search_str==null || empty($search_str))){
			if($return_query==FALSE){
				if($limit==null && $offset==null){
					return $this->db->order_by($order_by, $order)->get($this->table)->result();
				}else{
					return $this->db->order_by($order_by, $order)->get($this->table, $limit, $offset)->result();
				}	
			}else{
				if($limit==null && $offset==null){
					$this->db->order_by($order_by, $order)->get($this->table)->result();
				}else{
					$this->db->order_by($order_by, $order)->get($this->table, $limit, $offset)->result();
				}
				return $this->db->last_query();
			}
		}else if(($param!=null || !empty($param)) && ($search_str==null || empty($search_str))){
			if($return_query==FALSE){
				if($limit==null && $offset==null){
					return $this->db->order_by($order_by, $order)->get_where($this->table,$param)->result();
				}else{
					return $this->db->order_by($order_by, $order)->get_where($this->table,$param,$limit, $offset)->result();
				}
				
			}else{
				if($limit==null && $offset==null){
					$this->db->order_by($order_by, $order)->get_where($this->table,$param)->result();
				}else{
					$this->db->order_by($order_by, $order)->get_where($this->table,$param,$limit, $offset);
				}

				return $this->db->last_query();
			}
		}else if(($param!=null || !empty($param)) && ($search_str!=null || !empty($search_str))){
			if($return_query==FALSE){
				if($limit==null && $offset==null){
					$this->db->where($param);
					if($orlike==''){
						$this->db->like($search_str);
					}else if($orlike!=''){
						$this->db->like($search_str);
						$this->db->or_like($orlike);
					}
					$this->db->order_by($order_by, $order);
					return $this->db->get($this->table,$param)->result();
				}else{
					return $this->db->order_by($order_by, $order)->get_where($this->table,$param,$limit, $offset);
				}
				
			}else{
				if($limit==null && $offset==null){
					$this->db->where($param);
					if($orlike==''){
						$this->db->like($search_str);
					}else if($orlike!=''){
						$this->db->like($search_str);
						$this->db->or_like($orlike);
					}
					$this->db->order_by($order_by, $order);
					$this->db->get($this->table,$param)->result();
				}else{
					$this->db->order_by($order_by, $order)->get_where($this->table,$param,$limit, $offset);
				}

				return $this->db->last_query();
			}
		}
	}

	public function get_count($param=null,$search_str=null,$orlike=null,$order_by='id',$order='DESC',$limit=null,$offset=null,$return_query=FALSE){

		if(isset($this->primary_key)){
			$order_by=$this->primary_key;
		}

		if(($param==null || empty($param)) && ($search_str==null || empty($search_str))){
			if($return_query==FALSE){
				if($limit==null && $offset==null){
					$this->db->order_by($order_by, $order);
					$this->db->from($this->table);
					return $this->db->count_all_results();
				}else{
					$this->db->order_by($order_by, $order);
					$this->db->limit($limit, $offset);
					$this->db->from($this->table);
					return $this->db->count_all_results();
				}	
			}else{
				if($limit==null && $offset==null){
					$this->db->order_by($order_by, $order);
					$this->db->from($this->table);
					$this->db->count_all_results();
				}else{
					$this->db->order_by($order_by, $order);
					$this->db->limit($limit, $offset);
					$this->db->from($this->table);
					$this->db->count_all_results();
				}
				return $this->db->last_query();
			}
		}else if(($param!=null || !empty($param)) && ($search_str==null || empty($search_str))){
			if($return_query==FALSE){
				if($limit==null && $offset==null){
					$this->db->where($param);
					$this->db->order_by($order_by, $order);
					$this->db->from($this->table);
					return $this->db->count_all_results();
				}else{
					$this->db->where($param);
					$this->db->order_by($order_by, $order);
					$this->db->limit($limit, $offset);
					$this->db->from($this->table);
					return $this->db->count_all_results();
				}
			}else{
				if($limit==null && $offset==null){
					$this->db->where($param);
					$this->db->order_by($order_by, $order);
					$this->db->from($this->table);
					$this->db->count_all_results();
				}else{
					$this->db->where($param);
					$this->db->order_by($order_by, $order);
					$this->db->limit($limit, $offset);
					$this->db->from($this->table);
					$this->db->count_all_results();
				}

				return $this->db->last_query();
			}
		}else if(($param!=null || !empty($param)) && ($search_str!=null || !empty($search_str))){
			if($return_query==FALSE){
				if($limit==null && $offset==null){
					$this->db->where($param);
					if($orlike==''){
						$this->db->like($search_str);
					}else if($orlike!=''){
						$this->db->like($search_str);
						$this->db->or_like($orlike);
					}
					$this->db->order_by($order_by, $order);
					$this->db->from($this->table);
					return $this->db->count_all_results();
				}else{
					$this->db->where($param);
					$this->db->order_by($order_by, $order);
					$this->db->limit($limit, $offset);
					$this->db->from($this->table);
					return $this->db->count_all_results();
				}
			}else{
				if($limit==null && $offset==null){
					$this->db->where($param);
					if($orlike==''){
						$this->db->like($search_str);
					}else if($orlike!=''){
						$this->db->like($search_str);
						$this->db->or_like($orlike);
					}
					$this->db->order_by($order_by, $order);
					$this->db->limit($limit,$offset);
					$this->db->from($this->table);
					$this->db->count_all_results();
				}else{
					$this->db->where($param);
					$this->db->order_by($order_by, $order);
					$this->db->limit($limit, $offset);
					$this->db->from($this->table);
					$this->db->count_all_results();
				}

				return $this->db->last_query();
			}
		}
	}

	public function get_many_not_in($param,$param_val){
		return $this->db->where_not_in($param,$param_val)->get($this->table)->result();	
	}

	public function get_many_or_not_in($param,$param_val){
		return $this->db->or_where_not_in($param,$param_val)->get($this->table)->result();	
	}

	public function get_one($param,$return_query=FALSE){
		if($return_query==FALSE){
			return $this->db->get_where($this->table,$param)->first_row();
		}else{
			$this->db->get_where($this->table,$param)->first_row();
			return $this->db->last_query();
		}
		
	}

	public function get_distinct($return_query=FALSE){
		$this->db->distinct();
		if($return_query==TRUE){
			$this->db->get('table')->result();
			return $this->db->last_query();
		}else{
			return $this->db->get('table')->result(); 
		}
		
	}

	public function get_max($field,$as,$return_query=FALSE){
		$this->db->select_max($field, $as);
		if($return_query==TRUE){
			$this->db->get($this->table)->result();
			return $this->db->last_query();
		}else{
			return $this->db->get($this->table)->result();
		}	
	}

	public function get_min($field,$as,$return_query=FALSE){
		$this->db->select_min($field, $as);
		if($return_query==TRUE){
			$this->db->get($this->table)->result();
			return $this->db->last_query();
		}else{
			return $this->db->get($this->table)->result();
		}
	}

	public function get_avg($field,$as,$return_query=FALSE){
		$this->db->select_avg($field, $as);
		if($return_query==TRUE){
			$this->db->get($this->table)->result();
			return $this->db->last_query();
		}else{
			return $this->db->get($this->table)->result();
		}	
	}

	public function get_sum($field,$as,$param=null,$return_query=FALSE){
		$this->db->select_sum($field, $as);
		if($return_query==TRUE){
			if($param!=null){
				$this->db->where($param);
			}
			$this->db->get($this->table)->result();
			return $this->db->last_query();
		}else{
			if($param!=null){
				$this->db->where($param);
			}

			return $this->db->get($this->table)->result();
		}	
	}


	public function run_raw($query,$return_query=FALSE){
		if($return_query==TRUE){
			return $query;
		}else{
			$q=$this->db->query($query);
			return $q;
		}
	}
}
