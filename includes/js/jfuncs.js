function loadingpercentage(done,tasks) {
    $.post('/../../core/funcs/jfuncs.php', { action: "loadingpercentage", done: done, tasks: tasks }, function(result) {
        $('#loading span').text(result +"%");
        if(result==100) {
           $('#loading').fadeOut(300);
        }
    });
    $('#loading span').text("0%");
}
function acceptbrisanjeporuka() {
    $('#popup').slideUp(300);
    $('#loading').fadeIn(300);
    var numchecked = $('#ap-poruke input[type=checkbox]:checked').length;
    var numchechboxes = $('#ap-poruke input[type=checkbox]').length;
    var count = 0;
    if(numchecked==0) {
        $('#ap-poruke input[type=checkbox]').each(function () {
            var mid = $(this).parents(".message").attr('mid');
            $(this).parents(".message").remove();
            $.post('/../../core/funcs/jfuncs.php', { action: "obrisiporuku", mid: mid }, function(result) {
                count++;
                loadingpercentage(count,numchechboxes);
            });
        });
    } else {
        $('#ap-poruke input[type=checkbox]:checked').each(function () {
            var mid = $(this).parents(".message").attr('mid');
            $(this).parents(".message").remove();
            $.post('/../../core/funcs/jfuncs.php', { action: "obrisiporuku", mid: mid }, function(result) {
                count++;
                loadingpercentage(count,numchecked);
            });
        });
    }
}
function deleteapmessagesask() {
    var numchecked = $('#ap-poruke input[type=checkbox]:checked').length;
    if(numchecked==0) {
        var question = "Da li želiš da obrišeš sve poruke?";
    } else {
        var question = "Da li želiš da obrišeš označene poruke?";
    }
    $.post('/../../core/funcs/popups.php', { action: "deleteapmessagesask", msg: question }, function(result) {
        $('body').css('overflow','hidden');
        $('#popup').slideDown(300);
        $('#popupcontnent').html(result);
    });
}
function oznaciporukekao(seen) {
    $('#loading').fadeIn(300);
    var numchecked = $('#ap-poruke input[type=checkbox]:checked').length;
    var numchechboxes = $('#ap-poruke input[type=checkbox]').length;
    var count = 0;
    if(numchecked==0) {
        $('#ap-poruke input[type=checkbox]').each(function () {
            var mid = $(this).parents(".message").attr('mid');
            if(seen==1) {
                $(this).parents(".message").addClass('seen');
            } else {
                $(this).parents(".message").removeClass('seen');
            }
            $.post('/../../core/funcs/jfuncs.php', { action: "markmsgsas", mid: mid, seen: seen }, function(result) {
                count++;
                loadingpercentage(count, numchechboxes);
            });
        });
    } else {
        $('#ap-poruke input[type=checkbox]:checked').each(function () {
            var mid = $(this).parents(".message").attr('mid');
            if(seen==1) {
                $(this).parents(".message").addClass('seen');
            } else {
                $(this).parents(".message").removeClass('seen');
            }
            $.post('/../../core/funcs/jfuncs.php', { action: "markmsgsas", mid: mid, seen: seen }, function(result) {
                count++;
                loadingpercentage(count, numchecked);
            });
        });
    }
}

$('#ap-msg-cbx').change(function() {
    $('#ap-poruke input[type=checkbox]').each(function () {
        if($('#ap-msg-cbx').is(':checked')) {
            $(this).prop('checked', true);
        } else {
            $(this).prop('checked', false);
        }
    });
});

