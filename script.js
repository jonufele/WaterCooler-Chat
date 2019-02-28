/**
 * WaterCooler Chat (Javascript file)
 * 
 * @version 1.4
 * @author Joao Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, Joao Ferreira
 */

/*==================================
 #       INDEX                     #
 ===================================
 #   HTTP OBJECT (AJAX)            #
 #   TOPIC                         #
 #   COOKIES                       #
 #   ATTACHMENTS                   #
 #   ROOMS                         #
 #   MESSAGING                     #
 #   DATA RETRIEVAL                #
 #   USER / PROFILES               #
 #   GLOBAL SETTINGS               #
 #   ACCOUNT / CHAT ACCESS         #
 #   UTILITIES / TOOLBAR           #
 #   TOGGLE                        #
 #   POPULATE                      #
 #   HTTP OBJECT (AJAX)            #
 ==================================*/
 
/*==================================
 #       HTTP OBJECT (AJAX)        #
 ==================================*/
 
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
 
/*=================================
 #            TOPIC               #
 =================================*/
 
function wc_lock_topic(c) {
    var http = getHTTPObject();
    http.open("GET", c+"mode=lock_topic", true);
    http.onreadystatechange=function(){if(http.readyState==4){
        if(http.responseText.length > 0) {
            alert(http.responseText);
        } else {
            wc_toggle('wc_topic_con');
            wc_toggle('wc_topic_editbox');
        }
    }}
    http.send(null);
}

function wc_unlock_topic(c) {
    wc_toggle('wc_topic_con');
    wc_toggle('wc_topic_editbox');
    var http = getHTTPObject();
    http.open("GET", c+"mode=unlock_topic", true);
    http.onreadystatechange=function(){if(http.readyState==4){    
    }}
    http.send(null);
}

function wc_upd_topic(c)
{
    var http = getHTTPObject();
    var t = document.getElementById('wc_topic_txt').value;
    http.open("GET", c+"mode=upd_topic&t="+encodeURIComponent(t), true);
    http.onreadystatechange=function(){if(http.readyState==4){
        if(http.responseText.length > 0) {
            document.getElementById('wc_topic').innerHTML = http.responseText;
            wc_send_msg_event(c, 'topic_update', 0);
            wc_refresh_users(1, c, 0, 0, 'ignore_lastmod');
        } else {
            alert('Nothing To Update!');
        }
    }}
     http.send(null);
}

function wc_refresh_topic(c)
{
    var http = getHTTPObject();
    http.open("GET", c+"mode=refresh_topic", true);
    http.onreadystatechange=function(){if(http.readyState==4){
        document.getElementById('wc_topic').innerHTML = http.responseText;
    }}
    http.send(null);
}

/*=================================
 #            COOKIES             #
 =================================*/

function wc_setCookie(cname, cvalue, exdays, prefix) {
    var d = new Date();
    if(exdays != 0) {
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
    } else {
        var expires = "expires=0";
    }
    document.cookie = prefix + '_' + cname + "=" + cvalue + ";" + expires + ";path=/";
}

function wc_getCookie(name, prefix) {
    cookies = document.cookie;
    cname = prefix + '_' + name;
    if(cookies.search(cname + '=') != -1) {
        var par = cookies.replace(' ', '').split(';');
        n = par.length;
        for(i=0; i < n ; i++) {
            if(par[i].search(cname + '=') != -1) {
                return par[i].replace(cname + '=', '');
            }
        }
    }
}

/*=================================
 #         ATTACHMENTS            #
 =================================*/

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

function wc_attach_upl(c, event)
{
    event.preventDefault();

    var icon = document.getElementById('wc_attachment_upl_icon');
    var tmp = icon.src;
    icon.src = document.getElementById('wc_loader_img').src;
    var formData = new FormData();
    formData.append('attach', document.getElementById('wc_attach').files[0], document.getElementById('wc_attach').files[0].value);
     var http = getHTTPObject();
    http.open("POST", c+"mode=attach_upl", true);
    http.onreadystatechange=function(){if(http.readyState==4){
        if(http.responseText.length > 0) {
            if(http.responseText.search('Error') != -1) {
                alert(http.responseText);
            } else {
                wc_bbcode(document.getElementById('wc_text_input_field'), 'wc_text_input', http.responseText, '');
            }
        }
        icon.src = tmp;
        wc_reset_attach_input();
    }}
    http.send(formData);
}

