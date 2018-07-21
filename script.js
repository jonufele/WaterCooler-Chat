function getHTTPObject()
{
 var xmlhttp = false;
 try
 {
  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
 {
  try
  {
   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }
  catch (e)
  {
   xmlhttp = false;
   }
  }
 if(!xmlhttp && typeof XMLHttpRequest!='undefined')
 {
  try
  {
    xmlhttp = new XMLHttpRequest();
    }
  catch (e)
  {
    xmlhttp = false;
    }
  }
 if(!xmlhttp && window.createRequest)
 {
  try
  {
    xmlhttp = window.createRequest();
    }
  catch (e)
  {
    xmlhttp = false;
    }
  }
 return xmlhttp;
 }

function wc_reset_attach_input() {

    var oldInput = document.getElementById('wc_attach'); 
    var newInput = document.createElement("input"); 

    newInput.type = "file"; 
    newInput.id = oldInput.id;
    newInput.className = oldInput.className;
    newInput.onchange = oldInput.onchange;
    document.getElementById('wc_attach_cont').innerHTML = '';
    document.getElementById('wc_attach_cont').appendChild(newInput);
}

function wc_attach_test(attach_max) {
	if(document.getElementById('wc_text_input').className != 'closed') {
		obj = document.getElementById('wc_text_input_field');
		var s = obj.value.split('files/attachments/').length -1;
		var s2 = obj.value.split('[attach_').length -1;

		if((s+s2+1) > attach_max && attach_max > 0) {
			alert('Maximum number of attachments per post is: ' + attach_max);
		} else {
			document.getElementById('wc_attach').click();
		}
	}
}

function wc_attach_upl(c, event, incdir)
{
	event.preventDefault();

	var icon = document.getElementById('wc_attachment_upl_icon');
	var tmp = icon.src;
	icon.src = incdir + 'images/loader.gif';
	var formData = new FormData();
	formData.append('attach', document.getElementById('wc_attach').files[0], document.getElementById('wc_attach').files[0].value);
 	var http = getHTTPObject();
	http.open("POST", c+"mode=attach_upl", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0) {
			if(http.responseText.search('Error') != -1) {
				alert(http.responseText);
			} else {
				wc_bbcode(document.getElementById('wc_text_input_field'), http.responseText, '');
			}
		}
		icon.src = tmp;
		wc_reset_attach_input();
	}}
	http.send(formData);
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function apply_theme(v) {
	setCookie('wc_theme', v, 365);
	location.reload();
}

function wc_post(e, c, r, lim)
{
	if(e.which == 13 || e.keyCode == 13) { wc_smsg(c, r, lim); }
	if(e.which == 9 || e.keyCode == 9) {
		e.preventDefault();
		var text_input = document.getElementById('wc_text_input_field');
		var http = getHTTPObject();
		http.open("GET", c+"mode=name_autocomplete&hint="+text_input.value, true);
		http.onreadystatechange=function(){if(http.readyState==4){
			if(http.responseText.length > 0) {
				text_input.value = text_input.value + http.responseText;
			}
			text_input.focus();
		}}
 		http.send(null);
	}
}

function wc_pop_vid(id, w, h) {
	document.getElementById('wc_video_'+id).innerHTML = '<iframe width="'+w+'" height="'+h+'" src="https://www.youtube.com/embed/'+id+'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
	wc_toggle('im_'+id);
	wc_toggle('video_'+id);
}

function wc_showcom() {

	alert("/me <message> - self message\n/ignore <user> - Ignores a user\n/unignore <user> - Unignores user\n/pm <user> <message> - Sends a private message to user\n\nINPUT TRIGGERS\n\nTAB - Hit tab while writing a user name to auto-complete\nPM - Click a user name in posts to auto complete the private message command\n\n(Replace \"<user>\" by the name of the user; [..] denotes an optional parameter.)");
}

function wc_toggle_status(c) {

	var src = document.getElementById('wc_joined_status_c');
	var http = getHTTPObject();
	http.open("GET", c+"mode=toggle_status", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0) {
			if(src.innerHTML.search('_on') != -1) {
				src.innerHTML = src.innerHTML.replace('_on', '_off');
			} else {
				src.innerHTML = src.innerHTML.replace('_off', '_on');
			}
			alert(http.responseText);
		}
	}}
 	http.send(null);
}