function obrisiobavestenje(oid) {
    $.post('/../../core/funcs/jfuncs.php', { action: "obrisiobavestenje", oid: oid }, function(result) {
        window.location.href = "/obavestenja/";
    });
}
function videoattachment() {
    var vid = $('#vid').val();
   $.post('/../../core/funcs/jfuncs.php', { action: "videoattachment", vid: vid }, function(result) {
        if(result!="error") {
            $('#attachedvid').attr('src','https://www.youtube.com/embed/'+result);
            $('#attachedvid').slideDown('500');
        } else {
            $('#attachedvid').hide();
        }
    });
}
$('#vid').change(function() {
    videoattachment();
});
function editvideo() {
    var id = $('#editvid').val();
    var vid = $('#evid').val();
    var title = $('#editvtitle').val();
    var text = $('#editvtext').val();
    $.post('/../../core/funcs/jfuncs.php', { action: "editvideo", id: id, vid: vid, title: title, text: text }, function(result) {
        window.location.href = "/video/";
    });
}
function editdogadjaj() {
    $.post('/../../core/funcs/popups.php', { action: "izmenivideo", vid: vid }, function(result) {
        $('body').css('overflow','hidden');
        $('#popup').slideDown(300);
        $('#popupcontnent').html(result);
    });
}
function izmenivideo(vid) {
    $.post('/../../core/funcs/popups.php', { action: "izmenivideo", vid: vid }, function(result) {
        $('body').css('overflow','hidden');
        $('#popup').slideDown(300);
        $('#popupcontnent').html(result);
    });
}
function acceptobrisidogadjaj(did) {
    $.post('/../../core/funcs/jfuncs.php', { action: "deletedogadjaj", did: did }, function(result) {
        window.location.href = "/dogadjaji/";
    });
}
function deletedogadjaj(did) {
    $.post('/../../core/funcs/popups.php', { action: "deletedogadjaj", did: did }, function(result) {
        $('body').css('overflow','hidden');
        $('#popup').slideDown(300);
        $('#popupcontnent').html(result);
    });
}
function acceptobrisivideo(vid) {
    $.post('/../../core/funcs/jfuncs.php', { action: "obrisivideo", vid: vid }, function(result) {
        window.location.href = "/video/";
    });
}
function obrisivideo(vid) {
    $.post('/../../core/funcs/popups.php', { action: "obrisivideo", vid: vid }, function(result) {
        $('body').css('overflow','hidden');
        $('#popup').slideDown(300);
        $('#popupcontnent').html(result);
    });
}

function dodajvideo() {
    var vid = $('#vid').val();
    var vtext = $('#vtext').val();
    var vtitle = $('#vtitle').val();
    $('#vidmsg').parent('div.hidden').removeClass('hidden');
    $('#vidmsg div.text').html("");
    $('#vidmsg').removeClass("notify");
    $('#vidmsg').removeClass("success");
    $('#vidmsg').removeClass("error");
    if(vid!=""&&vtitle!="") {
        $.post('/../../core/funcs/jfuncs.php', { action: "dodajvideo", vid: vid, text: vtext, title: vtitle }, function(result) {
            if (result=="succ") {
                $('#vidmsg div.text').html("Video uspešno dodat!");
                $('#vidmsg').addClass('success');
                location.reload();
            } else {
                $('#vidmsg div.text').html("Unesi ispravan YouTube Video ID!");
                $('#vidmsg').addClass('error');
            }
           
            
        });
    } else {
        if(vid==""&&vtitle=="") {
            $('#vidmsg div.text').html("Polja za naslov i video id moraju biti popunjena!");
            $('#vidmsg').addClass('notify');
        } else if(vid=="") {
            $('#vidmsg div.text').html("Unesite YouTube Video ID");
            $('#vidmsg').addClass('notify');
        } else {
            $('#vidmsg div.text').html("Unesite naslov videa!");
            $('#vidmsg').addClass('notify');
        }
    }
}

function acceptdeleteporuku(did) {
    $.post('/../../core/funcs/jfuncs.php', { action: "obrisiporuku", did: did }, function(result) {
        window.location.href = "/adminpanel/poruke/";
    });
}

function obrisiporuku(did) {
    $.post('/../../core/funcs/popups.php', { action: "obrisiporuku", did: did }, function(result) {
        $('body').css('overflow','hidden');
        $('#popup').slideDown(300);
        $('#popupcontnent').html(result);
    });
}

function showdogadjaj(did) {
    $.post('/../../core/funcs/popups.php', { action: "showdogadjaj", did: did }, function(result) {
        $('body').css('overflow','hidden');
        $('#popup').slideDown(300);
        $('#popupcontnent').html(result);
    });
}

function updatedate(did, date) {
    $('#'+did).val(date);
    $('#popup').slideUp(300);
    $('body').css('overflow','auto');
    $('#popupcontnent').html("");
    $('#hiddendogadjajdate').val(date);
}

function todaydate() {
    var did = $('#date').attr('did');
    $.post('/../../core/funcs/jfuncs.php', { action: "todaydate", did: did }, function(result) {
        $('#kalendar').html(result);
    });
}
function prevdate() {
    var did = $('#date').attr('did');
    var month = $('#date').attr('month');
    var year = $('#date').attr('year');
    $.post('/../../core/funcs/jfuncs.php', { action: "prevdate", month: month, year: year, did: did }, function(result) {
        $('#kalendar').html(result);
    });
}
function nextdate() {
    var did = $('#date').attr('did');
    var month = $('#date').attr('month');
    var year = $('#date').attr('year');
    $.post('/../../core/funcs/jfuncs.php', { action: "nextdate", month: month, year: year, did: did }, function(result) {
        $('#kalendar').html(result);
    });
}

