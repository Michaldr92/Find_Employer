<?php
class Wyloguj extends CI_Model {
	
	function logout(){ // Wylogowanie
		$this->session->unset_userdata('LOGIN');
		$this->session->unset_userdata('PASS');
		$this->load->view('log'); // Wczytanie widoku ekranu logowania
	}
}

?>