function wc_acc_rec(c) {

	document.getElementById('wc_join_rec').disabled = true;
	var http = getHTTPObject();
	http.open("GET", c+"mode=acc_rec", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		alert(http.responseText);
		document.getElementById('wc_join_rec').disabled = false;
	}}
 	http.send(null);
}

function wc_clear_screen(c, incdir) {

	var http = getHTTPObject();
	http.open("GET", c+"mode=new_start_point", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		wc_updmsg_once(c, 'ALL', 0, incdir);
	}}
 	http.send(null);
}

function wc_undo_clear_screen(c, incdir) {

	var http = getHTTPObject();
	http.open("GET", c+"mode=undo_start_point", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		wc_updmsg_once(c, 'ALL', 0, incdir);
	}}
 	http.send(null);
}

function wc_toggle_msg(c, id) {

	var msg = document.getElementById(id);
	var icon = document.getElementById('wc_icon_' + id);
	var http = getHTTPObject();

	http.open("GET", c+"mode=toggle_msg&id="+id, true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0) {
			if(http.responseText == "NO_ACCESS") {
				alert(http.responseText);
			} else {
				msg.innerHTML = http.responseText;
				if(icon.src.search("arrow_r") != -1) {
					icon.src = icon.src.replace("arrow_r", "arrow");
				} else {
					icon.src = icon.src.replace("arrow", "arrow_r");
				}
			}
		}
		wc_refreshrooms(c, 'forced');
	}}
 	http.send(null);
}

function wc_check_hidden_msg(c) {

	var http = getHTTPObject();
	http.open("GET", c+"mode=check_hidden_msg", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0) {
			arr = http.responseText.split(" ");
			for(i = 0 ; i < arr.length ; i++) {
				msg = document.getElementById(arr[i]);
				icon = document.getElementById('wc_icon_' + arr[i]);
				if(msg.innerHTML.search("hidden") == -1) {
					msg.innerHTML = '<i>This message is hidden.</i>';
					if(icon != null) {
						icon.src = icon.src.replace("arrow", "arrow_r");
					}
				}
			}
		}
	}}
 	http.send(null);
}

function wc_update_rname(c, par) {

	var formData = new FormData();
	formData.append('oname', document.getElementById('wc_oname_'+par).value);
	formData.append('nname', document.getElementById('wc_nname_'+par).value);
	formData.append('perm', document.getElementById('wc_perm_'+par).value);
	formData.append('rperm', document.getElementById('wc_rperm_'+par).value);
	var http = getHTTPObject();
	http.open("POST", c+"mode=update_rname", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0) { alert(http.responseText); }
		wc_refreshrooms(c, 'forced');
	}}
 	http.send(formData);
}

function wc_delete_rname(c, par, incdir) {

	if(confirm('Are you sure you want to delete this room and all its messages?')) {
		var oname = document.getElementById('wc_oname_'+par).value;
		var http = getHTTPObject();
		http.open("GET", c+"mode=delete_rname&oname="+oname, true);
		http.onreadystatechange=function(){if(http.readyState==4){
			if(http.responseText.length > 0) {
				if(http.responseText.search('RMV') != -1) {
					alert(http.responseText.replace('RMV', ''));
				} else {
					alert(http.responseText);
				}
			}
			wc_refreshrooms(c, 'forced');
			if(http.responseText.search('RMV') != -1) {
				wc_refreshtopic(c);
				wc_updmsg_once(c, 'ALL', 0, incdir);
			}
		}}
 		http.send(null);
	}
}

function wc_bbcode(myField, myValue, myValue2) {

	if (document.selection) {
		var temp;
		myField.focus();
		sel=document.selection.createRange();
		temp=sel.text.length;
		sel.text=myValue+sel.text+myValue2;
		if (myValue.length==0){
			sel.moveStart('character',myValue.length);
			sel.moveEnd('character',myValue.length);
		} else {
			sel.moveStart('character',-myValue.length+temp);
		}
		sel.select();
	} else if (myField.selectionStart || myField.selectionStart=='0'){
		var currentScroll /*: int*/ = myField.scrollTop;
		var startPos=myField.selectionStart;
		var endPos=myField.selectionEnd;
		myField.value=myField.value.substring(0,startPos)+myValue+myField.value.substring(startPos,endPos)+myValue2+myField.value.substring(endPos,myField.value.length);
		myField.scrollTop = currentScroll;
		//myField.selectionStart=startPos+myValue.length;
		myField.selectionEnd=endPos+myValue.length;
		myField.focus();
	} else {
		myField.value+=myValue;
	}
}