function choosedate(did) {
    $.post('/../../core/funcs/popups.php', { action: "choosedate", did: did }, function(result) {
        $('body').css('overflow','hidden');
        $('#popup').slideDown(300);
        $('#popupcontnent').html(result);
    });
}

function izmeniobjavu2() {
    $('#edittitle').css('background-color','#e3e3e3');
    $('#edittext').css('background-color','#e3e3e3');
    var oid = $('#editoid').val();
    var title = $('#edittitle').val();
    var text = $('#edittext').val();
    if (title!=""&&text!="") {
        $.post('/../../core/funcs/jfuncs.php', { action: "izmeniobjavu", oid: oid, title: title, text: text }, function(result) {
            window.location.href = result;
        });
    } else if(title==""&&text=="") {
        $('#edittitle').css('background-color','#f3cece');
        $('#edittext').css('background-color','#f3cece');
    } else if(title=="") {
        $('#edittitle').css('background-color','#f3cece');
    } else {
        $('#edittext').css('background-color','#f3cece');
    }
    
}
$('.newpost .title').click(function () {
    $('.newpost .npcont').slideToggle(300);
    $('.newpost .pointer').toggleClass('on');
});


$('.profil .avatar').click(function () {
    var avatar = $(this).attr('img');
    $('#popupcontnent').html("<img class='popupimg' src='/"+avatar+"'>");
    $('#popup').slideDown(500);
});

$('#wmtitle').keyup(function (e){
    $('#wmtitle').css('background-color','#e3e3e3');
})
$('#wmtext').keyup(function (e){
    $('#wmtext').css('background-color','#e3e3e3');
})

function newpost(type,page) {
    var image = $('#dndimg').val();
    $('#npmsg1').parent('div.hidden').removeClass('hidden');
    $('#npmsg1 div.text').html("");
    $('#npmsg1').removeClass("notify");
    $('#npmsg1').removeClass("success");
    $('#npmsg1').removeClass("error");
    if (type=="1") {
        var title = $('#notitle').val();
        var text = $('#notext').val();
    } else {
        var title = $('#wmtitle').val();
        var text = $('#wmtext').val();
    }
    $('#npmsg1 div.text').html("Molimo sačekajte...");
    if (title!=""&&text!="") {
        $.post('/../../core/funcs/jfuncs.php', { action: "newpost", image: image, title: title, text: text, page: page }, function(result) {
            $('#loading').fadeOut(300);
            $('#loading span').text("");
            window.location = result;
        });
    } else {
        $('#npmsg1 div.text').html("Popunite sva polja!");
        $('#npmsg1').addClass('notify');
        $('#wmtitle').css('background-color','#f3cece');
        $('#wmtext').css('background-color','#f3cece');
    }
}

function aktivacionikod() {
    $.post('/../../core/funcs/popups.php', { action: "aktivacionikod" }, function(result) {
        $('#popupcontnent').html(result);
        $('#akemail').focus();
    });

}
function zaboravljenalozinka() {
    $.post('/../../core/funcs/popups.php', { action: "zaboravljenalozinka" }, function(result) {
        $('#popupcontnent').html(result);
        $('#zlemail').focus();
    });
}
function loginuser(uid) {
    $.post('/../../core/funcs/jfuncs.php', { action: "loginuser", uid: uid }, function(result) {
		location.reload();
	});	
}

function showpwonreg() {
    $('#bigeye').toggleClass('on');
    var on = $('#bigeye').attr('on');
    if(on==0) {
        $('#regpw').attr('type','text');
        $('#bigeye').attr('on','1');
    } else {
        $('#regpw').attr('type','password');
        $('#bigeye').attr('on','0');
    }
    
}

