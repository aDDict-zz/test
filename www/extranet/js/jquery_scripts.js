img_preview = function (img) {
    imghtml = $("<img/>").attr("src",img).attr('class','vishid');
    options = {
        containerCss: {
            backgroundColor:"#ffffff"
        }
    };
    $(imghtml).modal(options);
}

img_tag_paste = function (img,width,height) {
    $myField = document.getElementById('textarea_html');
    myValue = '<img src="'+img+'" alt="" width="'+width+'" height="'+height+'">';
    //IE support
    if (document.selection) {
        $myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    //MOZILLA/NETSCAPE support
    else if ($myField.selectionStart || $myField.selectionStart == '0') {
        var startPos = $myField.selectionStart;
        var endPos = $myField.selectionEnd;
        if (endPos == $myField.value.length) {
            $.ajax({
                url: "ajax.php",
                data: { func : 'show_word', key : 'will_insert_to_end' },
                type: 'post',
                success: function(resp) {
                    if (confirm (resp)) {
                        $myField.value = $myField.value.substring(0, startPos) + myValue + $myField.value.substring(endPos, $myField.value.length);
                    }
                }
            });
        }
        else {
            $myField.value = $myField.value.substring(0, startPos) + myValue + $myField.value.substring(endPos, $myField.value.length);
        }
    } else {
        $myField.value += myValue;
    }
}

delete_uploaded = function (fid,obj,confirmtext,sessionlastid) {
    if (confirm(confirmtext)) {
        $.ajax({
            url: 'ajax.php',
            data: { func : 'delete_uploaded', id : fid, sessionlastid : sessionlastid },
            type: 'post',
            success: function(resp) {
                if (resp=="ok") {
                    obj.parent().slideUp();
                }
                else {
                    alert (resp);
                }
            }
        });
    }
    return false;
}

load_subject = function () {
    base_id = $("select[name=base_id] option:selected").val();
    if (base_id!='x') {
        $.getJSON(
            "ajax.php",
            { func: 'load_subject', base_id: base_id },
            function (resp) {
                $("input[name=subject]").val(resp.subject);
                $("input[name=emphasized]").val(resp.emphasized);
            }
        );
    }
    else {
        $("input[name=subject]").val("");
        $("input[name=emphasized]").val("");
    }
}

load_base = function () {
    group_id = $("select[name=mod_group_id] option:selected").val();
    
    $("#loading_filter_id").removeClass("none");
    if (group_id!='x') {
        $.getJSON(
            "ajax.php",
            { func : 'load_base_list', group_id : group_id },
            function (resp) {
                options = '<option value="x">----</option>';
                $.each(resp,function(k,v) {
                    options += '<option value="'+k+'">'+v+'</option>';
                });
                $("select[name=filter_id]").html(options);
                $("#loading_filter_id").addClass("none");
                $("input[name=group_id]").val(group_id);
            }
        );
        $.getJSON(
            "ajax.php",
            { func : 'load_base_user_list', group_id : group_id },
            function (resp) {
                options = '<option value="x">----</option>';
                $.each(resp,function(k,v) {
                    options += '<option value="'+k+'">'+v+'</option>';
                });
                $("select[name=sender_id]").html(options);
                $("#loading_sender_id").addClass("none");
                $("input[name=group_id]").val(group_id);
            }
        );
    }
    else {
        options = '<option value="x">----</option>';
        $("select[name=filter_id]").html(options);
        $("#loading_filter_id").addClass("none");
        $("select[name=sender_id]").html(options);
        $("#loading_sender_id").addClass("none");
    }
    
    //$("input[name=fix_group_id]").val(group_id);
}

checkFileUpload = function(field, depend_field, depend_value) {
    var depend_field = depend_field || "";
    var depend_value = depend_value || "";
    if ($("input[name="+field+"]").val() == "") {
        if ((depend_field!="" && $("input[name="+depend_field+"]:checked").val() == depend_value) || depend_field=="") {
            alert(no_file_selected);
            return false;
        }
    }
    return true;
}

function SetCookie(cookieName,cookieValue,nDays) {
 var today = new Date();
 var expire = new Date();
 if (nDays==null || nDays==0) nDays=1;
 expire.setTime(today.getTime() + 3600000*24*nDays);
 document.cookie = cookieName+"="+escape(cookieValue)
                 + ";expires="+expire.toGMTString();
}

//sender_timer_ch: if send type is 'now', show a confirm popup
confirmSendNow = function() {
    if ($("select[name=stype] option:selected").val()=="now" && !confirm('AZONNALI küldést választottál, ha most továbbmegyünk, a küldés AZONNAL elindul!')) {
        return false;
    }
    return true;
}

$(document).ready(function() {
    //--- ALERT IF NO GROUP IS SELECTED ----------------------------------------------------
    if (nogroup) alert(nogroup_message);
    //--- END OF ALERT IF NO GROUP IS SELECTED ----------------------------------------------------
    //--- PRINT PAGE -----------------------------------------------------------------------
    $("#print_page").click(function(){
        window.print();    
    });
    //--- END OF PRINT PAGE -----------------------------------------------------------------------
    //--- SHOW/HIDE SUBMENU -----------------------------------------------------------------------
    var donthide = 0;
    var fromsmenu = 0;
    var frommmenu = 0;
    var pgid = 0;
    $("[id^=mmenu],div[id^=smenu],div[id^=smenu] > a").mouseover(function(){
        var id = $(this).attr('id');
        var idt = $(this).attr('id').match(/\d+$/);
        if (id == 'mmenu'+idt) {
            var l = $("#mmenu"+idt).offset().left;
            $('div#smenu'+idt).css('left',l+'px');
        }
        if (pgid != idt && pgid != 0 && id == 'mmenu'+idt && fromsmenu == 0) {
            //$('div[id^=smenu]').fadeOut('fast');
            $('div[id^=smenu]').hide();
        }
        pgid = idt;
        if ($("#smenu"+pgid).is(':hidden')) {
            //$("#mmenu"+pgid+" ~ div").fadeIn('fast');
            $("#mmenu"+pgid+" ~ div").show();
        }
        fromsmenu = 0;
        frommmenu = 0;
        donthide = 1;        
    });

    $("[id^=mmenu]").mouseout(function(){
        frommmenu = 1;
        donthide = 0;
        pgid = $(this).attr('id').match(/\d+$/);
        //setTimeout(function(n){if(fromsmenu != 1 && donthide != 1){$('div[id^=smenu]').fadeOut('fast');frommmenu=0}},500);
        setTimeout(function(n){if(fromsmenu != 1 && donthide != 1){$('div[id^=smenu]').hide();frommmenu=0}},500);
    });

    $("div[id^=smenu]").mouseout(function(){
        fromsmenu = 1;
        donthide = 0;
        pgid = $(this).attr('id').match(/\d+$/);
        //setTimeout(function(n){if(frommmenu != 1 && donthide != 1){$('div[id^=smenu]').fadeOut('fast');fromsmenu=0}},500);
        setTimeout(function(n){if(frommmenu != 1 && donthide != 1){$('div[id^=smenu]').hide();fromsmenu=0}},1);
    }); 
    //--- END OF SHOW/HIDE SUBMENU -----------------------------------------------------------------------
    
    //--- SHOW/HIDE SENDING PROCESSES -------------------------------------------------------------------
    $("#autoopen").change(function(){
        var set = 0;
        if ($("#autoopen").is(":checked")) set = 1;
        $.ajax ({
            type: 'POST',
            data: 'func=proclist_autoopen&set='+set,
            url: baseUrl+'/ajax.php',
            success: function (result) {}
        });
    });

    $("#show_proclist").click(function(){
        if ($("#proclist").attr('class') == "vishid") {
            if ($("#proc_count").html() != 0) {
                $.ajax ({
                    type: 'POST',
                    data: 'func=get_proc_list&auid='+auid,
                    url: baseUrl+'/ajax.php',
                    success: function (result) {
                                $("a#show_proclist").html('Lista bezárása');
                                $("#proclist > div").html(result);
                                $("#proclist > div").fadeIn('fast');
                                $("#proclist").attr('class','');
                                $("#proclist").fadeIn("slow");                            
                                var h = $("#proclist > div").height();
                                $("#proclist").animate({height:h+"px"}, 'fast', function(){});
                   }
                });
            }
        }
        else {
            $("a#show_proclist").html('Lista lenyitása');
            $("#proclist").animate({height:"0px"}, 'fast', function(){
                $("#proclist").attr('class','vishid');
                $("#proclist > div").attr('style','');
                $("#proclist > div").fadeOut('fast');
                $("#proclist > div").html("");});
        }
    });

    $("a[id^=stop_proc_]").live("click",function(){
        var id = $(this).attr("id");
        var mid = $(this).attr("id").match(/\d+$/);
        $.ajax ({
            type: 'POST',
            data: 'func=stop_send_proc&auid='+auid+'&mid='+mid+'&stop_sending=yes',
            url: baseUrl+'/ajax.php',
            success: function (result) {
                $("span#stop_send_status_"+mid).html('<span class="headlinestopped">[Leállítva]</span>');
            }
        });
    });
    
    //--- AUTO REFRESH SENDING PROCESSES -------------------------------------------------------------------------------
    setInterval(function(){
        if($("#proclist > div").html() != "") {
            //refresh the whole list
            $.ajax ({
                type: 'POST',
                data: 'func=get_proc_list&auid='+auid,
                url: baseUrl+'/ajax.php',
                success: function (result) {
                            $("#proclist > div").html(result);
                         }
            });
        }
        
        //refresh general data only (process count and percent)
        $.ajax ({
            type: 'POST',
            data: 'func=get_proc_data&auid='+auid,
            url: baseUrl+'/ajax.php',
            success: function (result) {
                        var res = result.split('_');
                        $("#proc_count").html(res[0]);
                        $("#proc_percent").html(res[1]);
                     }
        });
    },10000);
    //--- END OF AUTO REFRESH PROCESSES -------------------------------------------------------------------------------

    //--- END OF SHOW/HIDE SENDING PROCESSES -------------------------------------------------------------------
    
    //--- OPEN HELP LAYER FOR SUPERADMIN -------------------------------------------------------------------
	$('a#open_help').click(function (e) {
		e.preventDefault();
		$('#basic-modal-content').modal();
	});
    //--- END OF OPEN HELP LAYER FOR SUPERADMIN -------------------------------------------------------------------
    
    //--- SAVE HELP TEXT --------------------------------------------------------------------------
    $("input[id^=save_help]").click(function(){
        var id = $(this).attr('id').split('_');
        var pid = $("input#pid").val();
        var lng = id[2];
        var txt = $("#rte_"+lng).val();
        //alert(txt);
        $.ajax ({
            type: 'POST',
            data: 'func=save_help&pid='+pid+'&lng='+lng+'&txt='+txt,
            url: baseUrl+'/ajax.php',
            success: function (result) {
                var res = result.split('_');
                if (res[1] == 'ok') {
                    $("#save_fail_"+res[0]).hide();
                    $("#save_success_"+res[0]).fadeIn("slow",function(){$("#save_success_"+res[0]).fadeOut("slow")});
                }
                else if (res[1] == 'fail') {
                    $("#save_success_"+res[0]).hide();
                    $("#save_fail_"+res[0]).fadeIn("fast")
                }
            }
        });         
    });
    //--- END OF SAVE HELP TEXT --------------------------------------------------------------------------
    
    //--- GET HELP TEXT --------------------------------------------------------------------------
    $("#get_help").click(function() {
        $("#nohelp").hide();
        var noedit = $("#noedittext").val();
        if (noedit == 0) {
            var pid = $("input#pid").val();
            var lng = '';
            var lngcount = $("input#lng_count").val();
            $("textarea[id^=rte]").each(function(n) {
                var id = $(this).attr('id').split("_");
                var lng = id[1];

                $.getJSON(baseUrl+'/ajax.php?func=get_help&pid='+pid+'&lng='+lng,
                    function(data){
                        $.each(data, function(lng,item) {
                            if (item != 'empty') {
                                $("textarea#rte_"+lng).val(item); 
                            }
                            $('textarea#rte_'+lng).wysiwyg();
                            $('iframe[id^=rte]').attr('style','height: 100px; width: 573px;');
                        });
                });    
            });
        }
        else {
            var pid = $("#pid").val();
            var lng = $("#lng").val();
            $.getJSON(baseUrl+'/ajax.php?func=get_help&pid='+pid+'&lng='+lng,
                function(data){
                    $.each(data, function(lng,item) {
                        if (item == 'empty') {
                            $("#nohelp").show(); 
                        }
                        else {
                            $("#nohelp").hide();
                            $("#basic-modal-content div#help").html(item);
                        }
                    });
            });            
        }
    });
    //--- END OF GET HELP TEXT --------------------------------------------------------------------------

    //--- AUTOMATIC AJAX UPLOAD
    upload_btns = ['upload_image','upload_attachment','upload_text_plain','upload_text_html'];
    $.each(upload_btns, function(i,upload_btn) {
        if ($('#'+upload_btn).length>0) {
            $ajaxupload = new AjaxUpload('#'+upload_btn, {
                action: 'ajax.php',
                name: upload_btn,
                data: {
                    func: 'ajaxupload',
                    field: upload_btn
                },
                autoSubmit: true,
                responseType: false,
                onChange: function(file, extension) {},
                onSubmit: function(file, extension) {
                    $("span#loading_"+upload_btn).removeClass('none');
                },
                onComplete: function(file, response) {
                    if (/^upload_text_/.test(upload_btn)) {
                        fieldname = upload_btn.replace(/^upload_text_/,'');
                        $field = $("#textarea_"+fieldname);
                        $field.val(response);
                    }
                    else {
                        if (!(/\.(zip|rar)$/.test(file))) {
                            $("div#uploaded_"+upload_btn).removeClass('none').html($("div#uploaded_"+upload_btn).html() + response + '<br>');
                        }
                        else {
                            if (!(/\.(jpg|png|jpeg|gif)$/.test(file))) {
                                $("div#uploaded_upload_attachment").removeClass('none').html($("div#uploaded_upload_attachment").html() + response + '<br>');
                            }
                            else {
                                $("div#uploaded_"+upload_btn).removeClass('none').html($("div#uploaded_"+upload_btn).html() + response + '<br>');
                            }
                        }
                    }
                    $("span#loading_"+upload_btn).addClass('none');
                }
            });
        }
    });
    //--- END OF AUTOMATIC AJAX UPLOAD
});

/*function resize_modal() {
    alert('stop');
    var w = $("#basic-modal-content").width()+30;
    var h = $("#basic-modal-content").height()+30;
    $("#simplemodal-container").animate({width : w+'px',height : h+'px'},10);
    $("#simplemodal-container").css("top", ( $(window).height() - h ) / 2+$(window).scrollTop() + "px");
    $("#simplemodal-container").css("left", ( $(window).width() - w ) / 2+$(window).scrollLeft() + "px");
}*/