function wc_create_room(c)
{
	var n = document.getElementById('wc_room_name').value;
	var http = getHTTPObject();
	http.open("GET", c+"mode=croom&n="+n, true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.search("exists") != -1) { alert(http.responseText); } else {
			document.getElementById('wc_room_list').innerHTML = http.responseText;
		}
	}}
 	http.send(null);
}

function wc_changeroom(c, n, incdir)
{
	var http = getHTTPObject();
	http.open("GET", c+"mode=changeroom&n="+n, true);
	http.onreadystatechange=function(){if(http.readyState==4){
		document.getElementById('wc_room_list').innerHTML = http.responseText;
		wc_updmsg_once(c, 'ALL', 0, incdir);
		wc_refreshtopic(c);
	}}
	http.send(null);
}


function wc_refreshtopic(c)
{
	var http = getHTTPObject();
	http.open("GET", c+"mode=refreshtopic", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		document.getElementById('wc_topic').innerHTML = http.responseText;
	}}
	http.send(null);
}

function wc_refreshrooms(c, forced)
{
	var open = 0;
	if(forced != 'forced') {
		var croom = document.getElementById('wc_croom_box');
 		if(croom != null) {
			if(croom.className != 'closed') { open = 1; }
		}
		var elems = document.getElementsByClassName('form_box');
		for(i = 0 ; i < elems.length ; i++) {
			if(elems[i].parentNode.className != 'closed') { open = 1; }
		}
	}
 	var http = getHTTPObject();
	http.open("GET", c+"mode=refreshrooms", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0 && open == 0) { document.getElementById('wc_room_list').innerHTML = http.responseText; }
	}}
	http.send(null);
}

function wc_upd_topic(c)
{
	var http = getHTTPObject();
	var t = document.getElementById('wc_topic_txt').value;
	http.open("GET", c+"mode=upd_topic&t="+encodeURIComponent(t), true);
	http.onreadystatechange=function(){if(http.readyState==4){
		document.getElementById('wc_topic').innerHTML = http.responseText;
		wc_smsge(c, 'topic_update', 0);
	}}
 	http.send(null);
}

function wc_reset_av(c)
{
	var reset = document.getElementById('wc_av_reset');
	var http = getHTTPObject();
	http.open("GET", c+"mode=reset_av", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0) {
			alert(http.responseText);
			if(http.responseText.search("successfully") != -1) { reset.className = 'closed'; }
		}
	}}
 	http.send(null);
}

function wc_check_topic_changes(c, reload)
{
	var http = getHTTPObject();
	http.open("GET", c+"mode=check_topic_changes&reload="+reload, true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0 && document.getElementById('wc_topic_editbox').className == 'closed') {
			document.getElementById('wc_topic').innerHTML = http.responseText;
		}
	}}
 	http.send(null);
}

function wc_smsg(c, refresh_delay, lim)
{
	var http = getHTTPObject();
	var obj = document.getElementById('wc_text_input_field');
	var objDiv = document.getElementById('wc_msg_container');
	var loader = document.getElementById('wc_post_loader');
	loader.className = '';
	var t = obj.value;
	obj.disabled = true;
	var isScrolledToBottom = objDiv.scrollHeight - objDiv.clientHeight <= objDiv.scrollTop + 1;
	http.open("GET", c+"mode=smsg&t="+encodeURIComponent(t), true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0 && http.responseText.search("<div") != -1) {
			var cont = document.createElement("div");
			cont.innerHTML = http.responseText;
			document.getElementById('wc_msg_container').appendChild(cont);
			wc_trim_chat(lim);
		}
		loader.className = 'closed';
		wc_updu(1, c, 0, 0, 'ignore_lastmod');
		if(isScrolledToBottom) { objDiv.scrollTop = objDiv.scrollHeight; }

		obj.value = '';
		obj.disabled = false;
		if(http.responseText.length > 0 && http.responseText.search("<div") == -1) { alert(http.responseText); }
		obj.focus();
	}}
	http.send(null);
}