/*=================================
 #             ROOMS              #
 =================================*/

function wc_create_room(c)
{
    var n = document.getElementById('wc_room_name').value;
    var http = getHTTPObject();
    http.open("GET", c+"mode=create_room&n="+encodeURIComponent(n), true);
    http.onreadystatechange=function(){if(http.readyState==4){
        if(http.responseText.search("ERROR") != -1) { alert(wc_parse_error(http.responseText)); } else {
            document.getElementById('wc_room_list').innerHTML = http.responseText;
        }
    }}
     http.send(null);
}

function wc_change_room(c, n, new_conv)
{
    if(new_conv == 1) {
        var conf = confirm('This will initiate a private conversation with this user, are you sure?');
    } else {
        var conf = true;
    }

    if(conf) {
        var http = getHTTPObject();
        http.open("GET", c+"mode=change_room&n="+encodeURIComponent(n), true);
        http.onreadystatechange=function(){if(http.readyState==4){
            if(http.responseText.indexOf('ERROR') == 0) {
                alert(wc_parse_error(http.responseText));
            } else {
                document.getElementById('wc_room_list').innerHTML = http.responseText;
                wc_refresh_msg_once(c, 'ALL', 0);
                wc_refresh_topic(c);
                wc_refresh_users(0, c, 0, 0, 'ignore_lastmod');
            }
        }}
        http.send(null);
    }
}

function wc_refresh_rooms(c, forced)
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
    http.open("GET", c+"mode=refresh_rooms", true);
    http.onreadystatechange=function(){if(http.readyState==4){
        if(http.responseText.length > 0 && open == 0) {
            document.getElementById('wc_room_list').innerHTML = http.responseText;
        }
    }}
    http.send(null);
}

function wc_upd_room(c, par) {

    var formData = new FormData();
    formData.append('oname', document.getElementById('wc_oname_'+par).value);
    formData.append('nname', document.getElementById('wc_nname_'+par).value);
    formData.append('perm', document.getElementById('wc_perm_'+par).value);
    formData.append('rperm', document.getElementById('wc_rperm_'+par).value);
    var http = getHTTPObject();
    http.open("POST", c+"mode=upd_room", true);
    http.onreadystatechange=function(){if(http.readyState==4){
        if(http.responseText.length > 0) { alert(http.responseText); }
        wc_refresh_rooms(c, 'forced');
    }}
     http.send(formData);
}

function wc_del_room(c, par) {

    if(confirm('Are you sure you want to delete this room and all its messages?')) {
        var oname = document.getElementById('wc_oname_'+par).value;
        var http = getHTTPObject();
        http.open("GET", c+"mode=del_room&oname="+encodeURIComponent(oname), true);
        http.onreadystatechange=function(){if(http.readyState==4){
            if(http.responseText.length > 0) {
                if(http.responseText.search('RMV') != -1) {
                    alert(http.responseText.replace('RMV', ''));
                } else {
                    alert(http.responseText);
                }
            }
            wc_refresh_rooms(c, 'forced');
            if(http.responseText.search('RMV') != -1) {
                wc_refresh_topic(c);
                wc_refresh_msg_once(c, 'ALL', 0);
            }
        }}
         http.send(null);
    }
}

/*=================================
 #           MESSAGING            #
 =================================*/

function wc_send_msg(c, refresh_delay, lim)
{
    var http = getHTTPObject();
    var obj = document.getElementById('wc_text_input_field');
    var objDiv = document.getElementById('wc_msg_container');
    var loader = document.getElementById('wc_post_loader');
    loader.className = '';
    var t = obj.value;
    obj.disabled = true;
    var isScrolledToBottom = objDiv.scrollHeight - objDiv.clientHeight <= objDiv.scrollTop + 1;
    http.open("GET", c+"mode=send_msg&t="+encodeURIComponent(t), true);
    http.onreadystatechange=function(){if(http.readyState==4){
        if(http.responseText.length > 0 && http.responseText.search("<div") != -1) {
            var cont = document.createElement("div");
            cont.innerHTML = http.responseText;
            document.getElementById('wc_msg_container').appendChild(cont);
            wc_trim_chat(lim);
        }
        loader.className = 'closed';
        wc_refresh_users(1, c, 0, 0, 'ignore_lastmod');
        if(isScrolledToBottom) { objDiv.scrollTop = objDiv.scrollHeight; }

        obj.value = '';
        obj.disabled = false;
        if(http.responseText.length > 0 && http.responseText.search("<div") == -1) { alert(http.responseText); }
        obj.focus();
    }}
    http.send(null);
}

