<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['account_suffix']		= '@XXXXXXX.XXX'; // konto z domeny
$config['base_dn']				= 'DC=XXXXXXX,DC=XXX'; // baza domeny
$config['domain_controllers']	= array ("XX.XX.XX.XX"); // IP domeny
$config['ad_username']			= 'xxx-xxxxxxxxx'; // Login do AD
$config['ad_password']			= 'xxxxxxxxx'; // Hasło do AD
$config['real_primarygroup']	= true;
$config['use_ssl']				= false;
$config['use_tls'] 				= false;
$config['recursive_groups']		= true;


/* End of file adldap.php */
/* Location: ./system/application/config/adldap.php */