function wc_smsge(c, t, refresh_delay)
{
	var http = getHTTPObject();
	var objDiv = document.getElementById('wc_msg_container');
	var isScrolledToBottom = objDiv.scrollHeight - objDiv.clientHeight <= objDiv.scrollTop + 1;
	http.open("GET", c+"mode=smsge&t="+t, true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0) {
			var cont = document.createElement("div");
			cont.innerHTML = http.responseText;
			document.getElementById('wc_msg_container').appendChild(cont);
		}
		if(isScrolledToBottom) { objDiv.scrollTop = objDiv.scrollHeight; }
	}}
 	http.send(null);
}

function wc_updu(n, c, j, visit, ilmod)
{
	var http = getHTTPObject();
	http.open("GET", c+"mode=updu&new="+n+"&join="+j+"&ilmod="+ilmod+"&visit="+visit, true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(ilmod == 'ignore_lastmod') {
			document.getElementById('wc_ulist').innerHTML = http.responseText;
		} else {
			if(http.responseText.length > 0) { 
				document.getElementById('wc_ulist').innerHTML = http.responseText;
			}
		}
	}}
 	http.send(null);
}

function wc_get_pass_input(c)
{
	var http = getHTTPObject();
	http.open("GET", c+"mode=get_pass_input", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		document.getElementById('wc_pass_input').innerHTML = http.responseText;
	}}
	http.send(null);
}

function wc_upl_avatar(c, event)
{
	event.preventDefault();
	var obj = document.getElementById('wc_avatar_form');
	var tmp = obj.innerHTML;
	var formData = new FormData();
	formData.append('avatar', document.getElementById('wc_avatar').files[0], document.getElementById('wc_avatar').files[0].value);
 	var http = getHTTPObject();
	http.open("POST", c+"mode=upl_avatar", true);
	obj.innerHTML = 'Please wait...';
	http.onreadystatechange=function(){if(http.readyState==4){
		obj.innerHTML = tmp;
		if(http.responseText.length > 0) {
			if(http.responseText.search("successfully") != -1) {
				var res = document.getElementById('wc_av_reset');
				res.className = '';
			}
			alert(http.responseText);
		}
	}}
	http.send(formData);
}

function wc_upd_user(c, id, event)
{
	event.preventDefault();
	var formData = new FormData();
	objs = document.getElementsByClassName('usett_' + id);
	for(i = 0 ; i < objs.length ; i++) {
		if(objs[i].type != 'checkbox') {
			formData.append(objs[i].name, objs[i].value);
		} else {
			if(objs[i].checked) {
				formData.append(objs[i].name, objs[i].value);
			} else {
				formData.append(objs[i].name, '0');
			}
		}
	}
 	var http = getHTTPObject();
	http.open("POST", c+"mode=upd_user", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0) {
			var s = http.responseText;
			if(http.responseText.search('MOD_OFF') != -1) {
				s = s.replace('MOD_OFF', '');
				document.getElementById('wc_mod_' + id).checked = false;	
			}
			if(s.search('Successfully') != -1) {
				wc_toggle('wc_uedt_'+id);
			}
			wc_updu(0, c, 0, 0, 'ignore_lastmod');
			alert(s);
		}
	}}
	http.send(formData);
}

function wc_upd_gsettings(c, event)
{
	event.preventDefault();
	var formData = new FormData();
	objs = document.getElementsByClassName('gsett');
	for(i = 0 ; i < objs.length ; i++) {
		if(objs[i].type != 'checkbox') {
			formData.append(objs[i].id, objs[i].value);
		} else {
			if(objs[i].checked) {
				formData.append(objs[i].id, objs[i].value);
			} else {
				formData.append(objs[i].id, '0');
			}
		}
	}
 	var http = getHTTPObject();
	http.open("POST", c+"mode=upd_gsettings", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0) {
			alert(http.responseText);
		}
	}}
	http.send(formData);
}

