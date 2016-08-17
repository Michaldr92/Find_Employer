<?php


// KOPIA FUNKCJI LOGUJ


	function login(){
		
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
					$this->index();
		}
			else{
				$data['Wrong']="Wrong!";
				$this->load->view('log',$data);
				$newdata = array( 'ADMIN' => 0);
			}
			
			
			$this->session->set_userdata($newdata);

	}

?>