function posaljiaktivacionikod() {
    $('#akmsg div.text').html("Molimo sačekajte...");
    $('#akmsg').parent('.hidden').removeClass('hidden');
    $('#akmsg').removeClass("notify");
    $('#akmsg').removeClass("success");
    $('#akmsg').removeClass("error");
    var email = $('#akemail').val();
    if (email=="") {
        $('#akmsg div.text').html("Unesite email adresu!");
        $('#akmsg').addClass("notify");
    } else {
        $('#akmsg div.text').html("Molimo sačekajte...");
        $.post('/../../core/funcs/jfuncs.php', { action: "posaljiaktivacionikod", email: email }, function(result) {
            if(result=="error1") {
                $('#akmsg div.text').html("Email adresa koju ste uneli nije pronađena!");
                $('#akmsg').addClass("error");
            } else if(result=="error2") {
                $('#akmsg div.text').html("Vaš nalog je već aktiviran!");
                $('#akmsg').addClass("error");
            } else {
                $('#akmsg').addClass("success");
                $('#akmsg div.text').html("Poslali smo Vam email!");
                $('#akemail').val("");
            }
        });
    }
}
function posaljizabloz() {
    $('#zlmsg').parent('.hidden').removeClass('hidden');
    $('#zlmsg').removeClass("notify");
    $('#zlmsg').removeClass("success");
    $('#zlmsg').removeClass("error");
    $('#zlmsg div.text').html("Molimo sačekajte...");
    var email = $('#zlemail').val();
    if (email=="") {
        $('#zlmsg').addClass("notify");
        $('#zlmsg div.text').html("Unesite email adresu!");
    } else {
        $.post('/../../core/funcs/jfuncs.php', { action: "posaljizabloz", email: email }, function(result) {
            if(result=="error") {
                $('#zlmsg div.text').html("Email adresa koju ste uneli nije pronađena!");
                $('#zlmsg').addClass("error");
            } else {
                $('#zlmsg').addClass("success");
                $('#zlmsg div.text').html("Poslali smo Vam email!");
                $('#zlemail').val("");
            }
        });
    }
}
function register() {
    $('#regmsg').parent('.hidden').removeClass('hidden');
    $('#regmsg').removeClass("success");
    $('#regmsg').removeClass("notify");
    $('#regmsg').removeClass("error");
    $('#regmsg div.text').html("Molimo sačekajte...");
    var reguname = $('#reguname').val();	
    var regname = $('#regname').val();
    var regemail = $('#regemail').val();
    var regpw = $('#regpw').val();
    $.post('/../../core/funcs/jfuncs.php', { action: "register", reguname: reguname, regname: regname, regemail: regemail, regpw: regpw }, function(result) {
        if(result=="error1") {
			$('#regmsg div.text').html("Popuni sva polja!");
            $('#regmsg').addClass("notify");
        } else if(result=="error2") {
			$('#regmsg div.text').html("Unesite ispravnu email adresu!");
            $('#regmsg').addClass("error");
        } else if(result=="error3") {
			$('#regmsg div.text').html("Korisničko ime je već zauzeto!");
            $('#regmsg').addClass("error");
        } else if(result=="error4") {
			$('#regmsg div.text').html("Nalog je već registrovan sa tom email adresom!");
            $('#regmsg').addClass("error");
        } else if(result=="error5") {
			$('#regmsg div.text').html("Lozinka mora da sadrži minimum 6 karaktera!");
            $('#regmsg').addClass("error");
        } else if(result=="succ1") {
            $('#regmsg').addClass("success");
            $('#regmsg div.text').html("Uspešno ste se registrovali.");
            location.reload();
        } else if(result=="succ2") {
            $('#regmsg').addClass("success");
            $('#regmsg div.text').html("Uspešno ste se registrovali. Potvrdite registraciju preko vašeg email-a.");
            
            $('#reguname').val('');	
            $('#regname').val('');
            $('#regemail').val('');
            $('#regpw').val('');
		} else {
            location.reload();
        }
    });
}
function logincheck() {
    $('#loginmsg').parent('.hidden').removeClass('hidden');
    $('#loginmsg div.text').html("Molimo sačekajte...");
    $('#loginmsg').removeClass("notify");
    $('#loginmsg').removeClass("success");
    $('#loginmsg').removeClass("error");
    var uname = $('#loginUname').val();	
	var upass = $('#loginPass').val();
    $.post('/../../core/funcs/jfuncs.php', { action: "logincheck", uname: uname, upass: upass }, function(result) {
        if(result=="error1") {
            $('#loginmsg').addClass("notify");
			$('#loginmsg div.text').html("Popuni sva polja!");
        } else if(result=="error2") {
			$('#loginmsg div.text').html("Korisnik nije pronađen!");
            $('#loginmsg').addClass("error");
        } else if(result=="error3") {
			$('#loginmsg div.text').html("Vaš nalog nije verifikovan!");
            $('#loginmsg').addClass("notify");
        } else if(result=="error4") {
			$('#loginmsg div.text').html("Uneli ste pogrešnu lozinku!");
            $('#loginmsg').addClass("error");
        } else {
            $('#loginmsg div.text').html("Uspešno ste se prijavili!");
            $('#loginmsg').addClass("success");
			loginuser(result);
		}
    });
}
function loginregister() {
	$('body').css('overflow','hidden');
    $('#popup').slideDown(300);
	$.post('/../../core/funcs/popups.php', { action: "loginreg" }, function(result) {
        $('#popupcontnent').html(result);
        $('#loginUname').focus();
    });
}
function formatText(el,tag){
	var selectedText = document.selection?document.selection.createRange().text:el.value.substring(el.selectionStart,el.selectionEnd);
	var newText='<'+tag+'>'+selectedText+'</'+tag+'>';
	if(document.selection){
		document.selection.createRange().text=newText;			
	} else {
		el.value=el.value.substring(0,el.selectionStart)+newText+el.value.substring(el.selectionEnd,el.value.length);
	}
	var l = el.value.indexOf(selectedText);
	if(l!=-1){
		el.focus();
		el.selectionStart = l;
		el.selectionEnd = l+selectedText.length
	}
}
function izmeniobjavu(oid) {
    $.post('/../../core/funcs/popups.php', { action: "izmeniobjavu", oid: oid }, function(result) {
        $('#popupcontnent').html(result);
        $('body').css('overflow','hidden');
        $('#popup').slideDown(300);
    });
}

