<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logs extends BP_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('System_Model');
		
		$this->load->language('system');
		
		$this->load->library('form_validation');
	}

	public function index()
	{
		// set session for menues
		$this->session->set_userdata('MENU', MNU_SYSTEM);

		$data['site_title'] = lang('logs_title');
		
		$this->form_validation->set_rules('log_date', 'Log Date', 'required');
		$this->form_validation->set_rules('keyword', 'Keyword', '');
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
			$data['content'] = $this->get_log_details();
		}
		
		$data = get_library('datepicker', $data);

		_render_view('system/logs_view', $data);
	}
	
	function get_log_details() {
		
		$logs_content = '';
		
		$keyword 	= $this->input->post('keyword');
		
		$log_date 	= $this->input->post('log_date');
		
		$log_date 	= date('Y-m-d', strtotime($log_date));
		
		$basepath	= str_replace('system', '', BASEPATH . APPPATH);
		$f_name 	= 'log-' . $log_date .'.php';
		$ff 		= $basepath . 'logs/' .$f_name;

		if (file_exists($ff))
		{
			if( empty($keyword) ) {
				
				$ff = highlight_file($ff, TRUE);
				$logs_content =trim($ff);
			
			} else {
				$this->_search_content($keyword, $ff);
			}
			
			
			

		} else{
			// notification
			$logs_content = 'It&#39;s gone: <b>'. $f_name .'</b>';
		}
		
		return $logs_content;
	}
	
	function _search_content($keyword, $ff) {
		
		$logs_content = '';
		
		// get file in a string
		$content = explode("\n", file_get_contents($ff));
		
		// cosmetic
		//$ff = str_replace('ERROR', '<br />Error<br />', $ff);
		
		$str_rp = '[INFO] Received FLIGHT payment results from Onepay:';
		
		// show errors
		foreach ($content as $line) {
		
			// search keyword
			if ( !empty($keyword) && stripos($line, $keyword) === false) continue;
		
			// flight payment
			$pos = stripos($line, '[INFO] Received FLIGHT payment');
			if ($pos !== false) {
					
				$txt = explode(',', $line);
					
				$f_txt = explode('-->', str_replace($str_rp, '', $txt[0]));
					
				$log_time 		= trim(str_replace('ERROR -', '', $f_txt[0]));
					
				$txt_type 	= explode(':', $f_txt[1]);
					
				$payment_type	= $txt_type[1] == 'domestic' ? 'domestic' : $this->get_code($txt, 'type');
					
				$responseCode	= $this->get_code($txt);
					
				$responseDesc	= $this->getInternationalResponseData($responseCode);
					
				if($payment_type == 'domestic') $responseDesc = $this->getDomesticResponseData($responseCode);
					
				$logs_content .= '<p>';
					
				$logs_content .= '[ ' . $log_time . ' ]';
					
				$logs_content .= ' Order Ref: '. $this->get_code($txt, 'vpc_OrderInfo');
					
				$logs_content .= ', Type: '. $payment_type;
					
				$logs_content .= ', Amount: '. number_format($this->get_code($txt, 'invoice_amount')/100, 0, '.', '.');
					
				$logs_content .= ', Transaction No: '. $this->get_code($txt, 'vpc_TransactionNo');
					
				$logs_content .= ', ResponseCode: '. $responseCode .' - "' . $responseDesc . '"';
					
				$logs_content .= '</p>';
			}
		
		}
		
		return $logs_content;
	}
	
	function get_code($content, $code = 'vpc_TxnResponseCode') {
		
		$txt = '';
		
		foreach ($content as $line) {
			
			$params = explode(':', $line);
			
			if( trim($params[0]) == trim($code) ) {
				
				$txt = isset($params[1]) ? $params[1] : '';
			}
		}
		
		return $txt;
	} 
	
	function getInternationalResponseData($responseCode)
	{
	
		switch ($responseCode) {
			case "0" :
				$result = "Transaction Successful";
				break;
			case "?" :
				$result = "Transaction status is unknown";
				break;
			case "1" :
				$result = "Bank system reject";
				break;
			case "2" :
				$result = "Bank Declined Transaction";
				break;
			case "3" :
				$result = "No Reply from Bank";
				break;
			case "4" :
				$result = "Expired Card";
				break;
			case "5" :
				$result = "Insufficient funds";
				break;
			case "6" :
				$result = "Error Communicating with Bank";
				break;
			case "7" :
				$result = "Payment Server System Error";
				break;
			case "8" :
				$result = "Transaction Type Not Supported";
				break;
			case "9" :
				$result = "Bank declined transaction (Do not contact Bank)";
				break;
			case "A" :
				$result = "Transaction Aborted";
				break;
			case "C" :
				$result = "Transaction Cancelled";
				break;
			case "D" :
				$result = "Deferred transaction has been received and is awaiting processing";
				break;
			case "F" :
				$result = "3D Secure Authentication failed";
				break;
			case "I" :
				$result = "Card Security Code verification failed";
				break;
			case "L" :
				$result = "Shopping Transaction Locked (Please try the transaction again later)";
				break;
			case "N" :
				$result = "Cardholder is not enrolled in Authentication scheme";
				break;
			case "P" :
				$result = "Transaction has been received by the Payment Adaptor and is being processed";
				break;
			case "R" :
				$result = "Transaction was not processed - Reached limit of retry attempts allowed";
				break;
			case "S" :
				$result = "Duplicate SessionID (OrderInfo)";
				break;
			case "T" :
				$result = "Address Verification Failed";
				break;
			case "U" :
				$result = "Card Security Code Failed";
				break;
			case "V" :
				$result = "Address Verification and Card Security Code Failed";
				break;
			case "99" :
				$result = "User Cancel";
				break;
			default  :
				$result = "Unable to be determined";
		}
		return $result;
	}
	
	function getDomesticResponseData($responseCode)
	{
	
		switch ($responseCode) {
			case "0" :
				$result = "Giao dịch thành công";
				break;
			case "1" :
				$result = "Ngân hàng từ chối giao dịch";
				break;
			case "3" :
				$result = "Mã đơn vị không tồn tại";
				break;
			case "4" :
				$result = "Không đúng access code";
				break;
			case "5" :
				$result = "Số tiền không hợp lệ";
				break;
			case "6" :
				$result = "Mã tiền tệ không tồn tại";
				break;
			case "7" :
				$result = "Lỗi không xác định";
				break;
			case "8" :
				$result = "Số thẻ không đúng";
				break;
			case "9" :
				$result = "Tên chủ thẻ không đúng";
				break;
			case "10" :
				$result = "Thẻ hết hạn/Thẻ bị khóa";
				break;
			case "11" :
				$result = "Thẻ chưa đăng ký sử dụng dịch vụ";
				break;
			case "12" :
				$result = "Ngày phát hành/Hết hạn không đúng̣";
				break;
			case "13" :
				$result = "Vượt quá hạn mức thanh toán";
				break;
			case "21" :
				$result = "Số tiền không đủ để thanh toán";
				break;
			case "99" :
				$result = "Người sử dụng huỷ giao dịch";
				break;
			default  :
				$result = "Unable to be determined";
		}
		return $result;
	}
}