function wc_send_msg_event(c, t, refresh_delay)
{
    var http = getHTTPObject();
    var objDiv = document.getElementById('wc_msg_container');
    var isScrolledToBottom = objDiv.scrollHeight - objDiv.clientHeight <= objDiv.scrollTop + 1;
    http.open("GET", c+"mode=send_msg_event&t="+encodeURIComponent(t), true);
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

function wc_post(e, c, r, lim)
{
    if(document.getElementById('wc_mline').className == 'closed') {
        if(e.which == 13 || e.keyCode == 13) { wc_send_msg(c, r, lim); }
    }
    if(e.which == 9 || e.keyCode == 9) {
        e.preventDefault();
        var text_input = document.getElementById('wc_text_input_field');
        var http = getHTTPObject();
        http.open("GET", c+"mode=name_autocomplete&hint="+encodeURIComponent(text_input.value), true);
        http.onreadystatechange=function(){if(http.readyState==4){
            if(http.responseText.length > 0) {
                text_input.value = text_input.value + http.responseText;
                text_input.scrollLeft = text_input.scrollWidth;
            }
            text_input.focus();
        }}
         http.send(null);
    }
}

function wc_poste(e, c, id, tag)
{
    if(document.getElementById('wc_sline').className != 'closed') {
        if(e.which == 13 || e.keyCode == 13) {
            e.preventDefault();
            var text_input = document.getElementById('editbox_cont_' + id);
            var loader = document.getElementById('edit_loader_' + id);
            loader.innerHTML = document.getElementById('wc_loader_img_c').innerHTML; 

            var http = getHTTPObject();
            http.open("GET", c+"mode=edit_msg&new_data="+encodeURIComponent(text_input.value)+"&id=" + id + "&tag=" + tag, true);
            http.onreadystatechange=function(){if(http.readyState==4){
                if(http.responseText.length > 0) {
                    if(http.responseText.search('ERROR') == -1) {
                        document.getElementById('js_' + id).innerHTML = http.responseText;
                        setTimeout( function() {document.getElementById('js_' + id).scrollIntoView();}, 500);
                    } else {
                        alert(wc_parse_error(http.responseText));
                        loader.innerHTML = '';
                    }  
                } else {
                     loader.innerHTML = '';
                }
            }}
            http.send(null);
        }
    }
}

/*=================================
 #        DATA RETRIEVAL          #
 =================================*/

function wc_refresh_msg_once(c, all, lim)
{
    var http = getHTTPObject();
    var objDiv = document.getElementById('wc_msg_container');
    if(all == 'ALL') { objDiv.innerHTML = document.getElementById('wc_loader_img_c').innerHTML; }
    var prevpos = objDiv.scrollTop;
    http.open("GET", c+"mode=refresh_msg&all="+all, true);
    http.onreadystatechange=function(){if(http.readyState==4){
        if(http.responseText.length > 0) {
            if(all == 'ALL') {
                var tmp = http.responseText.replace("wc_scroll('0')", "wc_scroll('ALL')");
                document.getElementById('wc_msg_container').innerHTML = tmp.replace("wc_scroll('')", "wc_scroll('ALL')");
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

function wc_refresh_msg_once_event(c)
{
    var http = getHTTPObject();
    var objDiv = document.getElementById('wc_msg_container');
    var prevpos = objDiv.scrollTop;
    http.open("GET", c+"mode=refresh_msg_event", true);
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

function wc_refresh_msg(c, all, refresh_delay, lim, incdir, prefix)
{
    var http = getHTTPObject();
    var prev;
    var objDiv = document.getElementById('wc_msg_container');
    var refresh = refresh_delay;

    if(all == 'ALL') {
        objDiv.innerHTML = document.getElementById('wc_loader_img_c').innerHTML;
    }
    var prevpos = objDiv.scrollTop;
    http.open("GET", c+"mode=refresh_msg&all="+all+"&new_visit=1", true);
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

            wc_refresh_components(c, incdir, lim);

            if(document.cookie.search(prefix + '_idle_refresh=') != -1) {
                var idle_refresh_cookie = wc_getCookie('idle_refresh', prefix);
                if(idle_refresh_cookie >= 1) { refresh = idle_refresh_cookie; }
            } else {
                refresh = refresh_delay;
            }

            if(document.getElementById('wc_msg_container').innerHTML != 'You are banned!') { 
                wc_theLoop();
            }
          }, refresh);
    })();
}

function wc_show_older_msg(c, reset)
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
        var http = getHTTPObject();
        http.open("GET", c+"mode=reset_archive", true);
        http.onreadystatechange=function(){if(http.readyState==4){ 
        }}
         http.send(null);
        older_container.innerHTML = '';
    } else {
        cont = older_container.innerHTML;
        older_container.innerHTML = document.getElementById('wc_loader_img_c').innerHTML + cont;
        var http = getHTTPObject();
        http.open("GET", c+"mode=refresh_msg&n="+encodeURIComponent(first_msg_id), true);
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

function wc_refresh_components(c, incdir, lim) {

    var objDiv = document.getElementById('wc_msg_container');
    var http = getHTTPObject();
    http.open("GET", c+"mode=refresh_components&reload=0&new=0&join=0&all=0&loop=1&ilmod=", true);
    http.onreadystatechange=function(){if(http.readyState==4){
        if(http.responseText.length > 0) {
            arr = http.responseText.split('[$]');

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
                    if(arr2[i].search('$') == -1) {
                        msg = document.getElementById(arr2[i]);
                        if(msg != null) {
                            icon = document.getElementById('wc_icon_' + arr2[i]);
                            if(msg.innerHTML.search('id="hidden_all_') == -1) {
                                msg.innerHTML = '<i><img src="'+incdir+'images/mod.png" class="mod_icon" id="hidden_all_'+arr2[i]+'"> This message is hidden for all users.</i>';
                                if(icon != null && icon.src.search('arrow_r') == -1) {
                                    icon.src = icon.src.replace("arrow", "arrow_r");
                                }
                            }
                        }
                    } else {
                        var parts = arr2[i].split('$');
                        var updated_msg_id = 'updated_note_' + parts[1] + '|' + parts[2];
                        
                        if(document.getElementById(updated_msg_id) != null && parts[0] != arr[6]) {
                            document.getElementById(updated_msg_id).innerHTML = '<div class="updated_msg_note">This message was edited by <i>' + parts[0] + '</i>, <a href="#" onclick="wc_reload_msg(\''+ c +'\', \''+ parts[1] + '|' + parts[2] +'\'); return false;">click here</a> to refresh.</a></div>';
                        }
                    }
                }
            }
    
            if(arr[4].length > 0) {
                var prevpos = objDiv.scrollTop;
                var cont = document.createElement("div");
                cont.innerHTML = arr[4];
                document.getElementById('wc_msg_container').appendChild(cont);
                objDiv.scrollTop = objDiv.scrollHeight;
            }
    
            if(arr[5].length > 0) {
                alert(arr[5]);
            }
    
            if(arr[7].length > 0) {
                var isScrolledToBottom = objDiv.scrollHeight - objDiv.clientHeight <= objDiv.scrollTop + 1;
                if(arr[7].indexOf("RESET") == -1) {
                    if(http.responseText != 'You are banned!') {
                        var cont = document.createElement("div");
                        if(isScrolledToBottom) {
                            var tmp = arr[7].replace("wc_scroll('0')", "wc_scroll('ALL')");
                                cont.innerHTML = tmp.replace("wc_scroll('')", "wc_scroll('ALL')");
                        } else {
                            cont.innerHTML = arr[7];
                        }
                        document.getElementById('wc_msg_container').appendChild(cont);
                    } else {
                        document.getElementById('wc_msg_container').innerHTML = arr[7];
                    }
                } else {
                    alert('The room you were viewing was removed/renamed! We apologize for the inconvenience.');
                    document.getElementById('wc_msg_container').innerHTML = arr[7].slice(5);
                    wc_refresh_topic(c);
                }
                wc_trim_chat(lim);
                if(isScrolledToBottom) { objDiv.scrollTop = objDiv.scrollHeight; }
            }
        }
    }}
     http.send(null);
}

function wc_reload_msg(c, id) {
    var http = getHTTPObject();
    document.getElementById('updated_note_' + id).innerHTML = document.getElementById('wc_loader_img_c').innerHTML;
    http.open("GET", c+"mode=reload_msg&id=" + id, true);
    http.onreadystatechange=function(){if(http.readyState==4){
        if(http.responseText.length > 0) {
            document.getElementById('js_' + id).innerHTML = http.responseText;
        } else {
            document.getElementById('updated_note_' + id).innerHTML = '';
        }
    }}
     http.send(null);
}

/*=================================
 #        USER / PROFILES         #
 =================================*/

function wc_refresh_users(n, c, j, visit, ilmod)
{
    var http = getHTTPObject();
    http.open("GET", c+"mode=refresh_users&new="+n+"&join="+j+"&ilmod="+ilmod+"&visit="+visit, true);
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

function wc_del_user(c, id, event)
{
    event.preventDefault();

    var conf = confirm('Are you sure you want to remove this user?\nThis action cannot be un-done!');

    if(conf) {
         var http = getHTTPObject();
        http.open("GET", c+"mode=del_user&id=" + encodeURIComponent(id), true);
        http.onreadystatechange=function(){if(http.readyState==4){
            if(http.responseText.length > 0) {
                var s = http.responseText;
                if(s.search('successfully') != -1) {
                    wc_toggle('wc_uedt_' + id);
                    wc_refresh_users(0, c, 0, 0, 'ignore_lastmod');
                    if(s.indexOf('RMV') == 0) {
                        s = s.replace('RMV', '');
                        wc_refresh_msg_once(c, 'ALL', 0);
                        wc_refresh_topic(c);
                              }
                }
                alert(s);
            }
        }}
        http.send(null);
    }
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
                wc_refresh_users(0, c, 0, 0, 'ignore_lastmod');
            }
            alert(s);
        }
    }}
    http.send(formData);
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

