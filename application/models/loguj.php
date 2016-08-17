<?php

class Loguj extends CI_Model {

	function login(){ // Logowanie
		
		if (isset($_POST['LOGIN'])&&isset($_POST['PASS'])) { // Sprawdzenie czy log i hasło jest ustawione
			$L = $_POST['LOGIN'];
			$P = $_POST['PASS'];
		}
		else{
			$L=''; // Ustawienie pusty login
			$P=''; // Ustawienie puste hasło
		}
		
		if($L==LOGIN && $P==PASS){	// Jeśli się zgadza to pokaż panel admina
				$newdata = array( 'ADMIN' => 1);
				$this->index();
		}
			else{ // Jeśli się nie zgadza to wyświetl, że złe dane
				$data['Wrong']="Wrong!";
				$this->load->view('log',$data);
				$newdata = array( 'ADMIN' => 0);
			}
			
			
			$this->session->set_userdata($newdata); // Dołącz dane do sesji

	}
}
?>