function acceptdeleteobjavu(oid) {
    $.post('/../../core/funcs/jfuncs.php', { action: "obrisiobjavu", oid: oid }, function(result) {
		window.location.href = "/textovi/";
	});
}

function obrisiobjavu(oid) {
    $.post('/../../core/funcs/popups.php', { action: "obrisiobjavu", oid: oid }, function(result) {
        $('#popupcontnent').html(result);
        $('body').css('overflow','hidden');
        $('#popup').slideDown(300);
    });
}

function hidepopup() {
    if($('#popup').is(":visible")) {
        $('#popup').slideUp(300);
        $('body').css('overflow','auto');
    }
}

$(document).keyup(function(e) {
    if (e.keyCode == 27) {
        if($('#popup').is(":visible")) {
            $('#popup').slideUp(300);
            $('body').css('overflow','auto');
            $('#popupcontnent').html("");
        }
        if($('#writermode').is(":visible")) {
            $('#writermode').fadeOut(300);
        }
    }
});

$(document).keypress(function(e) {
    if(e.which == 13) {
        var focused = $(':focus');
        if($('#loginUname').is(":focus")) {
            logincheck();
        }
        if($('#loginPass').is(":focus")) {
            logincheck();
        }
        if($('#reguname').is(":focus")||$('#regname').is(":focus")||$('#regemail').is(":focus")||$('#regpw').is(":focus")) {
            register();
        }
        if($('#akemail').is(":focus")) {
            posaljiaktivacionikod();
        }
        if($('#zlemail').is(":focus")) {
            posaljizabloz();
        }
    }
});

$('#expand').click(function (e) {
	$('#writermode').fadeIn(300);
	var text = $('#notext').val();
	var title = $('#notitle').val();
	$('#wmtitle').val(title);
	$('#wmtext').val(text);
	$('#wmtext').focus();
});
$('#writermode div.shrink').click(function (e) {
	$('#writermode').fadeOut(300);
    var wmtitle = $('#wmtitle').val();
    var wmtext = $('#wmtext').val();
    
    $('#notitle').val(wmtitle);
    $('#notext').val(wmtext);
    
});

$('#popup div.close').click(function (e) {
	$('#popup').slideUp(300);
    $('body').css('overflow','auto');
    $('#popupcontnent').html("");
});

function switchbtn(oid) {
    var bool = $('#'+oid).attr('bool');
    $('#'+oid).toggleClass('on');
    if (bool==1) {
        $('#'+oid).attr('bool','0');
        console.log(oid);
        console.log("0");
        $.post('/../../core/funcs/jfuncs.php', { action: "switchbtn", val: oid, bool: "0" });
    } else {
        $('#'+oid).attr('bool','1');
        console.log(oid);
        console.log("1");
        $.post('/../../core/funcs/jfuncs.php', { action: "switchbtn", val: oid, bool: "1" });
    }

}

$('div.header div.m-rightmeni').click(function () {
    $('.content div.cright').toggleClass('on');
    $('.m-rightmeni').toggleClass('on');
});

$('div.header div.m-mainmenu').click(function () {
    $('div.header div.meni').toggleClass('on');
});

$('.dropdownoptions').click(function (e) {
	$(this).children('div.options').slideToggle(250);
	$(this).children('div.arrow').toggleClass('selected');
});

$(window).on("scroll", function() {
    var scrollPos = $(window).scrollTop();
    if (scrollPos >= 300) {
        $('#header').addClass('fixed');
        $('.content').css('margin-top','70px');
    } else {
        $('#header').removeClass('fixed');
        $('.content').css('margin-top','0px');
    }    
});