function wc_upd_settings(c, event)
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

    http.open("POST", c+"mode=upd_settings", true);
    http.onreadystatechange=function(){if(http.readyState==4){
        if(document.getElementById('wc_join').className == 'closed') {
            wc_toggle('wc_text_input');
        }
        if(http.responseText.search("RELOAD_MSG") != -1) {
            wc_refresh_msg_once(c, 'ALL', 0);
        }
        if(http.responseText.search("RESETP_CHECKBOX") != -1) {
            document.getElementById('wc_resetp_elem').className = '';
            document.getElementById('wc_resetp').checked = false;
        } else {
            if(http.responseText.length == 0) {
                document.getElementById('wc_resetp_elem').className = 'closed';
                document.getElementById('wc_resetp').checked = false;
            }
        }
        if(http.responseText.search("RELOAD_PASS_FORM") != -1) {
            if(document.getElementById('wc_text_input').className != 'closed') {
                wc_toggle('wc_text_input');
            }
            if(document.getElementById('wc_settings_icon').className != 'closed') {
                wc_toggle('wc_settings_icon');
            }
            if(document.getElementById('wc_join').className == 'closed') {
                wc_toggle('wc_join');
            }
            wc_get_pass_input(c);
            join_bt.value = join_bt.value.replace('Join Chat', 'Login');
        }
        if(http.responseText.search("NO_ACCESS") == -1) {
            if(http.responseText.length > 0 && http.responseText.indexOf('ERROR') == 0) {
                alert(wc_parse_error(http.responseText));
            } else {
                document.getElementById('wc_settings_input').className = 'closed';
                alert('Settings successfully updated!');
            }
        } else {
            alert('Failed to update settings: access denied!');
        }
        document.getElementById('wc_text_input_field').focus();
    }}
    http.send(formData);
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

