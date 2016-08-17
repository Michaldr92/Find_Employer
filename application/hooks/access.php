<?php
class Access
{

	var $CI;

	public function index()
	{
		$routing =& load_class('Router');
		$method = $routing->fetch_method();
		$class = $routing->fetch_class();

        $CI =& get_instance();
        $CI->load->library('session');
        $CI->load->helper('url');

        $URL = base_url();

        $login = $CI->session->userdata('LOGIN');
        $pass = $CI->session->userdata('PASS');

		if($class=="C_user" && ($method == "login" || $method == "logout")){

				}
					elseif($login==LOGIN && $pass == PASS){

					}
				/*	else{
						
							//header("location: {$URL}C_user/login");
						}
				*/
	}	
}
?>