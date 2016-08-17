<?php

class M_user extends CI_Model{
	
/*	

	
*/
public function getoffices() // Pobranie danych z bazy
{
		$q = 'SELECT id, name, filename, default_office, wys, szer FROM offices ';
		$result = $this ->db -> query($q)->result_array();
		
		return $result;
}

public function getdefaultffice() // Pobierz domyślną mape biura
{
	$q='SELECT id FROM `offices` WHERE default_office = 1 LIMIT 1';
	$result = $this ->db -> query($q)->result_array();
		
		return $result[0]['id'];
	}
		
// Pobranie danych z bazy do biura / miejsca	
public function getlist()
{
		$result = $this->db->query('SELECT filename, netid, imie, nazwisko, x, y, offices.name as officename, offices.id as officeid FROM places, offices WHERE places.office_id = offices.id AND places.active=1 ORDER BY nazwisko')->result_array();
				
		return $result;
}

	
public function czy_admin(){ // Sprawdz czy użytkownik jest adminem
			$ret=0;
			$sesja=$this->session->get_userdata();
			if (isset($sesja['ADMIN'])) $ret = $sesja['ADMIN'];
			return $ret;
	}


public function backup_getlist($name) // Backup
{
		$result = $this->db->query('SELECT filename, netid, imie, nazwisko, x, y FROM places, offices WHERE places.office_id = offices.id AND offices.name = "'.$name.'" ')->result_array();
				
		return $result;
}
	
	// Zapisanie uzytkownika, dodanie punktow, miejsce pracy, stan aktywny/nieaktywny
public function savelist($data)
{	
if($this->czy_admin() == 1)
	{
		if(isset($data['places']))
		{				
			$places = $data['places'];
			
			foreach ($places as $v){

				if($v['office_id'] >0 && $v['x'] > 0 && $v['y'] > 0 && $v['id'] != '') // Zapisywanie punktów do bazy danych
				{
					$net_id = explode('_', $v['id']);
					$net_id = $net_id[1];
					$q = 'INSERT INTO places (netid, imie, nazwisko, x, y, office_id, active) VALUES ("'.$net_id.'","'.$v['imie'].'", "'.$v['nazwisko'].'",'.$v['x'].','.$v['y'].' , '.$v['office_id'].' , 1) ON DUPLICATE KEY UPDATE x = VALUES (x), y = VALUES(y), office_id = VALUES(office_id) ';
					$this->db->query($q);	
				}
			}		
				return true;
			
		} else 
				return false;
	}
}
// pobranie danych o użytkowniku z AD przed LDAP
	public function getusersinfo($netID){		
		$ret=array();
		
		if (isset($netID) && strlen($netID)>2 ) {
			
			$ds=ldap_connect(LDAP_SERVER);
			
				if ($ds){
					$r=ldap_bind($ds, LDAP_USER, LDAP_PASS);
					$sr=ldap_search($ds, LDAP_DN, "samaccountname=".$netID);
					$info = ldap_get_entries($ds, $sr);
					
				
					
					$ret['givenname']=$ret['sn']=$ret['netid']=$ret['mail']=$ret['title']=$ret['telephonenumber']=$ret['employeenumber']=""; // Pusta tablica
					$ret['thumbnailphoto']=base_url().'assets/img/brak.gif';	// Jesli nie ma zdjęcia w bazie to wyswietl brak zdjecia
					
					
					if (isset($info[0])){
							$info=$info[0];
							$ret['netid']=$netID;
							
							if (isset($info['givenname'][0])) 	$ret['givenname']=$info['givenname'][0]; // Imię
							if (isset($info['sn'][0]))	$ret['sn']=$info['sn'][0]; // Nazwisko
							if (isset($info['mail'][0])) 	$ret['mail']=$info['mail'][0]; // email
							if (isset($info['title'][0])) 		$ret['title']=$info['title'][0]; // stanowisko
							if (isset($info['telephonenumber'][0]))    $ret['telephonenumber']=$info['telephonenumber'][0]; // nr tel
							if (isset($info['employeenumber'][0]))    $ret['employeenumber']=$info['employeenumber'][0]; // nr pracownika
							
							$url = EMPIRE_PHOTO_PREFIX.$ret['employeenumber'].'.jpg'; // zdjecie z empire
							
							if($this->spr_url($url)){								
								$ret['thumbnailphoto']=$url;							
							}
											
					}
				}		
			return $ret;
		}	
	}		
public function getemployees($nazwisko){ // Pobieranie listy nazwisk do selektora
			
		$ret=array();
	
		if ( isset($nazwisko) && strlen($nazwisko)>1 ) {
				
			$ds=ldap_connect(LDAP_SERVER); // Połączenie z LDAP

			if ($ds){
				$r=ldap_bind($ds, LDAP_USER, LDAP_PASS);
				$sr=ldap_search($ds, LDAP_DN, "sn=".$nazwisko."*");
				$info = ldap_get_entries($ds, $sr);		
				$c=(int)$info['count'];
				for ($i=0; $i < $c; $i++){					
					$ret[$i]['id']=$info[$i]['samaccountname'][0];
					$mail='';
					if (isset($info[$i]['mail'])) $mail=$info[$i]['mail'][0];
					
					$ret[$i]['label']=$info[$i]['sn'][0].' '.$info[$i]['givenname'][0].' | '.$mail.' | '.$info[$i]['samaccountname'][0];
					$ret[$i]['value']=$info[$i]['sn'][0];
					$ret[$i]['imie']=$info[$i]['givenname'][0];		
					$ret[$i]['netid']=$info[$i]['samaccountname'][0];
				}		
			}
		}		
			return $ret;			
	}
	
	public function delete_user($id) // Usuwanie użytkownika z bazy i na mapie
	{
		if($this->czy_admin() == 1) // Sprawdza czy zalogowany uzytkownik jest adminem
		{
			$tab = explode('_',$id);
			$netid='';
			if (isset($tab[1])) $netid=$tab[1];
				$query = 'DELETE FROM `places` WHERE netid = "'.$netid.'" LIMIT 1';  // Polecenie usunięcia
				$this->db->query($query); // Wykonaj w bazie
					return true;
		}
	}
	
	private function spr_url($url){ // Dodatkowa biblioteka
      $AgetHeaders = @get_headers($url);
        if (preg_match("|200|", $AgetHeaders[0])) {
         return true;
        } 
		else {
              return false;
          }
	}
}	

	
?>