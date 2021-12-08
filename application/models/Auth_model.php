<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 */
class Auth_model extends MY_Model
{


	function __construct()
	{
		$this->table = 'users';
		$this->primary_key = 'user_id';
	}

	//users
	public function addUser($data)
	{
		$this->table = 'users';
		return $this->store($data);
	}
	public function getUserData($param = null, $many = FALSE)
	{
		$this->table = 'users';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} elseif ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by = 'user_id', $order = 'DESC', FALSE);
		} else {
			return $this->get_many();
		}
	}


	public function updateUser($data, $param)
	{
		$this->table = 'users';
		return $this->modify($data, $param);
	}
	public function delUser($param)
	{
		$this->table = 'users';
		return $this->remove($param);
	}

	//customers
	public function addCustomer($data)
	{
		$this->table = 'customers';
		return $this->store($data);
	}

	public function getCustomerData($param = null, $many = FALSE, $order_by = 'customer_id', $order = 'DESC')
	{
		$this->table = 'customers';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}


	public function checkAppUser($email = null, $phone = null, $many = FALSE, $order = 'DESC', $order_by = 'customer_id')
	{

		$this->db->select('customer_id');

		if ($email != null && $phone != null) {
			$this->db->where('email', $email);
			$this->db->or_where('phone', $phone);
		}

		$this->db->order_by($order_by, $order);

		$query = $this->db->get('customers');
		//echo $this->db->last_query();die;

		if ($many != TRUE) {
			return $query->row_array();
		} else {
			return $query->result_array();
		}
	}

	public function updateCustomer($data, $param)
	{
		$this->table = 'customers';
		return $this->modify($data, $param);
	}

	public function delCustomer($param)
	{
		$this->table = 'customers';
		return $this->remove($param);
	}

	//it projects
	public function addProject($data)
	{
		$this->table = 'it_projects';
		return $this->store($data);
	}
	public function getProjectData($param = null, $many = FALSE, $order_by = 'proj_id', $order = 'DESC')
	{
		$this->table = 'it_projects';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}
	public function updateProject($data, $param)
	{
		$this->table = 'it_projects';
		return $this->modify($data, $param);
	}
	public function delProject($param)
	{
		$this->table = 'it_projects';
		return $this->remove($param);
	}

	//Customer Project
	public function addCustomerProject($data)
	{
		$this->table = 'customers_it_projects';
		return $this->store($data);
	}
	public function getCustomerProjectData($param = null, $many = FALSE, $order_by = 'id', $order = 'DESC')
	{
		$this->table = 'customers_it_projects';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}

	public function getCustomerProjects($param = null, $many = FALSE, $order = 'DESC', $order_by = 'customers_it_projects.id')
	{

		$this->db->select('customers_it_projects.*, customers.first_name, customers.last_name, customers.email, customers.phone, it_projects.proj_title, it_projects.proj_amount AS proj_amount, it_projects.proj_duration AS proj_duration');
		$this->db->join('customers', 'customers.customer_id = customers_it_projects.customer_id', 'left');
		$this->db->join('it_projects', 'it_projects.proj_id = customers_it_projects.proj_id', 'left');

		if ($param != null) {
			$this->db->where($param);
		}

		$this->db->order_by($order_by, $order);

		$query = $this->db->get('customers_it_projects');
		// echo $this->db->last_query();die;

		if ($many != TRUE) {
			return $query->row();
		} else {
			return $query->result();
		}
	}
	public function updateCustomerProject($data, $param)
	{
		$this->table = 'customers_it_projects';
		return $this->modify($data, $param);
	}
	public function delCustomerProject($param)
	{
		$this->table = 'customers_it_projects';
		return $this->remove($param);
	}

	//investment plans
	public function addInvPlan($data)
	{
		$this->table = 'investment_plans';
		return $this->store($data);
	}
	public function getInvPlansData($param = null, $many = FALSE, $order_by = 'plan_id', $order = 'DESC')
	{
		$this->table = 'investment_plans';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}


	public function updateInvPlan($data, $param)
	{
		$this->table = 'investment_plans';
		return $this->modify($data, $param);
	}
	public function delInvPlan($param)
	{
		$this->table = 'investment_plans';
		return $this->remove($param);
	}

	//Customer Invesment Plan
	public function addCustomerInvesmentPlan($data)
	{
		$this->table = 'customers_investment_plans';
		return $this->store($data);
	}
	public function getCustomerInvesmentPlanData($param = null, $many = FALSE, $order_by = 'id', $order = 'DESC')
	{
		$this->table = 'customers_investment_plans';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}

	public function getCustomerInvesmentPlans($param = null, $many = FALSE, $order = 'DESC', $order_by = 'customers_investment_plans.customer_id')
	{

		$this->db->select('customers_investment_plans.*, customers.first_name, customers.last_name, customers.email, customers.phone, investment_plans.plan_name, investment_plans.duration');
		$this->db->join('customers', 'customers.customer_id = customers_investment_plans.customer_id', 'left');
		$this->db->join('investment_plans', 'investment_plans.plan_id = customers_investment_plans.plan_id', 'left');

		if ($param != null) {
			$this->db->where($param);
		}

		$this->db->order_by($order_by, $order);

		$query = $this->db->get('customers_investment_plans');
		// echo $this->db->last_query();die;

		if ($many != TRUE) {
			return $query->row();
		} else {
			return $query->result();
		}
	}

	public function updateCustomerInvesmentPlan($data, $param)
	{
		$this->table = 'customers_investment_plans';
		return $this->modify($data, $param);
	}
	public function delCustomerInvesmentPlan($param)
	{
		$this->table = 'customers_investment_plans';
		return $this->remove($param);
	}

	//cryptocurrency
	public function addCrypto($data)
	{
		$this->table = 'cryptocurrency';
		return $this->store($data);
	}
	public function getCryptoData($param = null, $many = FALSE, $order_by = 'crypto_id', $order = 'DESC')
	{
		$this->table = 'cryptocurrency';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}


	public function updateCrypto($data, $param)
	{
		$this->table = 'cryptocurrency';
		return $this->modify($data, $param);
	}
	public function delCrypto($param)
	{
		$this->table = 'cryptocurrency';
		return $this->remove($param);
	}


	//cryptocurrency buy
	public function addCryptoBuy($data)
	{
		$this->table = 'cryptocurrency_bought';
		return $this->store($data);
	}

	public function getCryptoBuyData($param = null, $many = FALSE, $order_by = 'crypto_buy_id', $order = 'DESC')
	{
		$this->table = 'cryptocurrency_bought';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}

	public function getCryptoBuyFinalData($param = null, $many = FALSE, $order = 'DESC', $order_by = 'cryptocurrency_bought.crypto_buy_id')
	{

		$this->db->select('
			cryptocurrency_bought.*,
		 	SUM(received_amount) AS total_received_amount,
		  	SUM(quantity) AS total_quantity
		  	');

		if ($param != null) {
			$this->db->where($param);
		}

		$this->db->order_by($order_by, $order);

		$query = $this->db->get('cryptocurrency_bought');
		// echo $this->db->last_query();die;

		if ($many != TRUE) {
			return $query->row();
		} else {
			return $query->result();
		}
	}

	public function getCryptoBuyUserData($param = null, $many = FALSE, $order = 'DESC', $order_by = 'cryptocurrency_bought.crypto_buy_id')
	{

		$this->db->select('
			cryptocurrency_bought.*,
			customers.first_name,
			customers.last_name,
			customers.email,
			customers.phone,
		  	');

		$this->db->join('customers', 'customers.customer_id = cryptocurrency_bought.customer_id', 'left');

		if ($param != null) {
			$this->db->where($param);
		}

		$this->db->order_by($order_by, $order);

		$query = $this->db->get('cryptocurrency_bought');
		// echo $this->db->last_query();die;

		if ($many != TRUE) {
			return $query->row();
		} else {
			return $query->result();
		}
	}


	public function updateCryptoBuy($data, $param)
	{
		$this->table = 'cryptocurrency_bought';
		return $this->modify($data, $param);
	}

	public function delCryptoBuy($param)
	{
		$this->table = 'cryptocurrency_bought';
		return $this->remove($param);
	}

	//redeem request
	public function addWalletRedeem($data)
	{
		$this->table = 'customer_wallet_redeem';
		return $this->store($data);
	}

	public function getWalletRedeemData($param = null, $many = FALSE, $order_by = 'redeem_id', $order = 'DESC')
	{
		$this->table = 'customer_wallet_redeem';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}

	public function getWalletRedeemUserData($param = null, $many = FALSE, $order = 'DESC', $order_by = 'customer_wallet_redeem.redeem_id')
	{

		$this->db->select('
		customer_wallet_redeem.*,
			customers.first_name,
			customers.last_name,
			customers.email,
			customers.phone,
		  	');

		$this->db->join('customers', 'customers.customer_id = customer_wallet_redeem.customer_id', 'left');

		if ($param != null) {
			$this->db->where($param);
		}

		$this->db->order_by($order_by, $order);

		$query = $this->db->get('customer_wallet_redeem');
		// echo $this->db->last_query();die;

		if ($many != TRUE) {
			return $query->row();
		} else {
			return $query->result();
		}
	}


	public function updateWalletRedeem($data, $param)
	{
		$this->table = 'customer_wallet_redeem';
		return $this->modify($data, $param);
	}

	public function delWalletRedeem($param)
	{
		$this->table = 'customer_wallet_redeem';
		return $this->remove($param);
	}

	//cryptocurrency sell
	public function addCryptoSell($data)
	{
		$this->table = 'cryptocurrency_sold';
		return $this->store($data);
	}

	public function getCryptoSellData($param = null, $many = FALSE, $order_by = 'crypto_sell_id', $order = 'DESC')
	{
		$this->table = 'cryptocurrency_sold';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}

	public function getCryptoSellFinalData($param = null, $many = FALSE, $order = 'DESC', $order_by = 'cryptocurrency_sold.crypto_sell_id')
	{

		$this->db->select('
		cryptocurrency_sold.*,
		 	SUM(received_amount) AS total_received_amount,
		  	SUM(quantity) AS total_quantity
		  	');

		if ($param != null) {
			$this->db->where($param);
		}

		$this->db->order_by($order_by, $order);

		$query = $this->db->get('cryptocurrency_sold');
		// echo $this->db->last_query();die;

		if ($many != TRUE) {
			return $query->row();
		} else {
			return $query->result();
		}
	}

	public function getCryptoSellUserData($param = null, $many = FALSE, $order = 'DESC', $order_by = 'cryptocurrency_sold.crypto_sell_id')
	{

		$this->db->select('
		cryptocurrency_sold.*,
			customers.first_name,
			customers.last_name,
			customers.email,
			customers.phone,
		  	');

		$this->db->join('customers', 'customers.customer_id = cryptocurrency_sold.customer_id', 'left');

		if ($param != null) {
			$this->db->where($param);
		}

		$this->db->order_by($order_by, $order);

		$query = $this->db->get('cryptocurrency_sold');
		// echo $this->db->last_query();die;

		if ($many != TRUE) {
			return $query->row();
		} else {
			return $query->result();
		}
	}


	public function updateCryptoSell($data, $param)
	{
		$this->table = 'cryptocurrency_sold';
		return $this->modify($data, $param);
	}

	public function delCryptoSell($param)
	{
		$this->table = 'cryptocurrency_sold';
		return $this->remove($param);
	}

	//app_info_data
	public function getAppDetailsData($param = null, $many = FALSE, $order_by = 'id', $order = 'DESC')
	{
		$this->table = 'app_info_data';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}

	
	public function updateAppDetails($data, $param)
	{
		$this->table = 'app_info_data';
		return $this->modify($data, $param);
	}

	//notification logs
	public function addNotificationLog($data)
	{
		$this->table = 'notification_logs';
		return $this->store($data);
	}

	public function getNotificationLogData($param = null, $many = FALSE, $order_by = 'nlog_id', $order = 'DESC')
	{
		$this->table = 'notification_logs';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}
	
	public function updateNotificationLog($data, $param)
	{
		$this->table = 'notification_logs';
		return $this->modify($data, $param);
	}

	//withdrawal request
	public function addWalletWithdrawal($data)
	{
		$this->table = 'customer_wallet_withdrawal';
		return $this->store($data);
	}

	public function getWalletWithdrawalData($param = null, $many = FALSE, $order_by = 'wdl_id', $order = 'DESC')
	{
		$this->table = 'customer_wallet_withdrawal';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}

	public function getWalletWithdrawalUserData($param = null, $many = FALSE, $order = 'DESC', $order_by = 'customer_wallet_withdrawal.wdl_id')
	{

		$this->db->select('
		customer_wallet_withdrawal.*,
			customers.first_name,
			customers.last_name,
			customers.email,
			customers.phone,
			customers.wallet_amount,
			customers.bank_name,
			customers.branch_name,
			customers.ac_no,
			customers.ifsc,
			customers.ac_name
		  	');

		$this->db->join('customers', 'customers.customer_id = customer_wallet_withdrawal.customer_id', 'left');

		if ($param != null) {
			$this->db->where($param);
		}

		$this->db->order_by($order_by, $order);

		$query = $this->db->get('customer_wallet_withdrawal');
		// echo $this->db->last_query();die;

		if ($many != TRUE) {
			return $query->row();
		} else {
			return $query->result();
		}
	}


	public function updateWalletWithdrawal($data, $param)
	{
		$this->table = 'customer_wallet_withdrawal';
		return $this->modify($data, $param);
	}

	public function delWalletWithdrawal($param)
	{
		$this->table = 'customer_wallet_withdrawal';
		return $this->remove($param);
	}


	//notification logs
	public function addPaymentLog($data)
	{
		$this->table = 'payment_logs';
		return $this->store($data);
	}

	public function getPaymentLogData($param = null, $many = FALSE, $order_by = 'nlog_id', $order = 'DESC')
	{
		$this->table = 'payment_logs';
		if ($param != null && $many == FALSE) {
			return $this->get_one($param);
		} else if ($param != null && $many == TRUE) {
			return $this->get_many($param, $order_by, $order, FALSE);
		} else {
			return $this->get_many(null, $order_by, $order, FALSE);
		}
	}
	
		//it proj payout
		public function addPayout($data)
		{
			$this->table = 'it_projects_payout';
			return $this->store($data);
		}

		

	public function updatePayout($data, $param)
	{
		$this->table = 'it_projects_payout';
		return $this->modify($data, $param);
	}

		public function getITProjectPayoutData($param = null, $many = FALSE, $order_by = 'payout_id', $order = 'DESC')
		{
			$this->table = 'it_projects_payout';
			if ($param != null && $many == FALSE) {
				return $this->get_one($param);
			} else if ($param != null && $many == TRUE) {
				return $this->get_many($param, $order_by, $order, FALSE);
			} else {
				return $this->get_many(null, $order_by, $order, FALSE);
			}
		}

		public function getITProjectPayoutUserData($param = null, $many = FALSE, $order = 'DESC', $order_by = 'it_projects_payout.payout_id')
		{
	
			$this->db->select('
			it_projects_payout.*,
				customers.first_name,
				customers.last_name,
				customers.email,
				customers.phone,
				customers.wallet_amount,
				customers.bank_name,
				customers.branch_name,
				customers.ac_no,
				customers.ifsc,
				customers.ac_name,
				it_projects.proj_title
				  ');
	
			$this->db->join('customers', 'customers.customer_id = it_projects_payout.customer_id', 'left');
			$this->db->join('it_projects', 'it_projects.proj_id = it_projects_payout.proj_id', 'left');
	
			if ($param != null) {
				$this->db->where($param);
			}
	
			$this->db->order_by($order_by, $order);
	
			$query = $this->db->get('it_projects_payout');
			// echo $this->db->last_query();die;
	
			if ($many != TRUE) {
				return $query->row();
			} else {
				return $query->result();
			}
		}

		public function getITProjectPayoutMonthData($customer_id = null, $proj_id = null, $many = FALSE, $order = 'DESC', $order_by = 'payout_id')
		{
	
			$this->db->select('
			it_projects_payout.*
				  ');
	
			if ($customer_id != null) {
				$st = 'customer_id = ' . $customer_id . ' AND proj_id = ' . $proj_id . ' AND month(added_dtime) = month(UTC_TIMESTAMP())';
				$this->db->where($st, null, false);
			}
	
			$this->db->order_by($order_by, $order);
	
			$query = $this->db->get('it_projects_payout');
			// echo $this->db->last_query();die;
	
			if ($many != TRUE) {
				return $query->row();
			} else {
				return $query->result();
			}
		}

		//inv plans payout
		public function addPayoutInvPlan($data)
		{
			$this->table = 'inv_plan_payout';
			return $this->store($data);
		}
		public function getInvPlanPayoutData($param = null, $many = FALSE, $order_by = 'payout_id', $order = 'DESC')
		{
			$this->table = 'inv_plan_payout';
			if ($param != null && $many == FALSE) {
				return $this->get_one($param);
			} else if ($param != null && $many == TRUE) {
				return $this->get_many($param, $order_by, $order, FALSE);
			} else {
				return $this->get_many(null, $order_by, $order, FALSE);
			}
		}

		public function getInvPlanPayoutUserData($param = null, $many = FALSE, $order = 'DESC', $order_by = 'inv_plan_payout.payout_id')
		{
	
			$this->db->select('
			inv_plan_payout.*,
				customers.first_name,
				customers.last_name,
				customers.email,
				customers.phone,
				customers.wallet_amount,
				customers.bank_name,
				customers.branch_name,
				customers.ac_no,
				customers.ifsc,
				customers.ac_name,
				investment_plans.plan_name
				  ');
	
			$this->db->join('customers', 'customers.customer_id = inv_plan_payout.customer_id', 'left');
			$this->db->join('investment_plans', 'investment_plans.plan_id = inv_plan_payout.plan_id', 'left');
	
			if ($param != null) {
				$this->db->where($param);
			}
	
			$this->db->order_by($order_by, $order);
	
			$query = $this->db->get('inv_plan_payout');
			// echo $this->db->last_query();die;
	
			if ($many != TRUE) {
				return $query->row();
			} else {
				return $query->result();
			}
		}

		public function getInvPlanPayoutMonthData($customer_id = null, $proj_id = null, $many = FALSE, $order = 'DESC', $order_by = 'payout_id')
		{
	
			$this->db->select('
			inv_plan_payout.*
				  ');
	
			if ($customer_id != null) {
				$st = 'customer_id = ' . $customer_id . ' AND plan_id = ' . $proj_id . ' AND month(added_dtime) = month(UTC_TIMESTAMP())';
				$this->db->where($st, null, false);
			}
	
			$this->db->order_by($order_by, $order);
	
			$query = $this->db->get('inv_plan_payout');
			// echo $this->db->last_query();die;
	
			if ($many != TRUE) {
				return $query->row();
			} else {
				return $query->result();
			}
		}
}
