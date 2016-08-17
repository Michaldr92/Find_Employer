<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class C_user extends CI_Controller {
	
	public function __construct()
       {
            parent::__construct();
			
				$this->load->model('M_user');		// Załadowanie modelu - logika
       }	
	
	
	public function index($office_id = 0, $skala = 0.5, $netid = '')  // Ustawienie domyślnej skali
	{
		$offices = $this->M_user->getoffices(); // Pobranie obrazu biura 
		
		if ($office_id == 0) $office_id = $this->M_user->getdefaultffice(); // Ustawienie na główne biuro
		
		$this->load->view('all',array('offices'=>$offices,'admin'=>$this->M_user->czy_admin(),'office_id'=>$office_id, 'skala'=>$skala, 'netid'=>$netid)); // Wczytanie widoku wszystko, przekazanie zmiennych -> admin, biuro, skala, netid

	}
	
	public function getuserinfo($netID) // Pobranie danych użytkownika
	{
		$list = $this->M_user->getusersinfo($netID);
		$this->load->view('users_list_json', array('response'=>$list));
		//$this->load->view('all', array('info'=>$info));
	}
		
	public function getlist()  // Pobranie punktów na mape
	{
		$list = $this->M_user->getlist();
		$this->load->view('users_list_json', array('response'=>$list));		
	}
	
	
		public function getemployees()  // Pobranie listy użytkownika do selectora
	{
		$nazwisko = $this->input->get('term');
		$list = $this->M_user->getemployees($nazwisko);
		$this->load->view('json_view', array('response'=>$list));		
	}
	
	
	public function savelist() // Zapisanie punktów na mapie
	{
		$status=$this->M_user->savelist($this->input->post());
		$error = "";
		
		if (! $status) $error='Blad zapisu do bazy';
		$this->load->view('save_status_json', array('response'=>$status,'error'=>$error));		
	}
	
	public function adduser() // Dodanie nowego użytkownika
	{
		$add = $this->M_user->adduser($this->input->post());
		$this -> load->view('users_list_json', array('response'=>$add));
	}
	

	function login(){ // Panel Admina - Logowanie
		
		if (isset($_POST['LOGIN'])&&isset($_POST['PASS'])) {
			$L = $_POST['LOGIN'];
			$P = $_POST['PASS'];
		}	
			else{
				$L='';
				$P='';
			}
	
		if($L==LOGIN && $P==PASS){	
			$newdata = array( 'ADMIN' => 1);
			$this->session->set_userdata($newdata);
			redirect('', 'refresh');
			//$this->index();
			
		}
			else{
				$data['Wrong']="";
				if ($L != '' && $P !='' )	$data['Wrong']="Wrong!";
				//$newdata = array( 'ADMIN' => 0);
				$this->load->view('log',$data);
			}
	}

	function logout() // Panel Admina - wylogowanie
	{
		$this->session->unset_userdata('ADMIN');
		session_destroy();
		redirect('', 'refresh');
	}


	public static function &get_instance() // pobranie obiektu
    {
        return self::$instance;
    }
	
	public function delete_user($id) // Usunięcie użytkownika
	{
		$this->M_user->delete_user($id);
		$this->load->view('json_view', array('response'=>'ok'));		
	}
	
	public function update_dymek($netid) // Chmura z informacjami użytkownika po najechaniu na punkt
	{
		$data = $this->M_user->getusersinfo($netid);
		$this->load->view('json_view', array('response'=>$data));
	}
	
	public function office($office_id,$skala=1,$netid = '') // Biura i skala -> Trzymanie skali po przełączeniu na inne biuro
	{
		$this->index($office_id,$skala, $netid);
	}
	
	
}


?>