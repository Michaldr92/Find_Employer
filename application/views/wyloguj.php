<?php


// KOPIA FUNKCJI WYLOGUJ


function logout(){
	$this->session->unset_userdata('LOGIN');
	$this->session->unset_userdata('PASS');
	$this->load->view('log');
}

?>