function wc_upd_settings(c, event, incdir)
{
	event.preventDefault();
	var formData = new FormData();

	var http = getHTTPObject();
	var email = document.getElementById('wc_email').value;
	var avatar = document.getElementById('wc_avatar').value;
	var web = document.getElementById('wc_web').value;
	var timezone = document.getElementById('wc_timezone').value;
	var hformat = document.getElementById('wc_hformat').value;
	var pass = document.getElementById('wc_pass').value;
	var join_bt = document.getElementById('wc_join_bt');
	var resetp = 0;
	if(document.getElementById('wc_resetp').checked) { resetp = 1; }

	formData.append('email', email);
	formData.append('avatar', avatar);
	formData.append('web', web);
	formData.append('timezone', timezone);
	formData.append('hformat', hformat);
	formData.append('pass', pass);
	formData.append('resetp', resetp);

	http.open("POST", c+"mode=updsett", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		wc_toggle('wc_settings_input');
		if(document.getElementById('wc_join').className == 'closed') {
			wc_toggle('wc_text_input');
		}
		if(http.responseText.search("RELOAD_MSG") != -1) { wc_updmsg_once(c, 'ALL', 0, incdir); }
		if(http.responseText.search("RESETP_CHECKBOX") != -1) {
			document.getElementById('wc_resetp_elem').className = '';
			document.getElementById('wc_resetp').checked = false;
		} else {
			document.getElementById('wc_resetp_elem').className = 'closed';
			document.getElementById('wc_resetp').checked = false;
		}
		if(http.responseText.search("RELOAD_PASS_FORM") != -1) {
			if(document.getElementById('wc_text_input').className != 'closed') { wc_toggle('wc_text_input'); }
			if(document.getElementById('wc_settings_icon').className != 'closed') { wc_toggle('wc_settings_icon'); }
			if(document.getElementById('wc_join').className == 'closed') { wc_toggle('wc_join'); }
			wc_get_pass_input(c);
			join_bt.value = join_bt.value.replace('Join Chat', 'Login');
		}
		if(http.responseText.search("NO_ACCESS") == -1) {
			if(http.responseText.search("INVALID") != -1) {
				document.getElementById('wc_settings_input').className = '';
				alert('Invalid Email/Web');
			} else {
				alert('Settings successfully updated!');
			}
		} else {
			alert('Failed to update settings: access denied!');
		}
		document.getElementById('wc_text_input_field').focus();
	}}
	http.send(formData);
}

function wc_updmsg_once(c, all, lim, incdir)
{
	var http = getHTTPObject();
	var objDiv = document.getElementById('wc_msg_container');
	if(all == 'ALL') { objDiv.innerHTML = '<div style="text-align: center"><img src="'+incdir+'images/loader.gif"></div>'; }
	var prevpos = objDiv.scrollTop;
	http.open("GET", c+"mode=updmsg&all="+all, true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0) {
			if(all == 'ALL') {
				document.getElementById('wc_msg_container').innerHTML = http.responseText;
			} else { 
				var cont = document.createElement("div");
				cont.innerHTML = http.responseText;
				document.getElementById('wc_msg_container').appendChild(cont);
				wc_trim_chat(lim);
			}
			objDiv.scrollTop = objDiv.scrollHeight;
		} else {
			if(all == 'ALL') { document.getElementById('wc_msg_container').innerHTML = http.responseText; }
		}
	}}
 	http.send(null);
}

function wc_updmsg_once_e(c)
{
	var http = getHTTPObject();
	var objDiv = document.getElementById('wc_msg_container');
	var prevpos = objDiv.scrollTop;
	http.open("GET", c+"mode=updmsg_e", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0) {
			var cont = document.createElement("div");
			cont.innerHTML = http.responseText;
			document.getElementById('wc_msg_container').appendChild(cont);
			objDiv.scrollTop = objDiv.scrollHeight;
		}
	}}
 	http.send(null);
}

function wc_doscroll()
{
	var objDiv = document.getElementById('wc_msg_container');
	objDiv.scrollTop = objDiv.scrollHeight;
}

function wc_trim_chat(lim)
{
	var older_container = document.getElementById('wc_older');
	var skip = 0;
	if(older_container != null) {
		if(older_container.innerHTML.length > 0) { skip = 1; }
	}
	if(skip == 0) {
		msg = document.getElementsByClassName('msg_item');
		n = msg.length;
		if(n >= lim) {
			for(i = 0 ; i < (n-lim); i++) {
				msg[i].className = 'closed';
			}
		}
	}
}

