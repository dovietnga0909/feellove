<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->language('flight');
		$this->load->library('dompdf_gen');
		$this->load->model('Booking_Model');
		$this->load->helper(array('form','cookie','flight','hotel', 'file', 'payment'));
		$this->load->config('flight_meta');
	}

	public function index()
	{	
		if ( isset($_GET ["type"]) && !empty($_GET ["type"]) ) {
			
			// get bank and title config
			$data = array();
			$data['config_title'] = $this->config->item('c_titles');
			//$data['bank_fee'] = $this->config->item('bank_fee');

			// Process domestic payment
			if ($_GET ["type"] == 'domestic') {
				
				$response = getDomesticResponseData();
				
				$this->process_response($response, PAYMENT_METHOD_DOMESTIC_CARD);

			// Process international payment
			} elseif ($_GET ["type"] == 'international') {
				
				$card_type = 'international';
			
				$response = getInternationalResponseData();
			
				$this->process_response($response, PAYMENT_METHOD_CREDIT_CARD);
				
			}
			
			
		}
		
		// ----- Otherwise case go to home page -----
		redirect(site_url());
	}
	
	function process_response($response, $payment_method = PAYMENT_METHOD_DOMESTIC_CARD) {
		$txnResponseCode 	= $response['txnResponseCode'];
		$hashValidated 		= $response['hashValidated'];
		$orderInfo 			= $response['orderInfo'];
		$amount 			= $response['amount'];
		
		$data['response'] = $response;
		
		// get invoice details
		$invoice = $this->Booking_Model->get_invoice_info($orderInfo);
		
		/*
		 * 11.05.2014, khuyenpv: Add bank fee for invoice amount
		 */
		$invoice['amount'] = apply_bank_fee_online_payment($invoice['amount'], $invoice['type'], $payment_method);
		
		$invoice_reference = $invoice['invoice_reference'];
		$data['invoice'] = $invoice;
		
		// check amount
		$onepay_return 	= (int)$amount;
		$invoice_amount = round($invoice['amount'] * 100);
		$balance_due = $onepay_return - $invoice_amount;
		
		// log any return payment from Onepay
		log_payment('', $hashValidated, $txnResponseCode, $onepay_return, $invoice_amount, $invoice['type'], $payment_method);
		
		// --- Success
		if($hashValidated=="CORRECT" && $txnResponseCode=="0"
				&& $balance_due == 0){
		
			// --- update payment status
			$this->Booking_Model->update_invoice_status(INVOICE_SUCCESSFUL, $invoice_reference, $payment_method, $txnResponseCode);
		
			$for = '';
		
			if($invoice['type'] == FLIGHT) {
				$for = array('type'=>'flight');
		
				$this->process_flight_confirm($data);
			} elseif($invoice['type'] == HOTEL) {
				$for = array('type'=>'hotel');
		
				$this->process_hotel_confirm($data);
			}
				
		
			// --- show thank you
			redirect(get_url(CONFIRM_PAGE, $for));
		}
		// --- Pending
		elseif ($hashValidated=="INVALID HASH" && $txnResponseCode=="0"){
				
			// --- update payment status
			$this->Booking_Model->update_invoice_status(INVOICE_PENDING, $invoice_reference, $payment_method, $txnResponseCode);
				
			// --- show pending message
			redirect('thanh-toan/dang-xu-ly.html');
		}
		// --- Fail
		elseif ($txnResponseCode!="0") {
		
			if($txnResponseCode == "99"){ // user click 'cancel payment and return the website'
				// do nothing: not update invoice status (keep the previous invoice status)
			} else {
				// --- update payment status
				$this->Booking_Model->update_invoice_status(INVOICE_FAILED, $invoice_reference, $payment_method, $txnResponseCode);
			}
				
			// --- show unsuccessful page (allow customers to repay or contact)
			redirect('thanh-toan/that-bai.html?invoice_ref=' . $invoice_reference);
		}
		// --- Unknow
		else {
			log_payment('unknow', $hashValidated, $txnResponseCode, $onepay_return, $invoice_amount, $invoice['type'], $payment_method);
		
			// --- update payment status
			$this->Booking_Model->update_invoice_status(INVOICE_UNKNOWN, $invoice_reference, $payment_method, $txnResponseCode);
		
			// --- show pending message
			redirect('thanh-toan/dang-xu-ly.html');
		}
	}
	
	function pending() {
	
		$data = array();
		$data['payment_status'] = INVOICE_PENDING;
		
		$data['page_header'] = lang('payment_pending');
		
		$data = get_in_page_theme(THANK_YOU_PAGE, $data);
		
		$is_mobile = is_mobile();
			
		$mobile_view = $is_mobile ? 'mobile/' : '';
		
		_render_view($mobile_view.'payment/main_view', $data, $is_mobile);
	}
	
	function unsuccess() {
	
		$redirect = false;
	
		if(isset($_GET["invoice_ref"])) {
			$invoice_ref = $_GET["invoice_ref"];
				
			$invoice = $this->Booking_Model->get_invoice_info($invoice_ref);
				
			if(!empty($invoice)) {
				
			    $data = array();
				
				$is_mobile = is_mobile();
					
				$mobile_view = $is_mobile ? 'mobile/' : '';
				
				$data['payment_status'] = INVOICE_FAILED;
				
				$data['invoice'] = $invoice;
				
				$data['page_header'] = lang('payment_failed');
					
				$data['bank_transfer'] = $this->config->item('bank_transfer');
				
				$data['payment_method'] = $this->load->view($mobile_view.'payment/invoice_payment_methods', $data, TRUE);
		
				$data = get_in_page_theme(THANK_YOU_PAGE, $data);
				
				_render_view($mobile_view.'payment/main_view', $data, $is_mobile);
			} else {
				$redirect = true;
			}
		} else {
			$redirect = true;
		}
	
		if($redirect) {
			redirect(site_url());
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Generate invoice view.
	 *
	 * @access        public
	 * @return        void
	 */
	
	function invoice() {
	
		if(isset($_GET["ref"])) {
			$invoice_ref = $_GET["ref"];
				
			$invoice = $this->Booking_Model->get_invoice_info($invoice_ref);
				
			if(!empty($invoice)) {
			    
			    $is_mobile = is_mobile();
			    
			    $mobile_view = $is_mobile ? 'mobile/' : '';
			    
			    $data['VIEW_INVOICE'] = true;
			    
			    $data['invoice_type'] = FLIGHT;
			    
				$data['invoice'] = $invoice;
				
				$data['countries'] = $this->config->item('countries');
				
				$data['bank_transfer'] = $this->config->item('bank_transfer');

				//$data['pay_url'] = get_payment_url($invoice);
				//$data['bank_fee'] = $this->config->item('bank_fee');
	
				if(isset($invoice['type']) && !empty($invoice['type'])) {
					$data['invoice_type'] = $invoice['type'];
				}
				
				/*
				 * for test the invoce template only
				* khuyepv: 20.12.2013
				*/
				/*$data['valid_airline_codes'] = $this->config->item('valid_airline_codes');
					$html = $this->load->view('flights/payment/invoice_template', $data, true);
	
				echo $html;exit();*/
				
				$data['payment_method'] = $this->load->view( $mobile_view.'payment/invoice_payment_methods', $data, true);
				
				if($is_mobile)
				{   
				    $data['page_css'] = get_static_resources('mobile/invoice.css');
				    
				    $data['bpv_content'] = $this->load->view($mobile_view.'payment/invoice_view', $data, true);
				    
				    echo $this->load->view('mobile/_templates/bpv_layout', $data, true);
				} else 
				{
				    echo $this->load->view('payment/invoice_view', $data, true);
				}
				
				exit();
			}
		}
	
		redirect(site_url());
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Create pdf, send email confirm to client and system.
	 *
	 * @access        public
	 * @param         object        Invoice object
	 * @return        void
	 */
	
	function process_hotel_confirm($data) {
	
		// do nothing
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Create pdf, send email confirm to client and system.
	 *
	 * @access        public
	 * @param         object        Invoice object
	 * @return        void
	 */
	
	function process_flight_confirm($data) {
	
		$invoice = $data['invoice'];
		$invoice_reference = $invoice['invoice_reference'];
	
		$data['valid_airline_codes'] = $this->config->item('valid_airline_codes');
		
		$data['config_title'] = $this->config->item('c_titles');
	
		// --- create invoice pdf
		$html = $this->load->view('flights/payment/invoice_template', $data, true);
		$mail_attachment = create_invoice_pdf($invoice_reference, $html);
	
		// --- send email to customer
		$to = $data['invoice']['customer']['email'];
	
		$subject = lang('flight_email_reply').' - ' . BRANCH_NAME;
	
		$mail_content = $this->load->view('payment/payment_success_form_mail', $data, TRUE);
	
		// -- send email to customer
		_send_mail_payment($subject, $mail_content, $mail_attachment, $to);
		//_send_mail_payment_by_google_acc($data, $mail_attachment);
	
		// -- send email to system
		//_send_mail_payment_notification($data, 'Flight');
		_send_mail_payment($subject, $mail_content, $mail_attachment, FLIGHT_PAYMENT_NOTIFICATION_EMAIL);
	}
}