/*=================================
 #        GLOBAL SETTINGS         #
 =================================*/

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

/*=================================
 #     ACCOUNT / CHAT ACCESS      #
 =================================*/

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

function wc_join_chat(c, t, refresh_delay)
{
    var passok = 0;
    var pass = document.getElementById('wc_login_pass');
    var input = document.getElementById('wc_text_input_field');
    var sett_icon = document.getElementById('wc_settings_icon');
    var sett_input = document.getElementById('wc_settings_input');
    if(pass !== null) { 
        var http = getHTTPObject();
        http.open("GET", c+"mode=cmp_pass&pass="+encodeURIComponent(pass.value), true);
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
        wc_refresh_users(1, c, 1, 0, 'ignore_lastmod');
        wc_send_msg_event(c, t, refresh_delay);

        wc_toggle('wc_text_input');
        if(sett_icon.className == 'closed') { wc_toggle('wc_settings_icon'); }
        if(sett_input.className != 'closed') { wc_toggle('wc_settings_input'); }
        wc_toggle('wc_join');
        wc_toggle('wc_joined_status_c');
        input.focus();
    }
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

/*=================================
 #      UTILITIES / TOOLBAR       #
 =================================*/

function wc_parse_error(msg) {
    if(msg.indexOf('ERROR: ') == 0) { msg = msg.replace('ERROR: ', '', msg); }
    return msg;
}

function wc_apply_theme(v, prefix, expire_days) {
    wc_setCookie('wc_theme', v, expire_days, prefix);
    location.reload();
}

function wc_clear_screen(c) {

    var http = getHTTPObject();
    http.open("GET", c+"mode=new_start_point", true);
    http.onreadystatechange=function(){if(http.readyState==4){
        wc_refresh_msg_once(c, 'ALL', 0);
    }}
     http.send(null);
}

function wc_undo_clear_screen(c) {

    var http = getHTTPObject();
    http.open("GET", c+"mode=undo_start_point", true);
    http.onreadystatechange=function(){if(http.readyState==4){
        wc_refresh_msg_once(c, 'ALL', 0);
    }}
     http.send(null);
}

function wc_scroll(all)
{
    var objDiv = document.getElementById('wc_msg_container');
    if(all == 'ALL') { objDiv.scrollTop = objDiv.scrollHeight; }
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
                msg[i].id = '';
                msg[i].innerHTML = '';
                msg[i].className = 'closed';
                
            }
        }
    }
}