function wc_update_components(c) {

	var http = getHTTPObject();
	http.open("GET", c+"mode=update_components&reload=0&new=0&join=0&ilmod=", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		arr = http.responseText.split("[$]");

		var open = 0;
		var elems = document.getElementsByClassName('form_box');
		for(i = 0 ; i < elems.length ; i++) {
			if(elems[i].parentNode.className != 'closed') { open = 1; }
		}

		if(arr[0].length > 0 && open == 0) { 
			document.getElementById('wc_ulist').innerHTML = arr[0];
		}

		if(arr[1].length > 0 && document.getElementById('wc_topic_editbox').className == 'closed') {
			document.getElementById('wc_topic').innerHTML = arr[1];
		}


		var croom = document.getElementById('wc_croom_box');
 		if(croom != null) {
			if(croom.className != 'closed') { open = 1; }
		}

		if(arr[2].length > 0 && open == 0) { document.getElementById('wc_room_list').innerHTML = arr[2]; }

		if(arr[3].length > 0) {
			var arr2 = arr[3].split(" ");
			for(i = 0 ; i < arr2.length ; i++) {
				msg = document.getElementById(arr2[i]);
				if(msg != null) {
					icon = document.getElementById('wc_icon_' + arr2[i]);
					if(msg.innerHTML.search("hidden") == -1) {
						msg.innerHTML = '<i>This message is hidden.</i>';
						if(icon != null) {
							icon.src = icon.src.replace("arrow", "arrow_r");
						}
					}
				}
			}
		}

		if(arr[4].length > 0) {
			var objDiv = document.getElementById('wc_msg_container');
			var prevpos = objDiv.scrollTop;
			var cont = document.createElement("div");
			cont.innerHTML = arr[4];
			document.getElementById('wc_msg_container').appendChild(cont);
			objDiv.scrollTop = objDiv.scrollHeight;
		}

		if(arr[5].length > 0) {
			alert(arr[5]);
		}
	}}
 	http.send(null);
}

function wc_updmsg(c, all, refresh_delay, lim, incdir)
{
	var http = getHTTPObject();
	var prev;
	var objDiv = document.getElementById('wc_msg_container');
	if(all == 'ALL') {
		objDiv.innerHTML = '<div style="text-align: center"><img src="'+incdir+'images/loader.gif"></div>';
	}
	var prevpos = objDiv.scrollTop;
	http.open("GET", c+"mode=updmsg&all="+all, true);
	http.onreadystatechange=function(){if(http.readyState==4){
		if(http.responseText.length > 0) {
			document.getElementById('wc_msg_container').innerHTML = http.responseText;
			objDiv.scrollTop = objDiv.scrollHeight;
		} else {
			objDiv.innerHTML = '';
		}
	}}
 	http.send(null);

	(function wc_theLoop () {
  		setTimeout(function wc_() {
			http.open("GET", c+"mode=updmsg&all=0", true);
			http.onreadystatechange=function(){if(http.readyState==4){
				if(http.responseText.length > 0) {
					var isScrolledToBottom = objDiv.scrollHeight - objDiv.clientHeight <= objDiv.scrollTop + 1;
					if(http.responseText.indexOf("RESET") == -1) {
						if(http.responseText != 'You are banned!') {
							var cont = document.createElement("div");
							cont.innerHTML = http.responseText;
							document.getElementById('wc_msg_container').appendChild(cont);
						} else {
							document.getElementById('wc_msg_container').innerHTML = http.responseText;
						}
					} else {
						alert('The room you were viewing was removed/renamed! We apologize for the inconvenience.');
						document.getElementById('wc_msg_container').innerHTML = http.responseText.slice(5);
						wc_refreshtopic(c);
					}
					wc_trim_chat(lim);
					if(isScrolledToBottom) { objDiv.scrollTop = objDiv.scrollHeight; }
				} 
			}}
 			http.send(null); wc_update_components(c);

			if(document.getElementById('wc_msg_container').innerHTML != 'You are banned!') { wc_theLoop(); }

 	 	}, refresh_delay);
	})();
}

