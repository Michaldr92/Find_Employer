<!DOCTYPE html>
<html>

<header>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Map Employeer</title>

    <!-------------------------------------WCZYTANIE BIBLIOTEK--------------------------------------->

    <link rel="stylesheet" href="<?=base_url()?>assets/js/jquery-ui.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/js/jquery-ui.theme.css">

    <link href="<?=base_url()?>assets/css/main.css" rel="stylesheet">

    <link rel="stylesheet" href="<?=base_url()?>assets/css/print-style.css" type="text/css" media="print" />

    <script src="<?=base_url()?>assets/js/jquery-1.11.3.min.js"></script>
    <script src="<?=base_url()?>assets/js/jquery-ui.js"></script>
    <script src="<?=base_url()?>assets/js/EmployerMapMain.js"></script>
    <script>
        var base_url = "<?=base_url();?>";
        var office_id = <?=$office_id;?>;
        var skala = <?=$skala;?>;
        var admin = <?=$admin;?>;
        var netid = "<?=$netid;?>";
        var empire_photo_prefix = "<?=EMPIRE_PHOTO_PREFIX;?>";
    </script>

</header>

<!----------------------------------------------------------------------------------------------------->

<body>

    <div id="glowny">

        <div class="pasek"></div>

        <div id="logo">
            <?php echo '<img src="'.base_url().'assets/img/logo.png"/>'; ?>
        </div>

        <?php 
					foreach ($offices as $value)
					{
						$styl='style="display:none"';
							//if ($value['default_office']==1) $styl='style="display:block"';
						echo '<div class="office_map_div" id = "office_div_'.$value['id'].'" '.$styl.'>';
						echo '<img class = "foteczka" src = "'.base_url().'assets/img/'.$value['filename'].'"  data-szer="'.$value['szer'].'"  data-wys="'.$value['wys'].'"   >';
						echo '<div class="office_name">'.$value['name'].'</div>';
						echo '</div>';
					}
				?>

        <div id="wyszukiwarka">

            <form action="<?= base_url().'C_user/'?>" method="post">
                <b>Szukaj pracownika:</b>
                <select id="szukaj_select">
					<option value="">Wybierz...</option>
				</select>

                <div id="find_button">Pokaż na mapie</div>

                <div id="radio_div"></div>

                <?php
								foreach($offices as $value)
								{
									$chk='';
									//if ($value['default_office'] > 0) $chk='checked="checked"';
									if ($value['id'] == $office_id) $chk='checked="checked"';
									echo '<input class="office_switch" type="radio" id="radio_office_'.$value['id'].'" value = "'.$value['id'].'" name="office_radio" '.$chk.'><label for="radio_office_'.$value['id'].'">'.$value['name'].'</label>';
								}
							?>

            </form>

        </div>

        <?php
		if($admin==1){
			
			echo '
			<div class="panel">
			<form action="'.base_url().'C_user/adduser" method="post"> 
			<b>Nazwisko:</b><br /> 
			<input type="text" name="employee" id="employee_input" /><br /> 
			<b>Imie:</b><br /> 
			<input type="text" name="imie" disabled="disabled" id="imie_input" /><br /> 
			<b>netID</b><br /> 
			<input type="text" name="netid" disabled="disabled" id="netid_input" /><br /> 			
			
			<br />
			<input id = "savebtn" type="button" value="Zapisz uklad" /> 
			</form>';
			
			
			echo '<div id="pole"></div>';
			
			echo '<div class = "kosz"<img src="'.base_url().'assets/img/kosz.png"/></div>'; 
			echo '</div>';		
		}
		?>

            <div id="butt">
                <?php 
			 if($admin==0){
				echo '<button class="zoomout">Zoom -</button > 	<button class="zoomreset">Zoom 0</button> <button class="zoomin">Zoom +</button>'; 
			 }
			?>
                <?php
				if ($admin== 0){
				echo '<a id="btn1" class="btn" href="'.base_url().'C_user/login">Zaloguj</a>';
				}else{
				echo '<a id="btn1" class="btn" href="'.base_url().'C_user/logout">Wyloguj</a>';
				}
			?>

            </div>

            <div id="dymek">
                <?php echo '<img src="'.base_url().'assets/img/brak.gif"/>'; ?>
                <b>Name: <span id = "dymek_imie"></span><br/>
				 Surname: <span id = "dymek_nazwisko"></span><br/>
				 Netid: <span id = "dymek_netid"></span><br/>
				 Email: <span id = "dymek_email"></span><br>
				 Tel.no.: <span id = "dymek_tel"></span><br>
				 Title: <span id = "dymek_title"></span><br></b>
            </div>


    </div>
</body>

</html>