function wc_bbcode(myField, container, myValue, myValue2) {

    if(document.getElementById(container).className != 'closed') {
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
}

/*=================================
 #            TOGGLE              #
 =================================*/

function wc_toggle_msg(c, id, id_source, priv, prefix) {

    var msg = document.getElementById(id);
    var icon = document.getElementById('wc_icon_' + id);
    var toggle_edit_icon = document.getElementById('wc_toggle_edit_icon');
    var http = getHTTPObject();

    if(document.cookie.search(prefix + '_hide_edit=1') == -1 && toggle_edit_icon != null) {
       if(document.cookie.search(prefix + '_skip_hide_msg=1') == -1 && icon.src.search('arrow_r') == -1) {
           var conf = confirm('You are now under edit mode, this action will hide the message for all users, are you sure?');
           if(conf) { wc_setCookie('skip_hide_msg', 1, 0, prefix); }
       } else {
            conf = true;
       }
    } else {
        var conf = true;
    }
 
    if(conf || icon.src.search('arrow_r') != -1) {
        http.open("GET", c+"mode=toggle_msg&id="+encodeURIComponent(id) + "&id_source=" + id_source + "&private=" + priv, true);
        http.onreadystatechange=function(){if(http.readyState==4){
            if(http.responseText.length > 0) {
                if(http.responseText.indexOf('ERROR') == 0) {
                    alert(wc_parse_error(http.responseText));
                } else {
                    msg.innerHTML = http.responseText;
                    if(icon.src.search("arrow_r") != -1) {
                        icon.src = icon.src.replace("arrow_r", "arrow");
                    } else {
                        icon.src = icon.src.replace("arrow", "arrow_r");
                    }
                }
            }
            wc_refresh_rooms(c, 'forced');
        }}
         http.send(null);
     }
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

function wc_toggle_edit(c, prefix)
{
    var http = getHTTPObject();
    http.open("GET", c+"mode=toggle_edit", true);
    http.onreadystatechange=function(){if(http.readyState==4){
        var boxes = document.getElementsByClassName('edit_bt');
        var class1 = 'edit_bt_off';
        if(boxes.length == 0) {
            var boxes = document.getElementsByClassName('edit_bt_off');
            var class1 = 'edit_bt';
            wc_setCookie('skip_hide_msg', 0, -1, prefix);
        }
        var n = boxes.length;
        for (var i = 0; i < n; i++){
            boxes[0].className = class1;
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

function wc_toggle_post_edit(c, id, tag)
{
    var post = document.getElementById(id);
    var post_edit = document.getElementById('edit_' + id);

    if(post.innerHTML.search('id="hidden_') != -1) {
        alert('Cannot edit hidden messages!');
        return;
    }

    if(post_edit.className.search('closed') != -1) {
        wc_toggle(id); wc_toggle('edit_' + id);
        var http = getHTTPObject();
        post_edit.innerHTML = document.getElementById('wc_loader_img_c').innerHTML;
        http.open("GET", c+"mode=toggle_post_edit&id=" + id + "&tag=" + tag, true);
        http.onreadystatechange=function(){if(http.readyState==4){
            post_edit.innerHTML = http.responseText;
		document.getElementById('js_' + id).scrollIntoView();
        }}
        http.send(null);
    } else {
        wc_toggle(id); wc_toggle('edit_' + id);
    }
}

function wc_toggle(id)
{
    var box1 = document.getElementById(id);
    if(box1 !== null)
    {
        if (box1.className.search('closed') != -1){
            if(box1.className.search(' closed') != -1) {
                box1.className = box1.className.replace(' closed', '');
            } else {
                box1.className = box1.className.replace('closed', '');
            }
        } else {
            if(box1.className.length > 0) {
                box1.className = box1.className + ' closed';
            } else {
                box1.className = 'closed';
            }
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

function wc_toggle_smiley(field, container) {
    if(document.getElementById(container).className != 'closed') {
    wc_toggle('wc_smiley_icon' + field); 
    wc_toggle('wc_smiley_box' + field);
    document.getElementById(field).focus();
    }
}

function wc_toggle_input_line_mode() {
    document.getElementById('wc_text_input_field').id = 'tmp';
    document.getElementById('wc_text_input_field_tmp').id = 'wc_text_input_field';
    document.getElementById('tmp').id = 'wc_text_input_field_tmp';
    wc_toggle('wc_text_input_field');
    wc_toggle('wc_text_input_field_tmp');
    wc_toggle('wc_multi_line_submit');
}

function wc_toggle_msg_cont(target) {
    info = document.getElementById('wc_info');
    gsett = document.getElementById('wc_global_settings');
    msg_cont = document.getElementById('wc_msg_container');
    
    if(target == 'wc_info') {
        if(info.className.search('closed') != -1) {
            if(gsett !== null) {
                if(gsett.className.search('closed') == -1) {
                    wc_toggle('wc_global_settings');
                } else {
                    wc_toggle('wc_msg_container');
                }
            } else {
                wc_toggle('wc_msg_container');
            }
            wc_toggle('wc_info');    
        } else {
            wc_toggle('wc_info');
            wc_toggle('wc_msg_container');
        }
    }
    
    if(target == 'wc_global_settings') {
        if(gsett.className.search('closed') != -1) {
            if(info.className.search('closed') == -1) {
                wc_toggle('wc_info');
            } else {
                wc_toggle('wc_msg_container');
            }
            wc_toggle('wc_global_settings');    
        } else {
            wc_toggle('wc_global_settings');
            wc_toggle('wc_msg_container');
        }
    }
}

/*=================================
 #           POPULATE             #
 =================================*/

function wc_pop_input(text)
{
    var obj = document.getElementById('wc_text_input_field');
    obj.value = text;
    obj.focus();
}

function wc_pop_vid(id, vid, w, h) {
    document.getElementById('wc_video_'+id).innerHTML = '<iframe width="'+w+'" height="'+h+'" src="https://www.youtube.com/embed/'+vid+'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
    wc_toggle('im_'+id);
    wc_toggle('wc_video_'+id);
}