function wc_toggle(id)
{
	var box1 = document.getElementById(id);
	if(typeof box1 !== 'undefined')
	{
		if (box1.className == 'closed'){
			box1.className = "";
		} else {
			box1.className = "closed";
		}
	}
}

function wc_multi_toggle(id)
{
	var boxes = document.getElementsByClassName(id);
	for (var i = 0; i < boxes.length; i++){
		if (boxes[i].style.display == 'none'){
			boxes[i].style.display = "inline";
		} else {
			boxes[i].style.display = "none";
		}
	}
}

function wc_pop_input(text)
{
	var obj = document.getElementById('wc_text_input_field');
	obj.value = text;
	obj.focus();
}

function wc_toggle_time(c)
{
	var http = getHTTPObject();
	http.open("GET", c+"mode=toggle_time", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		wc_multi_toggle('timestamp');
	}}
 	http.send(null);
}

function wc_toggle_edit(c)
{
	var http = getHTTPObject();
	http.open("GET", c+"mode=toggle_edit", true);
	http.onreadystatechange=function(){if(http.readyState==4){
		var boxes = document.getElementsByClassName('edit_bt');
		var class1 = 'edit_bt_off';
		if(boxes.length == 0) {
			var boxes = document.getElementsByClassName('edit_bt_off');
			var class1 = 'edit_bt';
		}
		var n = boxes.length;
		for (var i = 0; i < n; i++){
			boxes[0].className = class1;
		}
		var boxes2 = document.getElementsByClassName('hide_icon');
		var class2 = 'hide_icon_off';
		if(boxes2.length == 0) {
			var boxes2 = document.getElementsByClassName('hide_icon_off');
			var class2 = 'hide_icon';
		}
		var n2 = boxes2.length;
		for (var i = 0; i < n2; i++){
			boxes2[0].className = class2;
		}
		var create_room_link = document.getElementById('wc_create_link');
		var class3 = 'create_link_off';
		if(create_room_link.className == 'create_link_off') {
			var class3 = 'create_link';
		}
		create_room_link.className = class3;
	}}
 	http.send(null);
}

function wc_show_older_msg(c, reset, incdir)
{
	var msg = document.getElementsByClassName('msg_item');
	var n = msg.length;
	var first_msg_id = '';
	if(n > 0) {
		first_msg_id = msg[0].id;
	} else {
		first_msg_id = 'beginning';
	}
	var older_container = document.getElementById('wc_older');
	if(reset == '1') {
		older_container.innerHTML = '';
	} else {
		cont = older_container.innerHTML;
		older_container.innerHTML = '<div style="text-align: center"><img src="'+incdir+'images/loader.gif"></div>' + cont;
		var http = getHTTPObject();
		http.open("GET", c+"mode=updmsg&n="+first_msg_id, true);
		http.onreadystatechange=function(){if(http.readyState==4){ 
			if(http.responseText.length > 0) {
				older_container.innerHTML = http.responseText + cont;
			} else {
				older_container.innerHTML = cont;
			}
		}}
 		http.send(null);
	}
}

function wc_joinchat(c, t, refresh_delay)
{
	var passok = 0;
	var pass = document.getElementById('wc_login_pass');
	var input = document.getElementById('wc_text_input_field');
	var sett_icon = document.getElementById('wc_settings_icon');
	var sett_input = document.getElementById('wc_settings_input');
	if(pass !== null) { 
		var http = getHTTPObject();
		http.open("GET", c+"mode=cmppass&pass="+pass.value, true);
		http.onreadystatechange=function(){if(http.readyState==4){
			passok = http.responseText;
			if(passok == 1) {
				location.reload();
			} else {
				document.getElementById('wc_pass_err').innerHTML = 'Invalid Password!';
			}
		}}
 		http.send(null);
	} else {
		wc_updu(1, c, 1, 0, 'ignore_lastmod');
		wc_smsge(c, t, refresh_delay);
		wc_check_topic_changes(c, 1);

		wc_toggle('wc_text_input');
		if(sett_icon.className == 'closed') { wc_toggle('wc_settings_icon'); }
		if(sett_input.className != 'closed') { wc_toggle('wc_settings_input'); }
		wc_toggle('wc_join');
		wc_toggle('wc_joined_status_c');
		input.focus();
	}
}