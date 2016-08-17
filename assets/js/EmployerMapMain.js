	var dymek_timer = false;

	$(document).ready(function() {
			
	    show_office(office_id); // Pokaz obrazek biura
		
	    userslist(); // Wyświetl punkty (użytkownicy)


	    $('.zoomout').on('click', function() {
	        skaluj(0.5);
	    }); // Oddal
	    $('.zoomin').on('click', function() {
	        skaluj(2);
	    }); // Przybliż
	    $('.zoomreset').on('click', function() {
	        skaluj(0);
	    }); // Wczytaj domyślną pozycje


	    $('.panel').draggable(); // Panel Admina do przeciągania	
	
	    $('.kosz').droppable({ // Po przeciągnięciu na kosz usuwa punkt z mapy i bazy
	        accept: ".userdiv",
	        drop: function(event, ui) {
	            delete_point(ui.draggable);
	        }
	    });
	
	    $('#savebtn').click(function() { // Zapisuje pozycje punktów do bazy
	        save();
	    });

	    $('#szukaj_select').change(znajdz); // Wyszukuje użytkownika
																				
	    $('.office_switch').on('change', function() { // Przełącza biuro - widok

	        var office_id = $(this).val();
	        if (admin == 1) {
	            show_office(office_id);
	        } else {
	            document.location = base_url + 'C_user/office/' + office_id + '/' + skala;
	        }
	    });
							
	    $('#find_button').click(function() { // Szukaj pracownika
	        znajdz();
	    });
						
	    $("#employee_input").autocomplete({ //Dodawanie użytkownika, pojawienie się punktu na wyznaczonym kwadracie
	        source: base_url + 'C_user/getemployees',
	        minLength: 2,
	        select: function(event, ui) {

	            var netid = ui.item.netid;
	            var imie = ui.item.imie;
	            var nazwisko = ui.item.value;
	            $('#imie_input').val(imie);
	            $('#netid_input').val(netid);
	            if ($('#netid_' + netid).length < 1) { // Jeżeli nie ma użytkownika to dodaj
	                add_point({
	                    'imie': imie,
	                    'nazwisko': nazwisko,
	                    'netid': netid,
	                    'x': 60,
	                    'y': 382,
	                    'officeid': 1
	                });
	            } else { // Jesli istnieje wyswietl JS błąd
	                alert('Blad! Uzytkownik juz istnieje!');
	            }
	        }
	    });
	});
	

	function add_point(ob) { // Dodawanie punktu do mapy oraz "doklejenie"

	    var div = $('<div>' + ob['imie'][0] + ob['nazwisko'][0] + '</div>').prop('id', 'netid_' + ob['netid']).css('position', 'static').addClass('userdiv').prop('title', ob['imie'] + ' ' + ob['nazwisko']);

	    $('#pole').append(div);
	    div.on('mouseover', function() {
	        div.off('mouseover');
	        var xs = $(window).scrollLeft();
	        var ys = $(window).scrollTop();
	        var xy = $('.panel').offset();
	        //div.css('left',1*ob['x']+xs).css('top',1*ob['y']+ys).css('position','absolute');
	        div.css('left', 1 * xy.left + 50).css('top', 1 * xy.top + 280).css('position', 'absolute');

	        var element = div.detach();
	        $('#office_div_' + office_id).append(element);
	        div.draggable({
	            grid: [8, 8],
	            stop: function(event, ui) {
	                $(this).data('x', ui.offset.left).data('y', ui.offset.top);
	                $(this).addClass('userdiv_edited');
	            }
	        });
	    });
	    tooltips_ini();
	}


	function delete_point(ob) { // Usuwanie użytkownika/punktu
	    var id = ob.prop('id');
	    $.ajax({
	        dataType: "json",
	        url: base_url + 'C_user/delete_user/' + id,
	        success: function(data) {
	            $('#dymek').hide();
	            ob.remove();
	        }
	    });
	}


	function update_dymek(ob) { // Pokaż informacje w dymku o użytkowniku

	    var netid = ob.prop('id');
	    netid = netid.split('_');
	    netid = netid[1];

	    $.ajax({
	        dataType: "json",
	        url: base_url + 'C_user/update_dymek/' + netid,
	        success: function(data) {

	            $('#dymek_imie').text(data['givenname']); // imie
	            $('#dymek_nazwisko').text(data['sn']); // nazwisko
	            $('#dymek_netid').text(data['netid']); // netid
	            $('#dymek_email').text(data['mail']); //email
	            $('#dymek_tel').text(data['telephonenumber']); //telefon
	            $('#dymek_title').text(data['title']); // stanowisko
	            var img = $('#dymek img');
	            img.prop('src', data['thumbnailphoto']); // zdjęcie użytkownika

	            $('#dymek').show(); // pokaż dymek
	            var dymek = $('#dymek');
	            var wsp = ob.offset();
	            dymek.css('left', wsp.left + ob.width() + 'px').css('top', wsp.top + ob.height() + 'px'); // Ustaw obok punktu		

	        }
	    });
	}


	function userslist() { // Pobranie użytkowników

	    $.ajax({
	        dataType: "json",
	        url: base_url + 'C_user/getlist',
	        success: function(data) {
	            $('.userdiv').remove();
	            $('.users_option').remove();

	            for (d in data) {

	                var div = $('<div>' + data[d]['imie'][0] + data[d]['nazwisko'][0] + '</div>').prop('id', 'netid_' + data[d]['netid']).css('left', 1 * data[d]['x']).css('top', 1 * data[d]['y']).addClass('userdiv'); //.prop('title',data[d]['imie']+' '+data[d]['nazwisko']);
	                //var div = $('<div>'+data[d]['imie'][0]+data[d]['nazwisko'][0]+'</div>').prop('id','netid_'+data[d]['netid']).css('left',1*data[d]['x']).css('top',1*data[d]['y']).addClass('userdiv');
	                div.data('x', data[d]['x']).data('y', data[d]['y']);
	                $('#office_div_' + data[d]['officeid']).append(div);

	                var opt = $('<option class = "users_option" value="' + data[d]['netid'] + '">' + data[d]['nazwisko'] + ' ' + data[d]['imie'] + '</option>');
	                $('#szukaj_select').append(opt);
	            }

	            if (admin == 1) {
	                $('.userdiv').draggable({
	                    //snap: true, 
	                    grid: [8, 8],
	                    stop: function(event, ui) {
	                        $(this).data('x', ui.offset.left).data('y', ui.offset.top);
	                        $(this).addClass('userdiv_edited');
	                    }
	                });
	            }
	            tooltips_ini();
	            wh2data();
	            znajdz();

	            if (admin == 0) skaluj(skala);
	        }
	    });
	}


	function wh2data() { // Zdjęcie użytkownika
	    $('.foteczka').each(function() {
	        var fot = $(this);
	        fot.data('width', fot.width());
	        fot.data('height', fot.height());
	    });

	    $('.userdiv').each(function() {
	        var point = $(this);
	        point.data('width', point.width());
	        point.data('height', point.height());

	        var fontsize = parseInt(point.css('font-size'));
	        point.data('fontsize', fontsize);

	        var lineheight = parseInt(point.css('line-height'));
	        point.data('lineheight', lineheight);

	    });
	}

	function save() { // Zapisanie listy, punktów, użytkownika	

	    var places = [];
	    $('.userdiv_edited').each(function() {
	        var div = $(this);
	        var office_id = div.parent().prop('id');
	        var imienazwisko = div.prop('title');
	        var tab = imienazwisko.split(' ');
	        var imie = tab[0];
	        tab.shift();
	        nazwisko = tab.join(' ');
	        office_id = office_id.split('_');
	        office_id = office_id[2];
	        var p = div.offset();
	        var x = div.data('x');
	        var y = div.data('y');

	        places.push({
	            'id': div.prop('id'),
	            'imie': imie,
	            'nazwisko': nazwisko,
	            'x': x,
	            'y': y,
	            'office_id': office_id
	        });
	    });

	    $.ajax({
	        method: "post",
	        dataType: "json",
	        data: {
	            'places': places
	        },
	        url: base_url + 'C_user/savelist',
	        success: function(data) {
	            userslist();
	            alert('Układ strony zapisany!');
	        }
	    });
	}

	function show_office(id) { // Pokaż/Ukryj biuro
	    $('.office_map_div').hide();
	    $('#office_div_' + id).show();
	    office_id = id;

	}
		

	function znajdz() { // Automatyczne wyszukanie użytkownika -> Po wyborze nazwiska, automatycznie jest przewijana strona do danego punktu
	    //if (arguments[0]) netid=arguments[0];		

	    var netid_local = $('#szukaj_select').val();
	    if (netid_local != '') netid = netid_local;
	    //if (netid!='') netid_local=netid;

	    if (netid != '') {
	        var point = $('#netid_' + netid);
	        if (point.length == 1) {
	            var office_div = point.parent();
	            office_id = office_div.prop('id');
	            office_id = office_id.split('_');
	            office_id = office_id[2];

	            show_office(office_id); // Jeśli użytkownik jest w innym biurze to podmień mape

	            $('.office_switch').prop('checked', false);
	            $('#radio_office_' + office_id).prop('checked', true);
	            //$('#radio_office_'+office_id).click();

	            var offset = point.offset();
	            //var x = point.data('x');
	            //var y = point.data('y');
	            var scrollX = offset.left - $(window).width() / 2;
	            var scrollY = offset.top - $(window).height() / 2;


	            if (scrollX < 0) scrollX = 0;
	            if (scrollY < 0) scrollY = 0;

	            $("html, body").animate({
	                scrollLeft: scrollX,
	                scrollTop: scrollY
	            }, 1000, 'easeInOutCubic'); // Przesuwanie scroll

	            point.delay(1000).toggle("pulsate", {}, 1000).css('backgroundColor', 'red'); // Miganie - kolor czerwony
	            point.toggle("pulsate", {}, 2000, function() {
	                point.css("opacity", 0.75).css('backgroundColor', 'yellow'); // Jak znajdzie wczytaj domyslny kolor
	            });
	        } else {
	            alert('Nie znaleziono pracownika o podanym ID!'); // Jeśli nie ma pracownika to wyświetl komunikat
	        }
	    }
	}

	
	function tooltips_ini() // Akcja po najechaniu myszką na punkt
	{
	    $(".userdiv").mouseover(function() {
	        pokaz_dymek($(this));
	    });
	    $(".userdiv").mouseout(function() {
	        if (dymek_timer) window.clearTimeout(dymek_timer);
	        $('#dymek').hide();
	    });
	}
		

	function pokaz_dymek(ob) { // Pokaż cały dymek 

	    var _this = ob;
	    if (!arguments[1]) {
	        if (dymek_timer) window.clearTimeout(dymek_timer);
	        dymek_timer = setTimeout(function() {
	            pokaz_dymek(_this, 'd')
	        }, 300);
	        return false;
	    }
	    update_dymek($(_this));
	}


	function skaluj(k) { // FUNKCJA SKALUJĄCA z parametrem

	    var reset = false;
	    if (k == 1 || k == 0) {
	        reset = true;
	        skala = 1 / skala;
	        k = skala;
	    } else {

	        if (skala < 1 && k < 1) k = 1;
	        if (skala > 1 && k > 1) k = 1;

	        skala = skala * k;
	    }
	    if (reset) skala = 1;

	    // Skalowanie zdjęcia biura

	    $('.foteczka').each(function() {
	        var office_img = $(this);
	        var img_width = office_img.data('szer');
	        //var img_width = office_img.data('width');
	        if (img_width > 0) office_img.width(img_width * skala);

	    });

	    // Skalowanie punktów

	    $('.userdiv').each(function() {
	        var point = $(this);
	        //var org_xy= point.offset();
	        var x = point.data('x');
	        var y = point.data('y');
	        if (x > 0 && y > 0) {
	            point.css('left', x * skala);
	            point.css('top', y * skala);
	            point.height(point.data('height') * skala);
	            point.width(point.data('width') * skala);

	            var fontsize = parseInt(point.css('font-size'));
	            point.css('font-size', point.data('fontsize') * skala + 'px');

	            var lineheight = parseInt(point.css('line-height'));
	            point.css('line-height', point.data('lineheight') * skala + 'px');
	        }

	    });
	}