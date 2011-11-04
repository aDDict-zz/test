var mxe=new Array();
var mxbox=new Array();
var mxpage=new Array();
var mxlastpage=-1;
var mxlastbox='0';
var ordnum=0;
var widget_ref=new Object();
var cookie_ref=new Object();
var dstart = 0;
var filled_num = 0;
var cid=0;
var tmp_ch_sel="";
var pointers=new Object();
var force_preview=typeof(mx_force_preview)!='undefined';
var cluster=0;
var cluster_size=150;
var bg_tds=new Array();
var block_navigation = 0;




var kuttnshera_p8_collector = [], kuttnshera_p9_collector = [], bit = 0, p8_counter = 0, p9_counter = 0, mx_page_seq_spec = [], mx_page_seq = [], pointer;

function in_array(p_val) {
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i] == p_val) {
			return true;
		}
	}
	return false;
}
Array.prototype.in_array=in_array;
function z(s) { document.write(s); }

function mx_page_in_cluster(pg) {
    if (pg>=cluster_first_page && pg<=cluster_last_page) {
        return true;
    }
    return false;
}
function mx_init() {




  if(mx_maxima=='mxform316'){
    var arr = [];
    for(var i = 0; i < 8; i++) {
      mx_page_seq_spec.push(i);
    }
    for(var i = 8; i < 18; i++) {
      bit = Math.floor(Math.random()*2);
      if(bit == 0)
        arr.unshift(i);
      else
        arr.push(i);
    }
    for(var i = 0; i < 10;i++) {
      mx_page_seq_spec.push(arr[i]);
    }

    var arr = [];
    for(var i = 18; i < 31; i++) {
      bit = Math.floor(Math.random()*2);
      if(bit == 0)
        arr.unshift(i);
      else
        arr.push(i);
    }
    for(var i = 0; i < 13;i++) {
      mx_page_seq_spec.push(arr[i]);
    }
  }





	if (force_preview) {
		cluster_size=999;
	}
    var d=new Date();
    dstart=d.getTime()/1000;
    z("<form name='mxf' method='post' action='"+cpage+"'>\n<input type='hidden' name='__psv__' value=''>\n<input type='hidden' name='__mn__' value=''>\n<input type='hidden' name='data__' value='"+data+"'>\n<div id='framediv' class='fd'>\n");
    mx_getcookie();
    if (typeof(cookie_ref['__dstart__'])!='undefined') {
        dstart=cookie_ref['__dstart__'];
    }
    if (typeof(cookie_ref['__cluster__'])!='undefined') {
        cluster=cookie_ref['__cluster__'];
    }
	var max_cluster=Math.floor(mx_pagenum/cluster_size);
	if (cluster>max_cluster) {
		cluster=max_cluster;
	}
    cluster_first_page=cluster*cluster_size+1;
    cluster_last_page=(cluster*1+1)*cluster_size;
    if (!force_preview) {
        mx_page=cluster_first_page;
    }
    if (cluster_last_page>=mx_pagenum) {
        cluster_last_page=mx_pagenum;
    }
    mx_widgets();
    var firstrot = 0;
    for (i=0;i<mxe.length;i++) {
        // if (mx_page_in_cluster(mxe[i].page) || mxe[i].type=='hidden') {
        var widind = i;
        if (mx_maxima=='mxform282' && mxe[i].id.search(new RegExp("^sakkomkekpont_13_([0-9])","gi"))>=0) {
            if (RegExp.$1 == 1) {
                firstrot = i;
            }
            if (firstrot && cid.search(new RegExp("([0-9])[^0-9]*$","gi"))>=0) {
                var wshift = RegExp.$1 % 5;
                widind += wshift;
                if (widind >= firstrot + 5) {
                    widind -= 5;
                }
            }
        }
        if (mx_maxima=='mxform285' && mxe[i].id.search(new RegExp("^kuttnsicoregon_q_m_36_([0-9])","gi"))>=0) {
            if (RegExp.$1 == 1) {
                firstrot = i;
            }
            if (firstrot && cid.search(new RegExp("([0-9])[^0-9]*([0-9])[^0-9]*$","gi"))>=0) {
                var wshift = (RegExp.$1+RegExp.$2)%8;
                widind += wshift;
                if (widind >= firstrot + 8) {
                    widind -= 8;
                }
            }
        }

//        if (mx_maxima=='mxform316' && mxe[i].id.search(new RegExp("^kuttnshera_p8_([0-9])","gi"))>=0) {
//
//          bit = Math.floor(Math.random()*2);
//
//          if(bit == 0)
//            kuttnshera_p8_collector.unshift(i);
//          else if(bit == 1)
//            kuttnshera_p8_collector.push(i);
//
//          p8_counter++;
//          widind = -1;
//        }
//
//        if(p8_counter == 10 && typeof p8_listener == "undefined") {
//          var p8_listener = 1;
//          for(var c = 0, len = kuttnshera_p8_collector.length; c < len; c++) {
//            mx_create(mxe[ kuttnshera_p8_collector[c] ],0);
//            mx_page_seq_spec.push( kuttnshera_p8_collector[c] );
//          }
//        }
//
//        if (mx_maxima=='mxform316' && mxe[i].id.search(new RegExp("^kuttnshera_p9_([0-9])","gi"))>=0) {
//
//          bit = Math.floor(Math.random()*2);
//
//          if(bit == 0)
//            kuttnshera_p9_collector.unshift(i);
//          else if(bit == 1)
//            kuttnshera_p9_collector.push(i);
//
//          p9_counter++;
//
//          widind = -1;
//        }
//
//        if(p9_counter == 13 && typeof p9_listener == "undefined") {
//          var p9_listener = 1;
//          for(var r = 0, len = kuttnshera_p9_collector.length; r < len; r++) {
//            mx_create(mxe[ kuttnshera_p9_collector[r] ],0);
//            mx_page_seq_spec.push( kuttnshera_p9_collector[r] );
//          }
//        }

        if(widind != -1) {
//          mx_page_seq_spec.push( widind );
          mx_create(mxe[widind],0);
        }
    }

    if (mxlastbox!='0') {
        mx_end_box(mxlastpage,mxlastbox);
    }
    for (i=cluster_first_page;i<=cluster_last_page;i++) {
        z("<textarea style='width:450px; height:100px;' id='tcom_"+i+"' name='tcom_"+i+"'></textarea>");
		if (i==1) {
			z("<div class='mxpage' style='border:0px;' id='nav"+i+"'><table class='mx' width='95%' cellpadding='0' cellspacing='5' border='0'><tr><td style='text-align:center;background-color:transparent;width:100%;' id='navr"+i+"'></td></tr></table></div>\n");
		}
		else {
			z("<div class='mxpage' style='border:0px;' id='nav"+i+"'><table width='100%' cellpadding='0' cellspacing='5' border='0'><tr><td class='mxl' style='text-align:right;border:0;background-color:transparent;width:50%;' id='navl"+i+"'></td><td class='mxr' style='text-align:left;border:0;background-color:transparent;width:50%;' id='navr"+i+"'></td></tr></table></div>\n");
		}
    }
    z("</div>\n</form>\n");
    mxf=document.mxf;
    mx_load_defaults();
    if (mx_maxima=='mxform285') {  // special page sequence for this form
        ceo=0;
        if (cid.search(new RegExp("([0-9])[^0-9]*$","gi"))>=0) {
            ceo = (RegExp.$1)%2;
        }
        if (ceo == 1) {
            mx_page_seq = new Array();
            for (var is=1;is<=mx_pagenum;is++) {
                var seq=is;
                if (is == 85) {seq = 87; }
                if (is == 86) {seq = 88; }
                if (is == 87) {seq = 85; }
                if (is == 88) {seq = 86; }
                mx_page_seq.push(seq);
            }
            //alert(mx_page_seq.join(',').substr(90));
        }
    }
    if (mx_maxima=='mxform286') {  // special page sequence for this form
        ceo=0;
        if (cid.search(new RegExp("([0-9])[^0-9]*([0-9])[^0-9]*([0-9])[^0-9]*$","gi"))>=0) {
            ceo = (RegExp.$1)%5;
        }
        mx_page_seq = new Array();
        for (var is=1;is<=mx_pagenum;is++) {
            var seq=is;
            if (is>=31 && is<36) {
                seq += ceo;
                if (seq>35) {
                    seq = seq-5;
                }
            }
            mx_page_seq.push(seq);
        }
        //alert(mx_page_seq.join(','));
    }
    if (mx_maxima=='mxform261') {  // special page sequence for this form
        ceo=0;
        if (cid.search(new RegExp("([0-9])[^0-9]*([0-9])[^0-9]*$","gi"))>=0) {
            ceo = (RegExp.$1+RegExp.$2)%15;
        }
        mx_page_seq = new Array();
        for (var is=1;is<=mx_pagenum;is++) {
            var seq=is;
            if (is>=39 && is<54) {
                seq += ceo;
                if (seq>53) {
                    seq = seq-15;
                }
            }
            mx_page_seq.push(seq);
        }
        //alert(mx_page_seq.join(','));
    }


    if (mx_maxima=='mxform316') {  // special page sequence for this form
      pointer     = "mxform316";
      for (var is=1;is<=mx_pagenum;is++){
        if(mx_page_seq_spec[is])
          mx_page_seq.push(mx_page_seq_spec[is]);
        else
          mx_page_seq.push(is);
      }
    }

    if (mx_maxima=='mxform262') {  // special page sequence for this form
        ceo=0;
        if (cid.search(new RegExp("([0-9])[^0-9]*([0-9])[^0-9]*$","gi"))>=0) {
            ceo = (RegExp.$1+RegExp.$2)%15;
        }
        mx_page_seq = new Array();
        for (var is=1;is<=mx_pagenum;is++) {
            var seq=is;
            if (is>=40 && is<55) {
                seq += ceo;
                if (seq>54) {
                    seq = seq-15;
                }
            }
            mx_page_seq.push(seq);
        }
        //alert(mx_page_seq.join(','));
    }
    if (mx_maxima=='mxform252') {  // special page sequence for this form
        var ceos = new Array(0,1,2,3,4,5,6);
        var n = ceos.length;
        for (var i = n - 1; i > 0; i--) {
            var j = Math.floor(Math.random() * (i + 1));
            var tmp = ceos[i];
            ceos[i] = ceos[j];
            ceos[j] = tmp;
        }
        mx_page_seq = new Array();
        for (var is=1;is<=mx_pagenum;is++) {
            var seq=is;
            if (is>=3 && is<=9) {
                seq = 3+ceos[is-3];
                if (seq>9) {
                    seq = seq-7;
                }
            }
            if (is>=11 && is<=14) {
                seq = 11+ceos[is-11];
                if (seq>14) {
                    seq = seq-4;
                }
            }
            if (is>=16 && is<=19) {
                seq = 16+ceos[is-16];
                if (seq>19) {
                    seq = seq-4;
                }
            }
            if (is>=21 && is<=24) {
                seq = 21+ceos[is-21];
                if (seq>24) {
                    seq = seq-4;
                }
            }
            mx_page_seq.push(seq);
        }
        //alert(mx_page_seq.join(','));
    }
	if (cluster>0 && !force_preview) {
		mx_page--;
		mx_display(0,1,1);
	}
	else {
		mx_display(0,0,1);
	}
}
function mx_widget(id,type,mandatory,question,question2,page,box,vartype,dependency,value,direction,cols,ml,errmsg,addt,question_position,special,img_name,image_position,prv,parent_data,possible_values,max_num_answer,rotate,parent_dependency,has_dependent_elements) {
    this.id=id;
    this.type=type;
    this.mandatory=mandatory;
    this.question=question;
    this.question2=question2;
    this.page=page;
    this.box=box;
    this.visible=false;
    this.vartype=vartype;
    this.dependency=dependency;
    // load from the cookies that's available, but not for the hiddens! - we must allow that for cid because of the banner thing
    if ((this.type!='hidden' || id=='cid') && typeof(cookie_ref[id])!='undefined') {
        this.value=cookie_ref[id];
        if (this.type!='hidden') {
            filled_num++;
        }
    }
    else {
        this.value=value;
    }
    this.direction=direction;
    this.cols=cols;
    this.ml=ml;
    this.errmsg=errmsg;
    this.addt=addt;
    this.question_position=question_position;
    this.special=special;
    eval(img_name);
    if (id=='cid') {
        cid=value;
        if (mx_maxima=='mxform286') {  // special page sequence for this form
            set286 = 1;
            subset286 = 1;
            if (cid.search(new RegExp("([0-9])[^0-9]*([0-9])[^0-9]*$","gi"))>=0) {
                if (RegExp.$1 == 2 || RegExp.$1 == 3) {
                    set286 = RegExp.$1;
                }
                if (RegExp.$2 == 2) {
                    subset286 = RegExp.$2;
                }
            }
            logos286 = new Array('EL1.png','EL2.png','EL3.png','OL4.png','UCL5.png','RL6.png','CL7.png','GL8.png','TL9.png','UPL10.png','VL11.png','FL12.png','KL13.png','THL14.png','EOL15.png','ELL16.png');
            uszi286 = new Array('EI1.jpg','EI2.jpg','EI3.jpg','OI4.jpg','UCI5.jpg','RI6.jpg','CI7.jpg','GI8.jpg','TI9.jpg','UPI10.jpg','VI11.jpg','FI12.jpg');
        }
    }
    this.img_name = new Array();
    if (typeof(mx_ifns)!='undefined' && mx_ifns.length) {
        if (0) {   // display one by rotation instead of all, we can add this as an option in the future
            ceo=0;
            if (mx_ifns.length>1) {
                if (typeof(cid)!='undefined' && cid.length) {
                    ceo=cid.substr(cid.length-1,1) % mx_ifns.length;
                }
            }
            this.img_name=mx_ifns[ceo];
        }
        else {
            for (var imi=0;imi<mx_ifns.length;imi++) {
                this.img_name.push(mx_ifns[imi]);
            }
        }
    }
    this.image_position=image_position;
    this.prv=prv;
    this.parent_dependency=parent_dependency; // logical expression connecting parents
    if (typeof(parent_data)!='undefined') {
        this.parent_dependent='row';              // matrix column or row depends on parent(s)
        this.parent_always='';                    // elements to show always regardless of parent state
        this.parent_identify='seq';               // how to connect widget elements to parent widget elements, by their order or by name
        this.parent_showsingle=1;                 // if only a single element (or row) remains, show it or not
        // items 2., 4. and 5. are used only for backward compatibility
        var pparts=parent_data.split("|:|");
        if (typeof(pparts[2])!='undefined' && pparts[2]=='column') {
            this.parent_dependent='column';
        }
        if (typeof(pparts[4])!='undefined') {
            this.parent_always=','+pparts[4]+',';
        }
        if (typeof(pparts[5])!='undefined' && pparts[5]=='name') {
            this.parent_identify='name';
        }
        if (typeof(pparts[6])!='undefined' && pparts[6]=='0') {
            this.parent_showsingle=0;
        }
    }
    this.possible_values=possible_values;
    this.max_num_answer=max_num_answer;
    this.rotate=rotate;
    this.has_dependent_elements=has_dependent_elements;
    widget_ref[id]=this;
}
function mx_box(page,box,before,after,title) {
    this.page=page;
    this.box=box;
    this.before=before;
    this.after=after;
    this.title=title;
}
function mx_pg(prev,next,dependency,active,admeasure,parent_dependency,specvalid) {
    this.prev=prev;             // previous page button
    this.next=next;             // next page button
    this.dependency=dependency;
    this.active=active;         // if this is false, the page will not be visible
    this.visible=active;        // initially the same as active, the 'true' value may become false upon dependencies
    this.admeasure=admeasure;   // call this adserver link,
    this.adshown=false;         // first time the page is shown
    this.parent_dependency=parent_dependency;   // variable name of a form element in this page that needs to meet the parent show criteria so as to show the page
    this.specvalid=specvalid;   // custom page validator function
}
function mx_create(w,pws) {
    if (w.id!='___default___jogiszoveg') {
        if (w.page!=mxlastpage||w.box!=mxlastbox) {
            if (mxlastbox!='0') {
                mx_end_box(mxlastpage,mxlastbox);
            }
            if(w.box!='0') {
                mx_start_box(w.page,w.box);
            }
        }
        mxlastbox=w.box; mxlastpage=w.page;
    }
    z("<div class='mx' id='d"+w.id+(pws?'___pw':'')+"'>");
	if (w.id.search(new RegExp("^conjoint_11_[0-9]+","gi"))>=0) {
        z("<img src='img261/spacer.gif' style='width:856px;height:546px;' id='"+w.id+"_img'>");
    }
	if (w.id.search(new RegExp("^conjoint_12_[0-9]+","gi"))>=0) {
        z("<img src='img261/spacer.gif' style='width:252px;height:546px;' id='"+w.id+"_img'>");
    }
    if (w.image_position=="above") {
        mx_printimage(w,'');
    }
    if (w.question_position=='above') {
        var cust_wd='width: 100%;';
        z("<table class='mx' style='"+cust_wd+"' cellpadding='0' cellspacing='0'><tr><td class='mxl mxt' id='q"+w.id+"' style='width:100%'>");
    }
    else {
        z("<table class='mx' cellpadding='0' cellspacing='0'><tr><td valign='"+(((w.type=='radio_matrix'||w.type=='checkbox_matrix')&&w.direction=='horizontal')?"top":"middle")+"' class='mxl mxt' id='q"+w.id+"'"+(w.type=='separator' || w.type=='comment' && w.img_name.length?"style='text-align:center;width:95%;'":"")+">");
    }
    if (w.image_position=="before") {
        mx_printimage(w,'');
    }
    if (w.type=='separator') {
        z("<hr noshade class='mx'>");
    }
    else if (w.type=='homepage') { mx_hp(w.addt,w.question,w.errmsg); }
    else if (w.type=='comment') {
        z("<div class='comment'>"+w.question+"</div>\n");
        if (mx_maxima=='mxform286' && (w.page == 4 || w.page == 6 || w.page == 8)) {
            if (w.page == 4 || w.page == 6) {
                z("<div style='display:block; margin:12px;' id='indit286" + w.page + "' class='comment'><a onclick='mx_286_play(" + w.page + ",0)' style='color:#9c141c; text-decoration:underline; cursor:pointer;'>Indít &gt;&gt;</a></div>\n");
            }
            var ceo=0;
            if (cid.search(new RegExp("([0-9])[^0-9]*([0-9])[^0-9]*([0-9])[^0-9]*([0-9])[^0-9]*$","gi"))>=0) {
                ceo = RegExp.$1;
            }
            for (var i286r = 2; i286r < uszi286.length; i286r++) {
                i286 = i286r + 1*ceo;
                if (i286 > uszi286.length - 1) {
                    i286 = i286 - (uszi286.length - 2);
                }
                var lindex = i286;
                if (i286 == 2) {
                    lindex = set286 - 1;
                }
                var divid = 'rot286' + w.page + (i286r-2);
                var addstyle286 = '';
                var addpath286 = '';
                if (w.page == 8) {
                    if (i286r == 2) {
                        z("<div style='display:none;' id='rot2868'><table border='0'><tr>");
                    }
                    z("<td style='text-align:center;'><img style='margin:12px 0;' src='http://www.kutatocentrum.hu/kutatasok/110602_logo/images/uszi/thumb/" + uszi286[lindex] + "'></td>\n");
                    if (i286r==6) {
                        z("</tr><tr>");
                    }
                    if (i286r==uszi286.length - 1) {
                        z("</tr></table></div>");
                    }
                }
                else {
                    z("<div style='text-align:center; display:none;' id='" + divid + "'><img style='margin:12px 0;' src='http://www.kutatocentrum.hu/kutatasok/110602_logo/images/uszi/" + uszi286[lindex] + "'></div>\n");
                }
            }
            var divid = 'rot286' + w.page + '10';
            z("<div style='text-align:center; clear:both;' id='" + divid + "'>&nbsp;</div>\n");
        }
    }
    else {
        tbroken=0;
        // action which should handle widget change
        odp=(w.type=='checkbox' || w.type=="radio" || w.type=='radio_matrix' || w.type=='checkbox_matrix'?"onclick":"onchange")+"=\"";
        // function handling widget change
        odpf="mx_depend('"+w.id+"');";
        z((numbering=="yes"?++ordnum+'. ':'')+w.question+(pws?' (mÃ©gegyszer)':''));
		if (w.id.search(new RegExp("^internetcoinjoint_13_t[0-9]+","gi"))>=0) {
			z("<div align='center'><img src='img"+data+"/spacer.gif' style='width:816px;height:282px; margin:12px 0;' id='"+w.id+"_img'></div");
		}
		if (w.id.search(new RegExp("^internetcoinjoint_14_k1_[0-9]+","gi"))>=0) {
			z("<div align='center'><img src='img"+data+"/spacer.gif' style='width:433px;height:282px; margin:12px;' id='"+w.id+"_img'></div>");
		}
		if (w.id.search(new RegExp("^mobil1102conjoint_q5_k[0-9]+","gi"))>=0) {
			z("<div align='center'><img src='img"+data+"/spacer.gif' style='width:866px;height:398px; margin:12px 0;' id='"+w.id+"_img'></div>");
		}
		if (w.id.search(new RegExp("^mobil1102conjoint_q6_k[0-9]+","gi"))>=0) {
			z("<div align='center'><img src='img"+data+"/spacer.gif' style='width:391px;height:398px; margin:12px 0;' id='"+w.id+"_img'></div>");
		}
		if (w.id.search(new RegExp("^mobil1103conjoint2_q7_k[0-9]+","gi"))>=0) {
			z("<div align='center'><img src='img"+data+"/spacer.gif' style='width:886px;height:385px; margin:12px 0;' id='"+w.id+"_img'></div>");
		}
		if (w.id.search(new RegExp("^mobil1103conjoint2_q8_k[0-9]+","gi"))>=0) {
			z("<div align='center'><img src='img"+data+"/spacer.gif' style='width:376px;height:385px; margin:12px 0;' id='"+w.id+"_img'></div>");
		}
		if (w.id.search(new RegExp("^kuttnsicoregon_q12_k[0-9]+","gi"))>=0) {
			z("<div align='center'><img src='img"+data+"/spacer.gif' style='width:886px;height:351px; margin:12px 0;' id='"+w.id+"_img'></div>");
		}
		if (w.id.search(new RegExp("^kuttnsicoregon_q18_k[0-9]+","gi"))>=0) {
			z("<div align='center'><img src='img"+data+"/spacer.gif' style='width:351px;height:351px; margin:12px 0;' id='"+w.id+"_img'></div>");
		}
//		if (w.id.search(new RegExp("^orrszivo_k8$","gi"))>=0) {
//			z("<div align='center'><table border='0'><tr>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo1.argb.jpg'><br>1. termék</td>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo2.argb.jpg'><br>2. termék</td>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo3.argb.jpg'><br>3. termék</td>" +
//               "</tr></table></div>");
//		}
//		if (w.id.search(new RegExp("^orrszivo_k9$","gi"))>=0) {
//			z("<div align='center'><table border='0'><tr>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo3.argb.jpg'><br>1. termék</td>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo6.argb.jpg'><br>2. termék</td>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo8.argb.jpg'><br>3. termék</td>" +
//               "</tr></table></div>");
//		}
//		if (w.id.search(new RegExp("^orrszivo_k10$","gi"))>=0) {
//			z("<div align='center'><table border='0'><tr>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo2.argb.jpg'><br>1. termék</td>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo7.argb.jpg'><br>2. termék</td>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo8.argb.jpg'><br>3. termék</td>" +
//               "</tr></table></div>");
//		}
//		if (w.id.search(new RegExp("^orrszivo_k11$","gi"))>=0) {
//			z("<div align='center'><table border='0'><tr>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo2_K11.argb.jpg'><br>1. termék</td>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo3_K11.argb.jpg'><br>2. termék</td>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo6_K11.argb.jpg'><br>3. termék</td>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 2px; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo8_K11.argb.jpg'><br>4. termék</td>" +
//               "</tr></table></div>");
//		}
//		if (w.id.search(new RegExp("^orrszivo_k12$","gi"))>=0) {
//			z("<div align='center'><table border='0'><tr>" +
//              "<td class='mxt' style='text-align:center; vertical-align:middle; font-weight:bold;'><img style='margin:12px 0; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110829_egeszseg/img/orrszivo_K12.jpg'></td>" +
//               "</tr></table></div>");
//		}
		if (w.id.search(new RegExp("^tnserstetexas_20$","gi"))>=0) {
            var lindex = set286 - 1;
            if (set286 == 1) {
                lindex = subset286;
            }
			z("<div align='center'><table border='0'><tr><td class='mxt' style='text-align:center vertical-align:middle; font-weight:bold;'>Jelenlegi logó<br><img style='margin:12px 0; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110602_logo/images/logo/" + logos286[0] + "'></td><td class='mxt' style='text-align:center vertical-align:middle; font-weight:bold;'> -&gt; </td><td class='mxt' style='text-align:center vertical-align:middle; font-weight:bold;'>Új logó<br><img style='margin:12px 0; vertical-align:middle;' src='http://www.kutatocentrum.hu/kutatasok/110602_logo/images/logo/" + logos286[lindex] + "'></td></tr></table></div>");
		}
		if (w.id.search(new RegExp("^tnserstetexas_(6_|1)([0-9]+)$","gi"))>=0) {
            var lindex = 1 * RegExp.$2 + 1;
            if (RegExp.$2 == 1) {
                lindex = set286 - 1;
            }
			z("<div align='center'><img style='margin:12px 0;' src='http://www.kutatocentrum.hu/kutatasok/110602_logo/images/logo/" + logos286[lindex] + "'></div>");
		}
        if (w.question_position=='above') {
			i14pad='width:95%';
			if (w.id.search(new RegExp("^internetcoinjoint_(15_k2|14_k1)_[0-9]+","gi"))>=0) {
				i14pad='padding:0 204px;';
			}
			if (w.id.search(new RegExp("^mobil1102conjoint_q6_k[0-9]+","gi"))>=0) {
				i14pad='padding:0 228px;';
			}
			if (w.id.search(new RegExp("^mobil1103conjoint2_q8_k[0-9]+","gi"))>=0) {
				i14pad='padding:0 234px;';
			}
			if (w.id.search(new RegExp("^kuttnsicoregon_q18_k[0-9]+","gi"))>=0) {
				i14pad='padding:0 244px;';
			}
            z("</td></tr><tr><td style='" + i14pad + "' class='mxr mxt'> \n");
        }
        else {
            if (w.type=='hidden') {
                z("</td> <td> \n");
            }
            else {
                z("</td> <td class='mxr mxt'> \n");
            }
        }
        if (w.image_position=="below") {
            mx_printimage(w,'<br>');
        }
        w.ordnum=ordnum;
		var slider = w.id.search(new RegExp("^(minta_k9|mobil1103conjoint2_q19|kuttnsicoregon_q_m_36|kuttnsicoregon_q36_v|kutkcpaneltagok_k9)","gi"))>=0;
		var ml="";
		var oku="";
		if (slider) {
			oku="mx_slide_tx(\""+w.id+"\");";
		}
		if (w.ml) {
            ml=" onkeyup='mx_depend(\""+w.id+"\");"+oku+"' onkeypress='if (this.value.length>="+w.ml+" && event.keyCode!=8 && event.keyCode!=46 && event.keyCode!=9) { return false;}' maxlength="+w.ml;
		}
		else if (oku) {
            ml=" onkeyup='"+oku+"'";
		}
        if (w.type=='input') {
            if (slider) {
                mx_slider(w,ml+odp+odpf);
            }
            else if (w.addt.length) {
                z("<table class='mx' cellpadding='0' cellspacing='0'><tr><td valign='middle'><input class='mx' style='"+half+"' name='"+w.id+"' "+ml+odp+odpf+"\"></td><td valign='middle' class='mxt'>"+w.addt+"</td></tr></table>");
            }
            else if (w.vartype=='email' && (data=='214' || data=='236' || data=='237' || data=='238' || data=='239' || data=='245' || data=='246')) {
                z("<input class='mx' value='e-mail címed' id='"+w.id+"''name='"+w.id+"' "+ml+odp+odpf+"\" onclick=\"mx_autoclear('"+w.id+"','e-mail címed')\">");
            }
            else {
                z("<input class='mx' name='"+w.id+"' "+ml+odp+odpf+"\">");
            }
        }
        if (w.type=='cim') {
            mx_cim();
        }
        if (w.type=='ceg_cim') {
            mx_ceg_cim();
        }
        if (w.type=='tel') {
            mx_tel();
        }
        if (w.type=='mob') {
            mx_mob();
        }
        if (w.type=='captcha') {
            var captchaCode = "";
            for (i=0; i<5; i++) {
                randomnumber = Math.floor(Math.random()*9)+1;
                randomalpha = Math.floor(Math.random()*25)+65;
                if ( Math.floor(Math.random()*4)==2 ) {
                    captchaCode += "" + randomnumber;
                } else {
                    captchaCode += String.fromCharCode(randomalpha);
                }
            }
            encryptedCaptchaCode = encryptString(captchaCode);
            z("<div class='captcha' style='margin-top:6px;'><input class='capinput' alt='"+encryptedCaptchaCode + "' style='vertical-align:top;' name='"+w.id+"'><img class='capimg' src='http://www.maxima.hu/captcha/captcha.php?action=create&id="+w.id+"&value="+encryptedCaptchaCode+"' alt='Maxima Captcha' style='vertical-align:top;'/></div>\n");
        }
        if (w.type=='password') {
            if (pws) {
                z("<input class='mx' name='"+w.id+"___pw' type='password' style='margin-top:2px;'>");
                pws=0;
            }
            else {
                z("<input class='mx' name='"+w.id+"' "+ml+odp+odpf+"\" type='password'>"); pws=1;
            }
        }
        if (w.type=='hidden') {
            z("<input type='hidden' id='"+w.id+"' name='"+w.id+"' value='"+w.value+"'>");
        }
        if (w.type=='textarea') {
            z("<textarea class='mx mxt' name='"+w.id+"' "+ml+odp+odpf+"\"></textarea>");
        }
        if (w.type=='multiselect' || w.type=='select') {
            if (w.type=='multiselect') {
                ms='multiple size='+w.options.length; fop=''; frr='[]';
            }
            else {
                ms='';
                fop='<option value=" "> -- '+mx_err[6]+' -- </option>'; frr='';
            }
            z("<select "+ms+" class='mx mxt' id='w_"+w.id+"' name='"+w.id+frr+"' "+odp+odpf+"\">"+fop);
            if (w.dependency=='' || mxe.id!='cigaretta_tipus') {
                for (var i=0;i<w.options.length;i++) {
                    z("<option value="+w.optvals[i]+">"+w.options[i]+"</option>");
                }
            }
            z("</select>");
        }
        if ((w.direction=='horizontal' && w.options && w.options.length) || w.type=='radio_matrix' || w.type=='checkbox_matrix') {
            prc=Math.floor(100/(w.cols+1));
            var mtxbg='';
            if (w.type=='radio_matrix' || w.type=='checkbox_matrix') {
                mtxbg=' mxhorodd mxverodd';
            }
			var toss='width:100%;';
	        if (w.id.search(new RegExp("^mobil1103conjoint2_q7_k[0-9]+","gi"))>=0) {
				var toss="margin-left:155px; width:720px;";
			}
	        if (w.id.search(new RegExp("^kuttnsicoregon_q12_k[0-9]+","gi"))>=0) {
				var toss="margin-left:155px; width:720px;";
			}
	        if (w.id.search(new RegExp("^mobil1102conjoint_q5_k[0-9]+","gi"))>=0) {
				var toss="margin-left:155px; width:720px;";
			}
	        if (w.id.search(new RegExp("^internetcoinjoint_13_t[0-9]+","gi"))>=0) {
				var toss="margin-left:300px; width:520px;";
			}
			if (w.id.search(new RegExp("^mobilconjoint_1_k[0-9]+","gi"))>=0) {
				var toss="margin-left:135px; width:710px;";
				prc=28;
                last_cnc="</td><td class='mxt hcb mtx"+mtxbg+"' style='width:16%; padding-right:8px; text-align:center;cursor:pointer;'>";
			}
            if (w.ml<0 || w.ml=='100') {
                prc='';
            }
            else {
                prc='width:'+prc+'%;';
            }
            htd="<td class='mxt hcb mtx"+mtxbg+"' style='"+prc+" text-align:center;cursor:pointer;'>";
            z("<table cellpadding='0' cellspacing='0' border='0' class='hcb' style='"+toss+"'><tr>"+(w.ml=='100'?"<td class='mxt hcb mtx"+mtxbg+"' style='width:100%;'>":(w.ml<0?"<td class='mxt hcb mtx"+mtxbg+"' style='width:" + (- w.ml) + "px'>":htd)));
            cnc="</td>"+htd;
        }
        else {
            cnc='<br>';
        }
        var staylast=0;
        if (w.id=='kuthriflora_sq10' || w.id=='kuthriflora_q16' || w.id=='kutfeminarapid_k4' || w.id=='kuttnsnivea_q15' || w.id=='mobil1103conjoint2_q25' || w.id=='mobil1103conjoint2_q28' || w.id=='sakkomkekpont_2' || w.id=='sakkomkekpont_10' || w.id=='sakkomkekpont_20' || w.id=='kuttnsicoregon_q40_1' || w.id=='kuttnsicoregon_q25' || w.id=='kuttnsicoregon_q42_v_1' || w.id=='tnserstetexas_20' || w.id == "kuttnshera_p8_1" || w.id == "kuttnshera_p8_2" || w.id == "kuttnshera_p8_3" || w.id == "kuttnshera_p8_4" || w.id == "kuttnshera_p8_5" || w.id == "kuttnshera_p8_6" || w.id == "kuttnshera_p8_7" || w.id == "kuttnshera_p8_8" || w.id == "kuttnshera_p8_9" || w.id == "kuttnshera_p8_10" || w.id == "kuttnshera_p9_1" || w.id == "kuttnshera_p9_2" || w.id == "kuttnshera_p9_3" || w.id == "kuttnshera_p9_4" || w.id == "kuttnshera_p9_5" || w.id == "kuttnshera_p9_6" || w.id == "kuttnshera_p9_7" || w.id == "kuttnshera_p9_8" || w.id == "kuttnshera_p9_9" || w.id == "kuttnshera_p9_10" || w.id == "kuttnshera_p9_11" || w.id == "kuttnshera_p9_12" || w.id == "kuttnshera_p9_13") {
            staylast=1;
        }
        if (w.id=='kuttnshera_p7' || w.id=='kuttnsnivea_s3' || w.id=='mobil1103conjoint2_q32' || w.id=='sakkomkekpont_4' || w.id=='sakkomkekpont_5' || w.id =='kutlaptophu_k2') {
            staylast=2;
        }

        if( w.id == "kuttnshera_p3" || w.id == "kuttnshera_p5"){
            staylast=3
        }

        if (w.type=='checkbox') {
            if (w.rotate=='yes') {
                var rnmx=Math.floor(Math.random()*(w.options.length-staylast));
            }
            var excl=mx_optexc_onclick(w);
            for (var rni=0;rni<w.options.length;rni++) {
                var i=rni;
                if (w.rotate=='yes' && rni<w.options.length-staylast) {
                    i=rni+rnmx;
                    if (i>=w.options.length-staylast) {
                        i-=(w.options.length-staylast);
                    }
                }
                if (w.direction=='horizontal') rni?z(cnc):1;
                var idname=w.id+"__"+w.optvals[i];
                var orwd="<div id='rad_div_"+w.optvals[i]+"'>";
                var orwi="<input type='checkbox' id='"+idname+"' name='"+idname+"' value='1' "+odp+excl+odpf+"\">";
                var coption = w.options[i];
                if (w.id.search(new RegExp("^tnserstetexas_5(_2|_3)?$","gi"))>=0) {
                    var lindex = 1 * w.options[i] + 1;
                    if (w.options[i] == 1) {
                        lindex = set286 - 1;
                    }
                    coption = "<img src='http://www.kutatocentrum.hu/kutatasok/110602_logo/images/logo/" + logos286[lindex] + "'>";
                }
                tbroken?ort='':ort="<a id='sq_"+idname+"' class='mxt' style='cursor:pointer; border:0'"+odp+"mx_ctoggle('"+w.id+"','"+w.optvals[i]+"');"+excl+odpf+"\">" + coption + "</a>";
                if (w.direction=='horizontal') {
                    z(orwd);
                    ort==''?1:z(ort+"<br>");
                    z(orwi+"</div>");
                    if (w.optbr[i]=='yes') {
                        tbroken=1;
                        z("</td></tr><tr>");
                    }
                }
                else {
                    z(orwd+orwi+' '+ort+'</div>');
                }
            }
            if (w.rotate=='yes') {
                z('<input type="hidden" name="'+w.id+'_random" value="'+rnmx+'">');
            }
        }
        if (w.type=='radio') {
            if (slider) {
                mx_slider(w,ml+odp+odpf,1);
            }
            else {
                if (w.rotate=='yes') {
                    var rnmx=Math.floor(Math.random()*(w.options.length-staylast));
                }
                for (var rni=0;rni<w.options.length;rni++) {
                    var i=rni;
                    if (w.rotate=='yes' && rni<w.options.length-staylast) {
                        i=rni+rnmx;
                        if (i>=w.options.length-staylast) {
                            i-=(w.options.length-staylast);
                        }
                    }
                    if (w.id.search(new RegExp("^mobilconjoint_1_k[0-9]+","gi"))>=0 && rni==w.options.length-1 && typeof(last_cnc)!='undefined') {
                        cnc=last_cnc;
                    }
                    if (w.direction=='horizontal') i?z(cnc):1;
                    var orwd="<div id='rad_div_"+w.optvals[i]+"'>";
                    var idname=w.id+"-"+w.optvals[i];
                    var omover = "";
                    if (w.id == 'kutkcetech_o2') {
                        omover = "onmouseover = 'mx_omover(" + w.optvals[i] + ",0)' onmouseout = 'mx_omover(" + w.optvals[i] + ",1)'";
                    }
                    var orwi="<input type='radio' id='"+idname+"' name='"+w.id+"' value='"+w.optvals[i]+"' "+odp+odpf+"\">";
                    tbroken?ort='&nbsp;':ort="<a " + omover + "id='sq_"+idname+"' class='mxt' style='cursor:pointer; border:0'"+odp+"mx_rtoggle('"+idname+"');"+odpf+"\">" + w.options[i] + "</a>";
                    if (w.direction=='horizontal') {
                        z(orwd);
                        ort==''?1:z(ort+"<br>");
                        z(orwi+'</div>');
                        if (w.optbr[i]=='yes') {
                            tbroken=1;
                            z("</td></tr><tr>");
                        }
                    }
                    else {
                        z(orwd+orwi+' '+ort+'</div>');
                    }
                }
                if (w.rotate=='yes') {
                    z('<input type="hidden" name="'+w.id+'_random" value="'+rnmx+'">');
                }
            }
        }
        if (w.type=='radio_matrix') {

          if(w.id == "kuttnshera_p6") {

            z('&nbsp;');
              var colcnt=1;
              if (w.type=='checkbox_matrix') {
                  var excl=mx_optexc_onclick(w);
              }
              for (var i=0;i<w.options.length;i++) {
                  if (w.optvert[i]=='no') {
                      var cnc2=cnc.replace(' hcb ',' mxbord mxbordtop mxver ');
                      cnc2=cnc2.replace('<td','<td id="rad_td_'+w.optvals[i]+'_t"');
                      if(colcnt%2==1) {
                          cnc2=cnc2.replace('mxverodd','mxvereven');
                      }
                      z(cnc2+w.options[i]);
                      colcnt++;
                  }
              }
              z('</td></tr>');
              if (w.rotate=='yes') {
                  var rnmx=Math.floor(Math.random()*w.options.length);
              }
              var rowcnt=0; var w31 = "";
              for (var rni=0;rni<w.options.length;rni++) {
                  var i=rni;
                  if (w.rotate=='yes') {
                      i=rni+rnmx;
                      if (i>=w.options.length) {
                          i-=w.options.length;
                      }
                  }
                  if (w.optvert[i]=='yes') {

                      if(i != 31){
                        z("<tr id='rad_div_"+w.optvals[i]+"'>");
                      } else if(i == 31){
                        w31 += "<tr id='rad_div_"+w.optvals[i]+"'>";
                      }


                      var htd2=htd.replace(' hcb ',' mxbord mxbordleft mxhor mxpad ');
                      if (rowcnt%2==0) {
                          htd2=htd2.replace('mxhorodd','mxhoreven');
                      }
                      htd4=htd2.replace('<td','<td id="rad_td_l_'+w.optvals[i]+'"');
                      if(i != 31){
                        z(htd4);
                      } else if(i == 31){
                        w31 += htd4;
                      }

                      htd2=htd2.replace(' mxbordleft ',' mxver ');
                      if(i != 31){
                        z('<div align="left" id="sq_' + w.id + '_' + i + '">'+w.options[i]+'</div>');
                      } else if(i == 31){
                        w31 += '<div align="left" id="sq_' + w.id + '_' + i + '">'+w.options[i]+'</div>';
                      }

                      var colcnt=1;
                      for (var j=0;j<w.options.length;j++) {
                          if (w.optvert[j]=='no') {
                              var htd3=htd2;
                              if (colcnt%2==1) {
                                  htd3=htd3.replace('mxverodd','mxvereven');
                              }
                              var bgt="mx_bgtoggle(event,'"+w.id+"','"+w.optvals[i]+"','"+w.optvals[j]+"','"+w.direction+"','"+w.type+"');";
                              if (w.type=='radio_matrix') {
                                  var bact=(w.special=='2D'?'mx_r2d(\''+w.id+'\','+i+','+j+');':'')+odpf;
								  var mindex=colcnt-1;
                                  var tdact=odp+bgt+"if(mx_rmtoggle(event,'"+w.id+"','"+w.optvals[i]+"','"+mindex+"')) { "+bact+"}";
                              }
                              else {
                                  var bact=excl+odpf;
                                  var tdact=odp+bgt+"if(mx_cbmtoggle(event,'"+w.id+"','"+w.optvals[i]+"','"+w.optvals[j]+"')) { "+bact+"}";
                              }
                              bact=odp+bact;
                              //bact=odp+"return false;";
                              htd3=htd3.replace('<td','<td id="rad_td_'+w.optvals[i]+'_'+w.optvals[j]+'" onmouseover="mx_bgcolor('+w.optvals[i]+','+w.optvals[j]+',\'add\',new Array('+w.optvals+'));" onmouseout="mx_bgcolor('+w.optvals[i]+','+w.optvals[j]+',\'remove\',new Array('+w.optvals+'));"' + tdact + "\"");

                              if(i != 31){
                                z('</td>'+htd3);
                              } else if(i == 31){
                                w31 += '</td>'+htd3;
                              }


                              var idname=w.id+w.optvals[i]+"__"+w.optvals[j];
                              if (w.type=='radio_matrix') {

                                  if(i != 31){
                                    z("<input type='radio' id='"+idname+"' name='"+w.id+w.optvals[i]+"' value='"+w.optvals[j]+"' "+bact+"\">");
                                  } else if(i == 31){
                                    w31 += "<input type='radio' id='"+idname+"' name='"+w.id+w.optvals[i]+"' value='"+w.optvals[j]+"' "+bact+"\">";
                                  }

                              }
                              else { if(i == 31)
                                  z("<input type='checkbox' id='"+idname+"' name='"+idname+"' value='1' "+bact+"\">");
                              }
                              colcnt++;
                          }
                      }


                      if(i != 31){
                        z('</td></tr>');
                      } else if(i == 31){
                        w31 += '</td></tr>';
                      }


                      rowcnt++;
                  }

              }

              z( w31 );

              if (w.possible_values=="bottom") {
                  if (rowcnt%2==0) {
                      cnc=cnc.replace('mxhorodd','mxhoreven');
                      htd=htd.replace('mxhorodd','mxhoreven');
                      htd=htd.replace(' hcb ',' mxbord mxhor mxbordleft ');
                      mtxbg=mtxbg.replace('mxhorodd','mxhoreven');
                  }
                  z("<tr>"+(w.ml=='100'?"<td class='mxt mxbord mxhor mxbordleft mtx"+mtxbg+"' style='width:100%;'>":(w.ml<0?"<td class='mxt hcb mtx"+mtxbg+"' style='width:" + (- w.ml) + "px'>":htd)));
                  z('&nbsp;');
                  var colcnt=1;
                  for (var i=0;i<w.options.length;i++) {
                      if (w.optvert[i]=='no') {
                          var cnc2=cnc.replace(' hcb ',' mxbord mxhor mxver ');
                          cnc2=cnc2.replace('<td','<td id="rad_td_'+w.optvals[i]+'_b"');
                          if(colcnt%2==1) {
                              cnc2=cnc2.replace('mxverodd','mxvereven');
                          }
                          z(cnc2);z(w.options[i]);
                          colcnt++;
                      }
                  }
                  z('</td></tr>');
              }

              z('</table>');
              if (w.rotate=='yes') {
                  z('<input type="hidden" name="'+w.id+'_random" value="'+rnmx+'">');
              }
          }

        }


        if (w.type=='radio_matrix' || w.type=='checkbox_matrix') {

          if(w.id != "kuttnshera_p6") {

            z('&nbsp;');
            var colcnt=1;
            if (w.type=='checkbox_matrix') {
                var excl=mx_optexc_onclick(w);
            }
            for (var i=0;i<w.options.length;i++) {
                if (w.optvert[i]=='no') {
                    var cnc2=cnc.replace(' hcb ',' mxbord mxbordtop mxver ');
                    cnc2=cnc2.replace('<td','<td id="rad_td_'+w.optvals[i]+'_t"');
                    if(colcnt%2==1) {
                        cnc2=cnc2.replace('mxverodd','mxvereven');
                    }
                    z(cnc2+w.options[i]);
                    colcnt++;
                }
            }
            z('</td></tr>');
            if (w.rotate=='yes') {
                var rnmx=Math.floor(Math.random()*w.options.length);
            }

            if(w.id == "kuttnshera_p15") {
              var limit = 4;
              var arr = [], thisBit, optArr = [];
              for(var i = 0; i < w.options.length; i++) {
                if(i < limit){
                  thisBit = Math.floor(Math.random()*2);
                  if(thisBit == 1) {
                    arr.unshift(w.options[i]);
                    optArr.unshift(w.optvals[i]);
                  } else {
                    arr.push(w.options[i]);
                    optArr.push(w.optvals[i]);
                  }
                } else {
                  arr.push(w.options[i]);
                  optArr.push(w.optvals[i]);
                }
              }
              w.options = arr;
              w.optvals = optArr;
            }

            if(w.id == "kuttnshera_k19") {
              var limit = 18;
              var arr = [], thisBit, optArr = [];
              for(var i = 0; i < w.options.length; i++) {
                if(i < limit){
                  thisBit = Math.floor(Math.random()*2);
                  if(thisBit == 1) {
                    arr.unshift(w.options[i]);
                    optArr.unshift(w.optvals[i]);
                  } else {
                    arr.push(w.options[i]);
                    optArr.push(w.optvals[i]);
                  }
                } else {
                  arr.push(w.options[i]);
                  optArr.push(w.optvals[i]);
                }
              }
              w.options = arr;
              w.optvals = optArr;

            }

            for (var rni=0;rni<w.options.length;rni++) {
                var i=rni;
                if (w.rotate=='yes') {
                    i=rni+rnmx;
                    if (i>=w.options.length) {
                        i-=w.options.length;
                    }
                }
                if (w.optvert[i]=='yes') {
                    z("<tr id='rad_div_"+w.optvals[i]+"'>");
                    var htd2=htd.replace(' hcb ',' mxbord mxbordleft mxhor mxpad ');
                    if (rowcnt%2==0) {
                        htd2=htd2.replace('mxhorodd','mxhoreven');
                    }
                    htd4=htd2.replace('<td','<td id="rad_td_l_'+w.optvals[i]+'"');
                    z(htd4);
                    htd2=htd2.replace(' mxbordleft ',' mxver ');
                    z('<div align="left" id="sq_' + w.id + '_' + i + '">'+w.options[i]+'</div>');
                    var colcnt=1;
                    for (var j=0;j<w.options.length;j++) {
                        if (w.optvert[j]=='no') {
                            var htd3=htd2;
                            if (colcnt%2==1) {
                                htd3=htd3.replace('mxverodd','mxvereven');
                            }
                            var bgt="mx_bgtoggle(event,'"+w.id+"','"+w.optvals[i]+"','"+w.optvals[j]+"','"+w.direction+"','"+w.type+"');";
                            if (w.type=='radio_matrix') {
                                var bact=(w.special=='2D'?'mx_r2d(\''+w.id+'\','+i+','+j+');':'')+odpf;
								var mindex=colcnt-1;
                                var tdact=odp+bgt+"if(mx_rmtoggle(event,'"+w.id+"','"+w.optvals[i]+"','"+mindex+"')) { "+bact+"}";
                            }
                            else {
                                var bact=excl+odpf;
                                var tdact=odp+bgt+"if(mx_cbmtoggle(event,'"+w.id+"','"+w.optvals[i]+"','"+w.optvals[j]+"')) { "+bact+"}";
                            }
                            bact=odp+bact;
                            //bact=odp+"return false;";
                            htd3=htd3.replace('<td','<td id="rad_td_'+w.optvals[i]+'_'+w.optvals[j]+'" onmouseover="mx_bgcolor('+w.optvals[i]+','+w.optvals[j]+',\'add\',new Array('+w.optvals+'));" onmouseout="mx_bgcolor('+w.optvals[i]+','+w.optvals[j]+',\'remove\',new Array('+w.optvals+'));"' + tdact + "\"");
                            z('</td>'+htd3);
                            var idname=w.id+w.optvals[i]+"__"+w.optvals[j];
                            if (w.type=='radio_matrix') {
                                z("<input type='radio' id='"+idname+"' name='"+w.id+w.optvals[i]+"' value='"+w.optvals[j]+"' "+bact+"\">");
                            }
                            else {
                                z("<input type='checkbox' id='"+idname+"' name='"+idname+"' value='1' "+bact+"\">");
                            }
                            colcnt++;
                        }
                    }
                    z('</td></tr>');
                    rowcnt++;
                }
            }
            if (w.possible_values=="bottom") {
                if (rowcnt%2==0) {
                    cnc=cnc.replace('mxhorodd','mxhoreven');
                    htd=htd.replace('mxhorodd','mxhoreven');
                    htd=htd.replace(' hcb ',' mxbord mxhor mxbordleft ');
                    mtxbg=mtxbg.replace('mxhorodd','mxhoreven');
                }
                z("<tr>"+(w.ml=='100'?"<td class='mxt mxbord mxhor mxbordleft mtx"+mtxbg+"' style='width:100%;'>":(w.ml<0?"<td class='mxt hcb mtx"+mtxbg+"' style='width:" + (- w.ml) + "px'>":htd)));
                z('&nbsp;');
                var colcnt=1;
                for (var i=0;i<w.options.length;i++) {
                    if (w.optvert[i]=='no') {
                        var cnc2=cnc.replace(' hcb ',' mxbord mxhor mxver ');
                        cnc2=cnc2.replace('<td','<td id="rad_td_'+w.optvals[i]+'_b"');
                        if(colcnt%2==1) {
                            cnc2=cnc2.replace('mxverodd','mxvereven');
                        }
                        z(cnc2);z(w.options[i]);
                        colcnt++;
                    }
                }
                z('</td></tr>');
            }

            z('</table>');
            if (w.rotate=='yes') {
                z('<input type="hidden" name="'+w.id+'_random" value="'+rnmx+'">');
            }
          }
        }
        if ((w.direction=='horizontal' && w.options && w.options.length)) {
            z('</td></tr></table>');
        }
        if (w.type=='datum') {
            var dat_year="<input class='mx' name='"+w.id+"__y' maxlength='4' style='width:50px;'> "+mname[13];
            var dat_month="<select class='mx mxt' style='width:120px;' name='"+w.id+"__m'> <option value='0'> -- </option>";
            for (i=1;i<=12;i++) {
                dat_month += "<option value='"+i+"'>"+mname[i]+"</option>";
            }
            dat_month += "</select> "+mname[14];
            var dat_day="<select style='width:50px;' class='mx mxt' name='"+w.id+"__d'> <option value='0'> -- </option>";
            for (i=1;i<=31;i++) {
                dat_day += "<option value='"+i+"'>"+i+".</option>";
            }
            dat_day += "</select> "+mname[15];
            z("<div>\n");
            if (mx_formlang=='ro') {
                z(dat_day + " " + dat_year + " " + dat_month);
            }
            else {
                z(dat_year + " " + dat_month + " " + dat_day);
            }
            z("</div>\n");
        }
    }
    z("</td></tr></table></div>\n");
    if (pws){mx_create(w,1);}
}

function d2h(d) {return d.toString(16);}
function h2d(h) {return parseInt(h,16);}

var Base64 = {

	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

	// public method for encoding
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = Base64._utf8_encode(input);

		while (i < input.length) {

			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		}

		return output;
	},

	// private method for UTF-8 encoding
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	}
};

function encryptString(text) {
    var data = new Array();
    var x = 0;
    for (x=0; x<=text.length; x++) {
        var charCode = text.charCodeAt(x);
        var charWhole = Math.floor(charCode / 7);
        var charMod = charCode % 7;
        data.push(d2h(charCode+charMod) + "-" + charMod);
    }
    return Base64.encode(data.join(","));
}
function mx_bgtoggle(ev,id,i,j,direction,type) {
    var e=ev?ev:event;
    var p=e.target?e.target:e.srcElement;
    var rb=find_object(id+i+'__'+j);
    if (p.nodeName=="INPUT") {
        p=p.parentNode;
        var rb=find_object(id+i+'__'+j);
        var chk=rb.checked;
    }
    else {
        var chk=!rb.checked;
    }
    if (chk) {
        addClass(p,"mxselected");
        bg_tds.push(p.id);
    }
    else {
        removeClass(p,"mxselected");
    }
    if (type=="radio_matrix") {
        var al = bg_tds.length;
        if (direction=="vertical") {
            for (var q=0; q<al; q++) {
                if (bg_tds[q].match(new RegExp('^rad_td_'+i)) && bg_tds[q]!="rad_td_"+i+"_"+j) {
                    removeClass(find_object(bg_tds[q]),"mxselected");
                }
            }
        }
        else {
            for (var q=0; q<al; q++) {
                if (bg_tds[q].match(new RegExp('^rad_td_\\d+_'+j)) && bg_tds[q]!="rad_td_"+i+"_"+j) {
                    removeClass(find_object(bg_tds[q]),"mxselected");
                }
            }
        }
    }
}
function mx_rtoggle(id) {
    var rb = find_object(id);
    rb.checked=(rb.checked?false:true);
}
function mx_ctoggle(id,i) {
    eval('var rb=mxf.'+id+'__'+i);
    rb.checked=(rb.checked?false:true);
}
function mx_cbmtoggle(ev,id,i,j) {
    var e=ev?ev:event;
    var p=e.target?e.target:e.srcElement;
    if(p.nodeName=="TD") {
        eval('var rb=mxf.'+id+i+'__'+j);
        rb.checked=(rb.checked?false:true);
		return true;
    }
	else {
		return false;
	}
}
function mx_rmtoggle(ev,id,i,j) {
    var e=ev?ev:event;
    var p=e.target?e.target:e.srcElement;
    if(p.nodeName=="TD") {
        //alert('var rb=mxf.'+id+i+'['+j+']');
        eval('var rb=mxf.'+id+i+'['+j+']');
        rb.checked=(rb.checked?false:true);
		return true;
    }
	else {
		return false;
	}
}
function mx_start_box(p,b) {
    for (j=0;j<mxbox.length;++j) {
        if (mxbox[j].page==p&&mxbox[j].box==b&&mxbox[j].before!='') {
            z("<div class='mxtb' id='tbb"+p+b+"'>\n"+mxbox[j].before+"</div>\n");
        }
    }
    z("<div class='mxb' id='b"+p+b+"'>\n");
    for (j=0;j<mxbox.length;++j) {
        if (mxbox[j].page==p&&mxbox[j].box==b&&mxbox[j].title!='') {
            z("<div class='mxtit'>"+mxbox[j].title+"</div>\n");
        }
    }
}
function mx_end_box(p,b) {
    z("</div>\n");
    for (j=0;j<mxbox.length;++j) {
        if (mxbox[j].page==p&&mxbox[j].box==b&&mxbox[j].after!='') {
            z("<div class='mxtb' id='tab"+p+b+"'>\n"+mxbox[j].after+"</div>\n");
        }
    }
}
function mx_page_getseq(current,chpg) {

    var next=current+chpg;
    if (typeof(mx_page_seq)!='undefined' && mx_page_seq.length) {
        var seqindex=-1;
        for (var i=0;i<mx_page_seq.length;i++) {
            if (mx_page_seq[i]==current) {
                seqindex=i;
            }
        }
        seqindex+=chpg;
        if (seqindex<0 || seqindex>=mx_page_seq.length) {
            return 0;
        }
        if (chpg==0) {
          return seqindex+1;
        }
        else {
            return mx_page_seq[seqindex];
        }

    }
    if (next<cluster_first_page || next>cluster_last_page) {
        return 0;
    }
    return next;
}

function mx_printimage(w,addstr) {
    for (var imi=0;imi<w.img_name.length;imi++) {
        z("<img onclick='click(event)' style='margin:0 10px;' src='" + (w.prv==true?'show_image.php?filename=':'./images/') + w.img_name[imi]+"'>" + addstr);
    }
}

function mx_benchmark(description) {

    var d = new Date();
    var now = Math.ceil(d.getTime()/10);
    if (typeof(description)=='undefined') {
        bmark = now;
        bsumm = '';
    }
    else if (description=='summary') {
        alert(bsumm);
    }
    else {
        bsumm += description + ': '+ (now-bmark) + '\n';
    }
}

// function to show/hide elements, to change page, to submit the form
function mx_display(jump,chpg,init,evid) {

    if (block_navigation) {
        alert('Összesen 10 képet mutatunk önnek, az utolsó megtekintése után lehet folytatni a kérdőív kitöltését.');
        return false;
    }

    if (typeof(evid)=='undefined') { // we don't need all this stuff if called from clicking an object element
        // list of pages visible by dependencies and that are active
        var next_cluster=cluster;
        for (i=cluster_first_page;i<=cluster_last_page;i++) {
            find_object('tcom_'+i).style.display="none";
            if (mxpage[i].active) {
                mxpage[i].visible=mx_dep_check('mxpage',i,evid);
            }
        }
        // find the active page and its buttons.
        var sbmit=false;
        var checkpage=mx_page;
        var old_mx_page=mx_page;
        if (chpg!=0) {
            sbmit=true;
            chpg<0 ? next_cluster-- : next_cluster++ ;
            while (checkpage = mx_page_getseq(checkpage,chpg)) {
                if (mxpage[checkpage].visible) {
                    mx_page=checkpage;
                    sbmit=false;
                    checkpage=0;
                    break;
                }
                chpg<0 ? chpg=-1 : chpg=1 ; // increment/decrement only by 1 after the first iteration
            }
        }
        var filled_out='none';
        var invcid='none';
        var notactive='none';
        var nextbtn_active=mx_page;
        if (form_inactive && !force_preview) {
            // may come from Maxima, saying that the form is not active
            if (init && typeof(cookie_ref['__form_inactive__'])!='undefined') {
                mx_page=0;
                notactive='block';
            }
            find_object('form_inactive').style.display=notactive;
        }
        if (invalid_cid && !force_preview) {
            // may come from Maxima, saying that the given CID id invalid
            if (init && typeof(cookie_ref['__invalid_cid__'])!='undefined' && notactive!='block') {
                mx_page=0;
                invcid='block';
            }
            find_object('invalid_cid').style.display=invcid;
        }
        if (filled_out_page && !force_preview) {
            // if we have just opened the form, but the form is already filled out, show a page saying 'you can not fill this form out again'
            if (init && typeof(cookie_ref['__finished__'])!='undefined' && invcid!='block' && notactive!='block') {
                mx_page=0;
                filled_out='block';
            }
            find_object('filled_out_page').style.display=filled_out;
        }
        if (intro_page && !force_preview && cluster==0) {
            var p0v='none';
            // if we have just opened the form, and we have found no filled in elements in the cookie, show the intro page
            if (init && !filled_num && filled_out!='block' && invcid!='block' && notactive!='block') {
                mx_page=0;
                p0v='block';
                nextbtn_active='intro_page';
            }
            find_object('intro_page').style.display=p0v;
        }
        var quitted='none';
        if (quitted_page && !force_preview && typeof(cookie_ref['__cluster__'])=='undefined') {
            // if we have just opened the form, and we have found some filled in elements in the cookie, show a page saying 'you are back'
            // show only if we are in the first cluster, and not coming from the second, this is not entirely correct, but will do for now.
            if (init && filled_num && filled_out!='block' && invcid!='block' && notactive!='block') {
                mx_page=0;
                quitted='block';
                nextbtn_active='quitted_page';
            }
            find_object('quitted_page').style.display=quitted;
        }
        // check for endlink pages, only when page is to be changed and there are no other spec pages displayed
        if (mx_page>0 && chpg!=0) {
            for (i=0;i<endlink_pages.length;i++) {
                if (mx_dep_check('mxendlink',i,evid)) {
                    mx_page=0;
                    find_object(endlink_pages[i]).style.display='block';
                }
            }
        }
        if (mx_page==0) {
            find_object('form_wrapper').style.display='none';
        }
        else {
            find_object('form_wrapper').style.display='block';
        }
        if (test==1 && mx_page!=0) {
            find_object('tcom_'+mx_page).style.display="block";
        }
        // if the page is changed, save data into a cookie
        if (chpg!=0) {
            mx_savecookie(sbmit,next_cluster);
        }
        if (sbmit) {
            if (0 && mx_page!=mx_pagenum) {
                mxf.action=cluster_target;
            }
            else {
                if (document.all) {
                    mx_dohp();
                }
                var d=new Date();
                mxf.___time___.value=Math.ceil(d.getTime()/1000-dstart);
            }
            mxf.submit();
            return;
        }
        mx_page<=1?nl="":nl=mxpage[mx_page].prev+" onclick='mx_display(1,-1,0);return(false);'>";
        mx_inner('navl'+mx_page,nl);
        // admeasure
        if (mx_page && mxpage[mx_page].admeasure.length && !mxpage[mx_page].adshown) {
            mxpage[mx_page].adshown=true;
            var iad=new Image(); iad.src=mxpage[mx_page].admeasure;
        }
    }
    // display elements
    var first_unfilled_page=0;
    for (var i=0;i<mxe.length;i++) {
        var idh=find_handle('d'+mxe[i].id);
        if (mxe[i].page==mx_page) {
            mxe[i].visible=mx_dep_check('mxe',i,evid);
        }
        if (mxe[i].page==mx_page && (mxe[i].visible==true || force_preview)) {
            dsp='block';
        }
        else {
            dsp='none';
        }
        if (idh) {
            idh.display=dsp;
            if (mxe[i].type=='password') {
                idh2=find_handle('d'+mxe[i].id+'___pw');
                idh2.display=dsp;
            }
            // in questions, we can refer to the values of other widgets, like {email}
            if (dsp=='block' && mxe[i].question.search(new RegExp("\{([a-z0-9_]+)\}","gi"))>=0) {
                qv=RegExp.$1;
                mxe[i].ordnum?qo=mxe[i].ordnum+'. ':qo='';
                var rerepl=qo+mxe[i].question.replace('{'+qv+'}',mx_getstate(qv,'').textvalue);
                if (rerepl.search(new RegExp("\{([a-z0-9_]+)\}","gi"))>=0) {
                    qv=RegExp.$1;
                    rerepl=rerepl.replace(eval('/{'+qv+'}/gi'),mx_getstate(qv,'').textvalue);
                }
                mx_inner('q'+mxe[i].id,rerepl);
            }
            // also, we can do it in the subquestions in the matrices
            if (dsp=='block' && (mxe[i].type=='checkbox_matrix' || mxe[i].type=='radio_matrix')) {
                for (var rni=0;rni<mxe[i].options.length;rni++) {
                    if (mxe[i].optvert[rni]=='yes' && mxe[i].options[rni].search(new RegExp("\{([a-z0-9_]+)\}","gi"))>=0) {
                        qv=RegExp.$1;
                        mx_inner('sq_'+mxe[i].id + '_' + rni,mxe[i].options[rni].replace('{'+qv+'}',mx_getstate(qv,'').textvalue));
                    }
                }
            }
            // and in the options of the other enum widgets...
            if (dsp=='block' && (mxe[i].type=='checkbox' || mxe[i].type=='radio')) {
                for (var rni=0;rni<mxe[i].options.length;rni++) {
                    if (mxe[i].options[rni].search(new RegExp("\{([a-z0-9_]+)\}","gi"))>=0) {
                        qv=RegExp.$1;
                        mx_inner('sq_' + mxe[i].id + (mxe[i].type=='checkbox'?'__':'-') + mxe[i].optvals[rni],mxe[i].options[rni].replace('{'+qv+'}',mx_getstate(qv,'').textvalue));
                    }
                }
            }
        }
        // jump to the first visible page (by dependency) which has a mandatory unfilled widget
        if (quitted=='block' && first_unfilled_page==0) {
            if (mx_getstate(mxe[i],'').filled==0) {
                if (mx_dep_check('mxpage',mxe[i].page,evid)) {
                    first_unfilled_page=mxe[i].page;
                }
            }
        }
    }
//first_unfilled_page=37;
    if (typeof(evid)=='undefined') { // we don't need all this stuff if called from clicking an object element
        if (first_unfilled_page>cluster_last_page) {
            first_unfilled_page=cluster_last_page;
        }
        // find the 'next page' button, 'lastpg=true' means that the submit button should be dislayed.
        lastpg=true;
        for (i=mx_page+1;i<=mx_pagenum;i++) {
            if (mxpage[i].active) {
                lastpg=false;
                break;
            }
        }
        lastpg?netxt=mxpage[mx_pagenum].next:(netxt=mx_page?mxpage[mx_page].next:mxpage[1].next);
        if (quitted=='block') {
            if (first_unfilled_page==0) {
                first_unfilled_page=cluster_last_page;
            }
        }
        else {
            first_unfilled_page=1;
        }
        mx_inner('navr'+nextbtn_active,netxt+" onclick='if(mx_valid()){mx_display(1,"+first_unfilled_page+",0);}else{mx_ga_track(\"oldal_"+nextbtn_active+"_sikertelen\",0);}return(false);'>");
        for (var i=0;i<mxbox.length;i++) {
            var box=mxbox[i].page+mxbox[i].box;
            var dsp=mxbox[i].page==mx_page?'block':'none';
            var idh=find_handle('b'+box);
            if (idh) { idh.display=dsp; }
            var idh=find_handle('tbb'+box);
            if (idh) { idh.display=dsp; }
            var idh=find_handle('tab'+box);
            if (idh) { idh.display=dsp; }
        }
        for (var i=0;i<=mx_pagenum;i++) {
            var idh=find_handle('nav'+i);
            if (idh) { (i==mx_page && filled_out!='block')?idh.display='block':idh.display='none'; }
        }
        mx_inner('__hpnum__',mx_page_getseq(mx_page,0));
        if (jump) {
            scrollTo(0,0);
            mx_inner('__fpnum__',mx_page);
            if (mx_page) {
                mx_ga_track('oldal_' + mx_page);
            }
        }
        if (mx_maxima=='mxform261') {
            find_object('layeropen0').style.visibility=(mx_page<23||mx_page>34?"hidden":"visible");
            //find_object('layeropen1').style.visibility=(mx_page<16||mx_page==49||mx_page==50||mx_page==51||mx_page==30||mx_page==31?"hidden":"visible");
        }
        if (mx_maxima=='mxform262') {
            find_object('layeropen0').style.visibility=(mx_page<26||mx_page>35?"hidden":"visible");
            //find_object('layeropen1').style.visibility=(mx_page<16||mx_page==49||mx_page==50||mx_page==51||mx_page==30||mx_page==31?"hidden":"visible");
        }
        if (mx_maxima=='mxform286' && (mx_page == 4 || mx_page == 6 || mx_page == 8) && old_mx_page != mx_page) {
            if (mx_page == 8) {
                mx_286_play(mx_page,0);
            }
            else {
                block_navigation = 1;
            }
        }
    }
}
// Google Analytics tracking, if set
function mx_ga_track(ga_event) {
    if (ga_virtual!='') {
        var ga_track=ga_virtual+'/'+ga_event;
				if(typeof _gat != "undefined"){
				  if(typeof pageTracker == "undefined")
					  var pageTracker = _gat._getTracker("UA-21823542-2");

          pageTracker._trackPageview(ga_track);
				  return false;
				}
    }
    return false;
}
// saves the filled data to cookie
function mx_savecookie(submit,next_cluster) {
    if (mx_cookie=='' && mx_maxima=='') {
        return;
    }
    var ca=new Array();
    //mx_benchmark();
    //mx_benchmark('xmlreq start');
    for (var i=0;i<mxe.length;i++) {
        var st=mx_getstate(mxe[i],'');
        if (st.value!='') {
            ca.push(mxe[i].id);
            ca.push(st.value.replace('|',''));
        }
    }
    //mx_benchmark('xmlreq end');
    //mx_benchmark('summary');
    // if we called this function prior to form submit, remember that in the cookie, and use it when they open the window again
    if (submit) {
        if (mx_page==mx_pagenum) {
            ca.push('__finished__');
            ca.push('1');
        }
        else {
            ca.push('__dstart__');
            ca.push(dstart);
            ca.push('__cluster__');
            ca.push(next_cluster);
            ca.push('__preview__');
            ca.push(preview);
        }
    }
    var savestr=encodeURIComponent(ca.join('|'));
    if (mx_cookie.length) {
        var d = new Date();
        d.setTime(d.getTime()+(14*24*60*60*1000));
        var expires = "; expires="+d.toGMTString();
        document.cookie = mx_cookie+"="+savestr+expires+"; path=/";
    }
    else {
        mxf.__mn__.value=mx_maxima;
        mxf.__psv__.value=savestr;
        if (!(submit && mx_page!=mx_pagenum)) {
            xmlreq('form_set_maxima.php?mn='+mx_maxima+'&psv='+savestr,mx_noresp);
        }
    }
}
// prepares from the cookie if it exists values for the mx_widget function.
function mx_getcookie() {
    if (mx_cookie=='' && mx_maxima=='') {
        return;
    }
    var getstr='';
    if (mx_cookie.length) {
        var nameEQ = mx_cookie + "=";
        var ca = document.cookie.split(';');
        for (var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) {
                getstr=c.substring(nameEQ.length,c.length);
            }
        }
	}
    else {
        getstr=mx_maxima_getstr;
    }
    var vals=decodeURIComponent(getstr).split('|');
    for (var j=0;j<vals.length;j+=2) {
        if (typeof(vals[j+1])!='undefined') {
            var vn=vals[j];
            cookie_ref[vn]=vals[j+1];
        }
    }
}
// checks for dependencies if we can show a page or widget (elt is the element type, i is it's index)
function mx_dep_check(elt,i,evid) {
    eval('var el='+elt+'['+i+']');
    if (mx_maxima=='mxform252' && elt=='mxendlink' && i==1) {
        var s3 = mx_getstate('kuttnsnivea_s3').value.split(',');
        var selgood=0;
        for (var sg=0;sg<s3.length;sg++) {
            if (s3[sg]=='31741' || s3[sg]=='31733' || s3[sg]=='31734' || s3[sg]=='31735' || s3[sg]=='31736' || s3[sg]=='31737' || s3[sg]=='31738') {
                selgood++;
            }
        }
        return mx_page>2 && selgood<2;
    }
    if (mx_maxima=='mxform199' && (elt=='mxe' && el.id=='mpoker_13' || elt=='mxpage' && i==14)) {
        var jatszott = mx_getstate('mpoker_10').value.split(',');
        var jatszik = mx_getstate('mpoker_12').value.split(',');
        res = jatszott.length > jatszik.length;
    }
	else if (el.dependency=='') {
        res=true;
    }
    else {
        // check all elements if they satisfy the given condition
        for (var j=0;j<el.depids.length;j++) {
            var vis=false;
            var depel=widget_ref[el.deptags[j]];
            // for endlinks, let it be true only if we are past the page the deptag is on so enabling negations.
            if (elt=='mxendlink' && mx_page<=depel.page) {
                if (el.dependency.search(new RegExp("!D"+el.depids[j],"gi"))>=0) {
                    vis=true;
                }
            }
            else {
                vis=mx_getstate(el.deptags[j],el.depvals[j]).satisfied;
            }
            eval('var D' + el.depids[j] + '='+vis);
        }
        // finally apply the logical expression
        if (elt=='mxpage' && i==49 && mx_maxima=='mxform264') {
            el.dependency = '!D3029 && D3052 && D3030 || D3029 && !D3052 && D3030 || D3029 && D3052 && !D3030';
        }
        if (elt=='mxpage' && i==49 && mx_maxima=='mxform267') {
            el.dependency = '!D3072 && D3073 && D3074 || D3072 && !D3073 && D3074 || D3072 && D3073 && !D3074';
        }
        eval('var res='+el.dependency);
    }
    if (res) {  // if the element can be displayed, check for parents
        if (typeof(el.parent_dependency)!='undefined' && el.parent_dependency!='' && (el.id!=evid || elt=='mxpage') && !force_preview) {
            if (elt=='mxpage') {
                res = mx_parent_dep(el.parent_dependency,0);
            }
            else if (elt=='mxe') {
                res = mx_parent_dep(el.id,1);
            }
        }
        if (elt=='mxe' && el.id=='kuttnsbebe_b4') {
            res = mx_parent_dep(el.id,1);
        }
        if (elt=='mxe' && el.id.search(new RegExp("^szinapszis_10_","gi"))>=0) {
            // this is also a parent-like dependency
            var keszitmenyek = mx_getstate('szinapszis_9').value.split(',');
            eval('var ch_sel=mxf.'+el.id);
            // NOTE: we assume that there is no Back button
            for (var j=ch_sel.options.length;j>keszitmenyek.length;j--) {
                ch_sel.remove(j);
            }
        }
        if (elt=='mxe' && el.id.search(new RegExp("^szinapszis_26_","gi"))>=0) {
            // this is also a parent-like dependency
            var keszitmenyek = mx_getstate('szinapszis_25').value.split(',');
            eval('var ch_sel=mxf.'+el.id);
            // NOTE: we assume that there is no Back button
            for (var j=ch_sel.options.length;j>keszitmenyek.length;j--) {
                ch_sel.remove(j);
            }
        }
    }
    return res;
}
// checks for parent dependencies for the given indexes of elements
function mx_parent_dep_check(child,i,option_name) {

    if (child.id == 'szinapszis_13') {
        return mx_getstate('szinapszis_13_' + (i+1),'').value != '';
    }
    for (var j=0;j<child.parent_depids.length;j++) {   // for each parent
        var parent_id=child.parent_deptags[j];
        var p=widget_ref[parent_id];
        var index = i;                                 // check for this index if it's selected
        if (child.parent_identify=='name') {           // find the index by name in this case
            var name_index=0;
            index=-1;
            for (var oi=0;oi<p.options.length;oi++) {
                // for matrices search only among subquestions
                if (index==-1 && (!(p.type=='checkbox_matrix' || p.type=='radio_matrix') || p.optvert[oi]=='yes')) {
                    if (p.options[oi]==option_name) {
                        index=name_index;
                    }
                    name_index++;
                }
            }
        }
        var vis = false;
        var parentval = ',' + child.parent_value[j] + ',';
        if (p.type=='checkbox_matrix' || p.type=='radio_matrix') {  // for matrices, the child can depend on more than one column
            var parent_column = child.parent_columns[j].split(',');
            for (pac=0;pac<parent_column.length && !vis;pac++) {
                var column=parseInt(parent_column[pac])-1;
                vis = parentval.search(new RegExp(","+index+"_"+column+",","gi"))>=0;
            }
        }
        else {
            vis = parentval.search(new RegExp(","+index+",","gi"))>=0;
        }
        eval('var PD' + child.parent_depids[j] + '='+vis);
    }
    // finally apply the logical expression
    eval('var res='+child.parent_dependency);
    return res;
}
// checks for removable parts by dependency and returns true/false as to show the element or not
function mx_parent_dep(id,remove_parts,evid) {

    var child = widget_ref[id];
    var shown = 0;

    if (child.id!='kuttnsbebe_b4') {
		for (var i=0;i<child.parent_depids.length;i++) {
			child.parent_value[i] = mx_getstate(child.parent_deptags[i],'',1).value;
		}
    }
    if (child.type=='checkbox') {
        for (var j=0;j<child.options.length;j++) {
            if (child.parent_always.search(new RegExp(","+child.optvals[j]+",","gi"))>=0 ) {
                showo=true;
            }
            else if (showo=mx_parent_dep_check(child,j,child.options[j])) {
                shown++;
            }
            if (remove_parts) {
                find_object("rad_div_"+child.optvals[j]).style.display = (showo?"block":"none");
            }
        }
    }
    else if (child.type=='select') {
        eval('ch_sel=mxf.'+child.id);
        tmp_ch_sel=ch_sel.innerHTML;
        var selvalue=ch_sel.value;
        for (var j=ch_sel.options.length;j>=0;j--) {
            ch_sel.remove(j);
        }
        ch_sel.innerHTML=tmp_ch_sel;
        for (var j=ch_sel.options.length-2;j>=0;j--) {
            if (child.parent_always.search(new RegExp(","+child.optvals[j]+",","gi"))>=0 ) {
                sd=true;
            }
            else if (sd=mx_parent_dep_check(child,j,child.options[j])) {
                shown++;
            }
            if (sd==false && remove_parts) {
                ch_sel.remove(j+1);
            }
            if (ch_sel.options[j].value==selvalue) {
                ch_sel.selectedIndex=j;
            }
        }
    }
    else if (child.type=='radio') {
        eval('ch_sel=mxf.'+child.id);
        if (child.id=='kuttnsbebe_k3') {
            var kuttnsbebe_k2=mx_getstate('kuttnsbebe_k2','').value;
        }
        for (var j=ch_sel.length-1;j>=0;j--) {
            var showo=false;
            if (child.parent_always.search(new RegExp(","+child.optvals[j]+",","gi"))>=0 ) {
                showo=true;
            }
			else if (child.id=='kuttnsbebe_k3') {
				if (kuttnsbebe_k2=='30799' && j==0 || kuttnsbebe_k2=='30800' && j<=1 || kuttnsbebe_k2=='30801' && j<=2 || kuttnsbebe_k2=='30802' && j<=3 || kuttnsbebe_k2=='30803' && j<=4 || kuttnsbebe_k2=='30804' && j<=5) {
					showo=true;
					shown++;
				}
			}
            else if (showo=mx_parent_dep_check(child,j,child.options[j])) {
                shown++;
            }
            if (remove_parts) {
                find_object("rad_div_"+ch_sel[j].value).style.display=(showo?"block":"none");
            }
        }
    }
    else if (child.type=='radio_matrix' || child.type=='checkbox_matrix') {
        var j=0;
        if (child.id=='internetcoinjoint_24' || child.id=='internetcoinjoint_25') {
            var internetcoinjoint_23 = mx_getstate('internetcoinjoint_23','').value - 33712;
        }
        for (var k=0;k<child.options.length;k++) {
            var showo=false;
            if (child.parent_dependent=='column') {
                if (child.optvert[k]=='no') {
                    if (child.id=='internetcoinjoint_24' || child.id=='internetcoinjoint_25') {
                        showo = j<internetcoinjoint_23;
                    }
                    else {
                        showo=mx_parent_dep_check(child,j,child.options[k]);
                    }
                    if (showo) {
                        shown++;
                    }
                    if (remove_parts) {
                        var tdo=find_handle('rad_td_'+child.optvals[k]+'_t');
                        if (tdo) { tdo.display=(showo==true?'':'none'); }
                        var tdo=find_handle('rad_td_'+child.optvals[k]+'_b');
                        if (tdo) { tdo.display=(showo==true?'':'none'); }
                        for (var kk=0;kk<child.options.length;kk++) {
                            if (child.optvert[kk]=='yes') {
                                var tdo=find_handle('rad_td_'+child.optvals[kk]+'_'+child.optvals[k]);
                                if (tdo) { tdo.display=(showo==true?'':'none'); }
                            }
                        }
                    }
                    j++;
                }
            }
            else if (child.optvert[k]=='yes') {
				if (child.id=='internetcoinjoint_24' || child.id=='internetcoinjoint_25') {
					showo = j<internetcoinjoint_23;
				}
				else if (child.id=='kuttnsbebe_b4') {
                    //alert('kuttnsbebe_b3_' + (j+1));
                    showo = mx_getstate('kuttnsbebe_b3_' + (j+1),'').value!='';
                }
                else if (child.id=='ithakasecure_k31') {
                    showo = (j<2 || mx_getstate('ithakasecure_k30').value=='31384');
                }
                else {
                    showo=mx_parent_dep_check(child,j,child.options[k]);
                }
                if (showo) {
                    if (remove_parts) {
                        find_object("rad_div_"+child.optvals[k]).style.display="";
                    }
                    shown++;
                }
                else if (remove_parts) {


            find_object("rad_div_"+child.optvals[k]).style.display="none";
//					if(child.id == "kuttnshera_p6" && k != 31){
//						find_object("rad_div_"+child.optvals[k]).style.display="none";
//					} else if(child.id == "kuttnshera_p6" && k == 31){
//					  console.log( child.optvals[k] ); console.log( child.options[k] );
//					  //console.log( child.id ); console.log( child );
//						// kuttnshera_p6 && 31
//					} else {
//						find_object("rad_div_"+child.optvals[k]).style.display="none";
//					}
                }
                j++;
            }
        }
    }
    if (shown==0 || shown==1 && child.parent_showsingle!=1) {
        return false;
    }
    return true;
}
// checks if all widgets in current and previous pages: all have valid values and the mandatory ones are filled in.
function mx_valid() {
    err='';
    for (var i=0;i<mxe.length;i++) {
        w=mxe[i];
        if (w.page<=mx_page && w.visible) {
            if (w.vartype=='email') {
                mx_trim(w);
            }
            v=mx_getstate(w,'');
            var page_visible=true;
            // if the page is not visible, we should not require its fields to be filled or to be valid.
            if (w.page) {
                page_visible=mx_dep_check('mxpage',w.page,'');
            }
            if (page_visible) {
                if (w.mandatory=='yes' && !v.filled) {
                    var errind = w.special=='fill_each_row'?9:1;
                    w.errmsg!=''?err+=w.errmsg+'\n':(err+=mx_err[0]+' "'+((w.question2==''&&(w.type=='radio'||w.type=='checkbox'))?w.options[0]:w.question2)+'" '+mx_err[errind]+'\n');
                }
                else if (v.err!='') {
                    err+=v.err+'\n';
                }
            }
        }
    }
    // additionally, we may have special perpage validation functions
    if (mx_page && mxpage[mx_page].specvalid!='') {
        eval('var isf=(typeof('+mxpage[mx_page].specvalid+'))=="function"');
        if (isf) {
            eval('var sv=' + mxpage[mx_page].specvalid + '()');
            if (sv.errmsg!='') {
                err+=sv.errmsg+'\n';
            }
        }
    }
    if (err!='') {
        alert(err);
        return 0;
    }
    return 1;
}

// This function returns for the widget wref an object with four values:
// .value: it's value. Used to save form state into a cookie.
// .filled: to check if the widget is filled in.
// .err: error message if the field is not filled in a valid way.
// .satisfied: true if its value=cond, or at least one element of the enum widget is set from cond false otherwise
// wref is either (the pointer to) the widget itself or it's id
// 2008-02-11 -- something is slow in this function, for now it is bypassed where possible,
//               if further optimization is needed in future, search for slowness in this function.
function mx_getstate(wref,cond,valueseqs) {
    var ret=new Object();
    ret.value='';
    ret.textvalue='';
    ret.filled=-1;
    ret.err='';
    ret.satisfied=0;
    var optselectednum=0; // how many options are selected in a checkbox widget or max of items selected per row in a checkbox matrix
    if (!(typeof(valueseqs)!='undefined' && valueseqs==1)) {
        valueseqs=0;
    }
    if (typeof(wref)!='object') {
        if (widget_ref[wref]) {
            var w=widget_ref[wref];
        }
        else {
            return ret;
        }
    }
    else {
        var w=wref;
    }
    if (w.type=='captcha') {
        eval('ret.value=mxf.'+w.id+'.value');
        eval('ret.data=mxf.'+w.id+'.alt');
        if (encryptString(ret.value.toUpperCase()) != ret.data) {
            ret.err='Helytelen captcha!';
        }
        return ret;
    }
    if (w.type=='comment' || w.type=='separator' || w.type=='homepage') {
        return ret;
    }
    var ew=0;
    if (w.type=='multiselect' || w.type=='select' || w.type=='radio' || w.type=='checkbox' || w.type=='radio_matrix' || w.type=='checkbox_matrix') {
        ew=1;
    }
    if (!mx_page_in_cluster(w.page)) {
        if (typeof(cookie_ref[w.id])!='undefined') {
            ret.textvalue=ret.value=cookie_ref[w.id];
        }
        if (ew) {
            var ew_vals=ret.value.split(',');
        }
    }
    else {
        var ew_vals=new Array(); // selected values of the enum widget
        var ew_textvals=new Array(); // selected values of the enum widget
        if (w.type=='input' || w.type=='textarea' || w.type=='hidden' || w.type=='password') {
            eval('ret.value=mxf.'+w.id+'.value');
            ret.textvalue=ret.value;
			ew_vals.push(ret.value); // for hidden enums
            if (w.type=='password') {
                eval('var v2=mxf.'+w.id+'___pw.value');
                if (ret.value!=v2) {
                    ret.err=mx_err[5];
                }
            }
            if (ret.value!='') {
                if (w.vartype=='email' && ret.value.search(new RegExp("^[\.\+_a-z0-9-]+@([0-9a-z][0-9a-z-]*[\.])+[a-z]{2,4}$","gi"))<0) {
                    ret.err=mx_err[3]+" ("+w.question2+").";
                }
                if (w.vartype=='number' && ret.value.search(new RegExp("^[0-9]*[.,]?[0-9]+$","gi"))<0) {
                    ret.err=mx_err[4]+" ("+w.question2+").";
                }
                if (w.vartype=='phone' && ret.value.search(new RegExp("^[\+]?[0-9]+$","gi"))<0) {
                    ret.err=mx_err[4]+" ("+w.question2+").";
                }
                if (w.id=='internetcoinjoint_d3' && ret.value.length!=4) {
                    ret.err="Az irányítószámnak négy számjegyből kell állnia.";
                }
            }
        }
        if (w.type=='datum') {
            eval('var year=mxf.'+w.id+'__y.value');
            eval('var month=mxf.'+w.id+'__m.options[mxf.'+w.id+'__m.selectedIndex].value');
            eval('var day=mxf.'+w.id+'__d.options[mxf.'+w.id+'__d.selectedIndex].value');
            ret.value=year+'-'+month+'-'+day;
            ret.filled=(year>0 && month>0 && day>0)?1:0;
            if (!(year<1 && month<1 && day<1)) {  // if they filled in something, check for validity.
                d = new Date(year,month-1,day);
                month1=d.getMonth()+1;
                day1=d.getDate();
                if (month1!=month || day1!=day || year<1900 || year>2000) {
                    ret.err=mx_err[2]+" ("+w.question2+").";
                }
            }
        }
        if (w.type=='multiselect' || w.type=='select') {
            var sel=find_object('w_'+w.id);
            if (sel) {
                for (var i=0;i<sel.options.length;i++) {
                    if (sel.options[i].selected && !(i==0 && w.type=='select')) {
                        w.type=='select'?ii=i-1:ii=i;
                        ew_vals.push(valueseqs?ii:w.optvals[ii]);
                        ew_textvals.push(w.options[ii]);
                        optselectednum++;
                    }
                }
            }
        }
        if (w.type=='radio') {
            for (var i=0;i<w.optvals.length;i++) {
                var ch = find_object(w.id+"-"+w.optvals[i]);
                if (ch.checked) {
                    ew_vals.push(valueseqs?i:w.optvals[i]);
                    ew_textvals.push(w.options[i]);
                }
            }
            if (ew_vals.length==0 && w.value!='') {  // if there was default value for the radio widget
                ew_vals.push(w.value);               // not changed by the user, IE thinks that it is not selected
            }
        }
        if (w.type=='checkbox') {
            for (var i=0;i<w.options.length;i++) {
                //eval('ch=mxf.'+w.id+'__'+w.optvals[i]); NOT LIKE THIS ITS FUCKING SLOW!!!
                var ch = find_object(w.id+'__'+w.optvals[i]);
                if (ch.checked) {
                    ew_vals.push(valueseqs?i:w.optvals[i]);
                    ew_textvals.push(w.options[i]);
                    optselectednum++;
                }
            }
        }
        if (w.type=='radio_matrix') {
            r2dcols=0;
            var rowindex=0;
            var unfilled_rows=0;
            var visible_rows=0;
            var one_row=0;
            for (var i=0;i<w.options.length;i++) {
                if (w.optvert[i]=='yes') {
                    var tv=0;
                    eval('sel=mxf.'+w.id+w.optvals[i]); // this will need to be removed once too, see comments at the checkbox widget
                    if (sel) {
                        for (var j=0;j<sel.length;j++) {
                            if (sel[j].checked) {
                                ew_vals.push(valueseqs?rowindex+'_'+j:w.optvals[i]+'_'+sel[j].value);
                                tv=1;
                                if (j == 2) {
                                    ew_textvals.push(w.options[i]);
                                }
                            }
                        }
                    }
                    if (find_object("rad_div_"+w.optvals[i]).style.display!='none') {
                        if (!tv) {
                            unfilled_rows++;
                        }
                        visible_rows++;
                    }
                    rowindex++;
                    one_row = w.optvals[i];
                }
            }
            var visible_cols=0;
            for (var i=0;i<w.options.length;i++) {
                if (w.optvert[i]=='no') {
                    if (find_object("rad_td_"+one_row+"_"+w.optvals[i]).style.display!='none') {
                        visible_cols++;
                    }
                }
            }
            if (unfilled_rows && (w.special!='2D' || visible_rows-unfilled_rows < Math.min(visible_rows,visible_cols))) {
                ret.filled=0;
            }
        }
        if (w.type=='checkbox_matrix') {
            var osncol=new Array();
            var osntext='Soronként';
            for (var i=0;i<w.options.length;i++) { osncol[i]=0; }
            var rowindex=0;
            for (var i=0;i<w.options.length;i++) {
                if (w.optvert[i]=='yes') {
                    var tv=0;
                    var osn=0;
                    var colindex=0;
                    for (var j=0;j<w.options.length;j++) {
                        if (w.optvert[j]=='no') {
                            //eval('sel=mxf.'+w.id+w.optvals[i]+'__'+w.optvals[j]);
                            var sel = find_object(w.id+w.optvals[i]+'__'+w.optvals[j]);
                            if (sel.checked) {
                                ew_vals.push(valueseqs?rowindex+'_'+colindex:w.optvals[i]+'_'+w.optvals[j]);
                                tv=1;
                                osn++;
                                osncol[j]++;
                            }
                            colindex++;
                        }
                    }
                    if (osn>optselectednum) {
                        optselectednum=osn;
                    }
                    if (!tv && w.special=='fill_each_row') {
						var isract = find_handle('rad_div_' + w.optvals[i]);
						if (isract && isract.display != 'none') {
                            ret.filled=0;
						}
                    }
                    rowindex++;
                }
            }
            if (w.id=='coldrex1_k75') {
                optselectednum=0;
                osntext='Oszloponként';
                for (var i=0;i<w.options.length;i++) {
                    if (osncol[i]>optselectednum) {
                        optselectednum=osncol[i];
                    }
                }
            }
        }

        if(w.id=='orrszivo_k9' && optselectednum > 2) {
          ret.err+='';
        }

        if (w.max_num_answer>0 && (optselectednum>w.max_num_answer || w.id=='feminacontent_f3' && optselectednum!=2)) {
            if (mx_formlang=='cz') {
                ret.err+=(w.type=='checkbox_matrix'?' '+osntext+' maximum ':' Vybrat maximálně ')+w.max_num_answer+' možností ('+w.question2+").";
            }
            else if (mx_formlang=='sl') {
                ret.err+=(w.type=='checkbox_matrix'?' '+osntext+' maximum ':' Vybrať si môžete ')+w.max_num_answer+' opcie ('+w.question2+").";
            }
            else if (mx_formlang=='ro') {
                ret.err+=(w.type=='checkbox_matrix'?' '+osntext+' maximum ':' Se pot selecta maxim ')+w.max_num_answer+' opţiuni ('+w.question2+").";
            }
            else if (w.id=='feminacontent_f3' && optselectednum!=2) {
                ret.err+='Kérjük, hogy a három témából kettőt válasszon!';
            }
            else {
                ret.err+=(w.type=='checkbox_matrix'?' '+osntext+' maximum ':' Maximum ')+w.max_num_answer+' opció választható ki ('+w.question2+").";
            }
        }
        if (w.type=='cim') {  // these do not work yet with cookie save, fix this later.
            ret.value=mx_cim_value();
        }
        if (w.type=='ceg_cim') {
            ret.value=mx_ceg_cim_value();
        }
        if (w.type=='tel') {
            ret.value=mx_tel_value();
            if (ret.value!='') {
                eval('var tkorzet=mxf.'+w.type+'__tel_korzet.value');
                eval('var tszam=mxf.'+w.type+'__tel_szam.value');
                if (tkorzet.length!=2 || !(tszam.length==6 || tszam.length==7)) {
                    if (tkorzet!='1' || !(tszam.length==6 || tszam.length==7)) {
                        ret.err=mx_err[7];
                    }
                }
            }
        }
        if (w.type=='mob') {
            ret.value=mx_mob_value();
            if (ret.value!='') {
                eval('var tkorzet=mxf.'+w.type+'__mobil_korzet.value');
                eval('var tszam=mxf.'+w.type+'__mobil_szam.value');
                if (!(tkorzet=="20" || tkorzet=="30" || tkorzet=="70") || !(tszam.length==7)) {
                        ret.err=mx_err[8];
                }
            }
        }
        if (ew) {
            ret.value=ew_vals.join(',');
            ret.textvalue=ew_textvals.join(', ');
        }
        else {
            ret.textvalue=ret.value;
        }
    }
    if (ret.filled==-1) {
        ret.filled=ret.value==''?0:1;
    }
    if (ew && cond=='*2') {
        ret.satisfied=(ew_vals.length>=2)?1:0;
    }
    else if (ew && cond!='*' || w.id=='internetcoinjoint_1hidden' || w.id=='hribankkartya2_q2a' || w.id=='hrihitelkartya_q2a' || w.id=='mobil1102conjoint_q1a') {  // the enum widget satisfies the condition if any of the listed items is filled in.
        var ccond=","+cond+",";
		var totalsat = 0;
        for (var i=0;i<ew_vals.length;i++) {
            if (ccond.search(new RegExp(","+ew_vals[i]+",","gi"))>=0 ) {
                ret.satisfied=1;
				totalsat++;
            }
        }
		if ((cond=='21392,21394' || cond=='30435_30450,30436_30450,30437_30450,30438_30450,30439_30450,30440_30450,30441_30450,30442_30450,30443_30450,30444_30450,30445_30450,30446_30450,30447_30450') && totalsat<2) {
			ret.satisfied=0;
		}
    }
    else if (ret.filled && cond=='*12') {
        ret.satisfied=(ret.value.length==12)?1:0;
    }
    else if (ret.filled && cond=='*14') {
        ret.satisfied=(ret.value.length==14)?1:0;
    }
    else if (ret.filled && cond=='*8') {
        ret.satisfied=(ret.value.length==8)?1:0;
    }
    else if (ret.filled && cond=='*') {
        ret.satisfied=1;
    }
    else if (ret.filled && cond=='*O') {
        ret.satisfied=(ret.value.substring(0,1)%2==1)?1:0;
    }
    else if (ret.filled && cond=='*E') {
        ret.satisfied=(ret.value.substring(0,1)%2==0)?1:0;
    }
    else if (ret.filled && cond.search(new RegExp("^[<>]=?[0-9]+$","gi"))>=0) {
        eval('ret.satisfied=(parseInt(ret.value)' + cond + ')?1:0');
    }
    else {
        ret.satisfied=(ret.value==cond)?1:0;
    }
    // mobil and tel: if one of them is filled in we consider the other filled in too.
    // in future, we should use "condition to accept a widget" instead of "widget is compulsory [<==> widget condition='*']"
    // that would make possible solving situations like this without ugly hacks like below:
    if (ret.filled==0 && (w.id=='tel' || w.id=='mobil')) {
        var other=w.id=='tel'?'mobil':'tel';
        if (typeof(widget_ref[other])!='undefined') {
            eval('ret.filled=mxf.'+other+'.value.length?1:0');
        }
    }
    return ret;
}
// loads the default values (after document onload) which are form defaults or coming from cookies.
function mx_load_defaults() {
    for (var ii=0;ii<mxe.length;ii++) {
        var w=mxe[ii];
        if (!mx_page_in_cluster(w.page)) {
            return;
        }
        if (w.value!='') {
            if (w.type=='input' || w.type=='textarea' || w.type=='hidden' || w.type=='password') {
                eval('var fw=mxf.'+w.id);
                fw.value=w.value;
            }
            if (w.type=='datum') {
                eval('var year=mxf.'+w.id+'__y');
                eval('var month=mxf.'+w.id+'__m');
                eval('var day=mxf.'+w.id+'__d');
                var dd=w.value.split('-');
                year.value=dd[0];
                var ind=parseInt(dd[1]);
                month.options[ind].selected=true;
                var ind=parseInt(dd[2]);
                day.options[ind].selected=true;
            }
            var enval=","+w.value+",";
            if (w.type=='multiselect' || w.type=='select') {
                var sel=find_object('w_'+w.id);
                if (sel) {
                    for (var i=0;i<sel.options.length;i++) {
                        if (enval.search(new RegExp(","+sel.options[i].value+",","gi"))>=0) {
                            sel.options[i].selected=true;
                        }
                    }
                }
            }
            if (w.type=='radio') {
                eval('sel=mxf.'+w.id);
                if (sel) {
                    if (sel.value) {   // only one radio button
                        if (sel.value==w.value) {
                            sel.checked=true;
                        }
                    }
                    else {
                        for (var i=0;i<sel.length;i++) {
                            if (sel[i].value==w.value) {
                                sel[i].checked=true;
                            }
                        }
                    }
                }
            }
            if (w.type=='checkbox') {
                for (var i=0;i<w.options.length;i++) {
                    eval('ch=mxf.'+w.id+'__'+w.optvals[i]);
                    if (enval.search(new RegExp(","+w.optvals[i]+",","gi"))>=0) {
                        ch.checked=true;
                    }
                }
            }
            if (w.type=='radio_matrix') {
                for (var i=0;i<w.options.length;i++) {
                    if (w.optvert[i]=='yes') {
                        eval('sel=mxf.'+w.id+w.optvals[i]);
                        if (sel) {
                            for (var j=0;j<sel.length;j++) {
                                if (enval.search(new RegExp(","+w.optvals[i]+'_'+sel[j].value+",","gi"))>=0) {
                                    sel[j].checked=true;
                                }
                            }
                        }
                    }
                }
            }
            if (w.type=='checkbox_matrix') {
                for (var i=0;i<w.options.length;i++) {
                    if (w.optvert[i]=='yes') {
                        for (var j=0;j<w.options.length;j++) {
                            if (w.optvert[j]=='no') {
                                eval('sel=mxf.'+w.id+w.optvals[i]+'__'+w.optvals[j]);
                                if (enval.search(new RegExp(","+w.optvals[i]+'_'+w.optvals[j]+",","gi"))>=0) {
                                    sel.checked=true;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
// ------------------------------ functions handling user actions on the form
// handles changes in the form (onclick etc), but only when there are dependent elements within the same page
function mx_depend(id) {

    var el = widget_ref[id];
    if (el.has_dependent_elements=='1' || id.search(new RegExp("kuttnsbebe_b3"))>=0) {
        mx_display(0,0,"",id);
    }
}
// handles clicks on two-dimensional radio matrices = only one option can be selected not only per row, but per coloumn too.
function mx_r2d(rid,ri,rj) {
    w=widget_ref[rid];
    jj=0;
    for (var i=0;i<w.options.length;i++) {
        if (w.optvert[i]=='no') {
            for (var j=0;j<w.options.length;j++) {
                if (w.optvert[j]=='yes' && j!=ri && i==rj) {
                    eval('mxf.'+w.id+w.optvals[j]+'['+jj+'].checked=false');
                }
            }
            jj++;
        }
    }
}
// returns the function that needs to be executed for options excluding the others, if any
function mx_optexc_onclick(w) {

    var exclist = new Array();
    for (var i=0;i<w.options.length;i++) {
        if (w.optexc[i]=='yes') {
            exclist.push(i);
        }
    }
    if (exclist.length) {
		if (w.type=='checkbox') {
			return 'mx_optexc(\''+w.id+'\',\''+exclist.join(',')+'\');';
		}
		else {
			return 'mx_optexc_mtx(\''+w.id+'\',\''+exclist.join(',')+'\');';
		}
    }
    return '';
}
// handles click on a checkbox matrix item that excludes the others.
function mx_optexc_mtx(rid,ri) {

    var w=widget_ref[rid];
    var ch=false;
    var ischk=false;
    var exlist=ri.split(',');
    for (var k=0;k<exlist.length;k++) {
        var i=exlist[k];
        for (var z=0;z<w.options.length;z++) {
            if (w.optvert[i] != w.optvert[z]) {
                var cb = (w.optvert[i]=='yes' ? w.id+w.optvals[i]+"__"+w.optvals[z] : w.id+w.optvals[z]+"__"+w.optvals[i]);
                var cbh = find_object(cb);
                if (cbh && cbh.checked) {
                    for (var q=0;q<w.options.length;q++) {
                        if (w.optvert[q] != w.optvert[z] && q!=i) {
                            var cb = (w.optvert[z]=='yes' ? w.id+w.optvals[z]+"__"+w.optvals[q] : w.id+w.optvals[q]+"__"+w.optvals[z]);
                            var cbh = find_object(cb);
                            if (cbh && cbh.checked) {
                                cbh.checked=false;
                            }
                        }
                    }
                }
            }
        }
    }
}

// handles click on a checkbox item that excludes the others.
function mx_optexc(rid,ri) {

    var w=widget_ref[rid];
    var ch=false;
    var ischk=false;
    var exlist=ri.split(',');
    for (var i=0;i<exlist.length;i++) {
        eval('ch=mxf.'+w.id+'__'+w.optvals[exlist[i]]);
        if (ch && ch.checked && ischk==false) {
            ischk = exlist[i];
        }
    }
    if (ischk) {
        var list=','+ri+',';
        for (var i=0;i<w.options.length;i++) {
            if (i!=ischk) {
                eval('ch=mxf.'+w.id+'__'+w.optvals[i]);
                ch.checked=false;
            }
        }
    }
}
// set this form as homepage
function mx_hp(hp,qu,mozqu) {
    if (document.all) {
        z(qu+"</td><td class='mxr mxt' style='text-align:center;'><input type='checkbox' style='behavior:url(#default#homepage)' name='___hp' value='"+hp+"' checked>");
    }
    else if (document.getElementById) {
        z('<div class="comment" style="text-align:center;"><a class="mxt" style="border:0px;" href="'+hp+'">'+mozqu+'</a></div>');
    }
}
function mx_dohp() {
    if (mxf.___hp && mxf.___hp.checked) {
        mxf.___hp.setHomePage(mxf.___hp.value);
    }
}
// ------------------------------ other helper functions
function mx_inner(idiv,itxt) {
    pnu=find_object(idiv);
    if(pnu) {
        pnu.innerHTML=itxt;
    }
}
function find_handle(id) {
    return find_object(id).style;
}
function find_object(id) {
    if (typeof(pointers[id])=='undefined' || pointers[id]==false) {
        pointers[id]=false;
        if (document.getElementById) { if (!(document.getElementById(id))) { pointers[id]=false; } else pointers[id]=document.getElementById(id); } else pointers[id]=false;
    }
    return pointers[id];
}
function mx_trim(w) {
    var v;
    eval('v=mxf.'+w.id+'.value');
    v=v.replace(/ /g,"");
    eval('mxf.'+w.id+'.value=v');
}

function mx_noresp() { }

function click(e) {
	if (document.all) {
		if (event.button == 2) {
			alert("Right-click has been disabled");
			return false;
		}
	}
	if (document.layers) {
		if (e.which == 3) {
			alert("Right-click has been disabled");
			return false;
		}
	}
}
mx_slidew=200;

function mx_slider(w,actions) {

    if (w.id.search(new RegExp("^(kuttnsicoregon_q_m_36|kuttnsicoregon_q36_v)","gi"))>=0) {
        z("<table class='mx' cellpadding='0' cellspacing='0'><tr><td class='mxt' style='text-align:right; width:310px;'>egyáltalán nem vagyok elégedett - 1&nbsp;&nbsp;</td><td style='width:200px;' valign='middle' class='mxt'> <div style='width:210px;height:20px;position:relative;margin-right:10px;' id='" + w.id +"_box'> <div style='width:200px;height:8px;font-size:6px;background-color:white; border:1px #F0CF90 solid;position:absolute;left:5px;top:6px;' onclick='mx_slide_click(event)' id='" + w.id +"_bar'> </div> <div style='width:10px;height:18px;background-color:#F0CF90; border:1px #044075 solid;position:absolute;left:104px;top:1px;' onmousedown='mx_grab(event)' id='" + w.id +"_grab'> </div> </div> </td><td style='text-align:left; width:310px;' class='mxt'>10 - rendkívül elégedett vagyok</td></tr><tr><td colspan='3' valign='middle' style='text-align:center; padding-top:2px;' class='mxt'><input class='mx' style='width:30px;' name='"+w.id+"' "+actions+"\">"+w.addt+"</td></tr></table>");
    }
    else if (w.id.search(new RegExp("^kutkcpaneltagok_k9","gi"))>=0) {
        z("<table class='mx' cellpadding='0' cellspacing='0'><tr><td class='mxt' style='text-align:right; width:310px;'>maximum 5 perces&nbsp;&nbsp;</td><td style='width:200px;' valign='middle' class='mxt'> <div style='width:210px;height:20px;position:relative;margin-right:10px;' id='" + w.id +"_box'> <div style='width:200px;height:8px;font-size:6px;background-color:white; border:1px #F0CF90 solid;position:absolute;left:5px;top:6px;' onclick='mx_slide_click(event)' id='" + w.id +"_bar'> </div> <div style='width:10px;height:18px;background-color:#F0CF90; border:1px #044075 solid;position:absolute;left:104px;top:1px;' onmousedown='mx_grab(event)' id='" + w.id +"_grab'> </div> </div> </td><td style='text-align:left; width:310px;' class='mxt'>maximum 40 perces</td></tr><tr><td colspan='3' valign='middle' style='text-align:center; padding-top:2px;' class='mxt'><input class='mx' style='width:30px;' name='"+w.id+"' "+actions+"\">"+w.addt+"</td></tr></table>");
    }
    else if (w.id.search(new RegExp("^mobil1103conjoint2","gi"))>=0) {
        z("<table class='mx' cellpadding='0' cellspacing='0'><tr><td class='mxt' style='text-align:right; width:310px;'>egyáltalán nem vagyok elégedett - 1&nbsp;&nbsp;</td><td style='width:200px;' valign='middle' class='mxt'> <div style='width:210px;height:20px;position:relative;margin-right:10px;' id='" + w.id +"_box'> <div style='width:200px;height:8px;font-size:6px;background-color:white; border:1px #F0CF90 solid;position:absolute;left:5px;top:6px;' onclick='mx_slide_click(event)' id='" + w.id +"_bar'> </div> <div style='width:10px;height:18px;background-color:#F0CF90; border:1px #044075 solid;position:absolute;left:104px;top:1px;' onmousedown='mx_grab(event)' id='" + w.id +"_grab'> </div> </div> </td><td style='text-align:left; width:310px;' class='mxt'>10 - rendkívül elégedett vagyok</td></tr><tr><td colspan='3' valign='middle' style='text-align:center; padding-top:2px;' class='mxt'><input class='mx' style='width:30px;' name='"+w.id+"' "+actions+"\">"+w.addt+"</td></tr></table>");
    }
    else {
        z("<table class='mx' cellpadding='0' cellspacing='0'><tr><td valign='middle' class='mxt'> <div style='width:210px;height:20px;position:relative;margin-right:10px;' id='" + w.id +"_box'> <div style='width:200px;height:8px;font-size:6px;background-color:white; border:1px #F0CF90 solid;position:absolute;left:5px;top:6px;' onclick='mx_slide_click(event)' id='" + w.id +"_bar'> </div> <div style='width:10px;height:18px;background-color:#F0CF90; border:1px #044075 solid;position:absolute;left:10;top:1px;' onmousedown='mx_grab(event)' id='" + w.id +"_grab'> </div> </div> </td><td valign='middle'><input class='mx' style='width:30px;' name='"+w.id+"' "+actions+"\"></td><td valign='middle' class='mxt'>"+w.addt+"</td></tr></table>");
    }
}

function mx_slide_click(ev) {
    var e=ev?ev:event;
    var p=e.target?e.target:e.srcElement;
    if (p.id.search(new RegExp("([a-zA-Z0-9_]+)_bar$","gi"))>=0) {
        mx_drag=RegExp.$1;
        mx_cursor(e);
        mx_slide_value();
    }
}
function mx_slide_value() {
    var d=find_object(mx_drag+'_grab').style;
    var scale = 100;
    var scalemin = 0;
    var multiplier = 1;
    if (mx_drag.search(new RegExp("^(mobil1103conjoint2|kuttnsicoregon_q_m_36|kuttnsicoregon_q36_v)","gi"))>=0) {
        var scale = 9;
        var scalemin = 1;
        var multiplier = 1;
    }
    if (mx_drag.search(new RegExp("^(kutkcpaneltagok_k9)","gi"))>=0) {
        var scale = 7;
        var scalemin = 1;
        var multiplier = 5;
    }
    if (d) {
        var v = (scalemin + Math.floor(parseInt(d.left)*scale/mx_slidew)) * multiplier;
        eval('mxf.'+mx_drag+'.value='+v);
    }
}
function mx_slide_tx(id) {
	mx_drag=id;
    eval('var v=mxf.'+id+'.value');
    v=parseInt(v);
    var scale = 100;
    var scalemin = 0;
    var multiplier = 1;
    if (mx_drag.search(new RegExp("^mobil1103conjoint2|kuttnsicoregon_q_m_36|kuttnsicoregon_q36_v","gi"))>=0) {
        var scale = 9;
        var scalemin = 1;
        var multiplier = 1;
    }
    if (mx_drag.search(new RegExp("^(kutkcpaneltagok_k9)","gi"))>=0) {
        var scale = 7;
        var scalemin = 1;
        var multiplier = 5;
    }
    mx_put_slider(Math.floor((v-scalemin*multiplier)*mx_slidew/(scale*multiplier)));
}
function mx_grab(ev) {
    var e=ev?ev:event;
    var p=e.target?e.target:e.srcElement;
    if (p.id.search(new RegExp("([a-zA-Z0-9_]+)_grab$","gi"))>=0) {
        mx_drag=RegExp.$1;
		document.onmousemove=mx_move;
		document.onmouseup=mx_up;
		mx_inmove=true;
		if (ev && typeof(ev.preventDefault)=="function") {
			ev.preventDefault();
		}
	}
}
function mx_move(ev) {
    if (!mx_inmove) { return false; }
    if (ev) { ev.preventDefault(); mx_cursor(ev); }
    else { event.cancelBubble = true; event.returnValue = false; mx_cursor(event); }
}
function mx_up(ev) {
    document.onmousemove=null;
    document.onmouseup=null;
    mx_inmove=false;
}

function mx_cursor(ev) {
    var x;
    var box=find_object(mx_drag+'_box');
    if (box) {
        var bx=mx_ofs(box,"offsetLeft");
		x=ev.pageX?ev.pageX:ev.x+bx;
		x=parseInt(x);
        mx_put_slider(x-bx);
		mx_slide_value();
    }
}

function mx_put_slider(x) {
    var d=find_object(mx_drag+'_grab').style;
    if (d) {
		x=parseInt(x);
		if (x=='') {
			x=0;
		}
        if (x<0) { x=0; }
        if (x>mx_slidew) { x=mx_slidew; }
        d.left=x+"px";
    }
}
function mx_ofs(r,attr) {
    var o=0;
    while(r) {o+=r[attr]; r=r.offsetParent; }
    return o;
}

function mx_201_rangsor_10() {

    var ret=new Object();
    ret.errmsg='';

    var rs=new Array('szinapszis_10_1','szinapszis_10_2','szinapszis_10_3','szinapszis_10_4','szinapszis_10_5','szinapszis_10_6','szinapszis_10_7','szinapszis_10_8','szinapszis_10_9','szinapszis_10_10','szinapszis_10_11');

    if (mx_201_rangsor(rs)) {
        ret.errmsg='A rangsor meghatározásánál nem választhatja kétszer ugyanazt a szempontot.\n';
    }
    return ret;
}

function mx_201_rangsor_26() {

    var ret=new Object();
    ret.errmsg='';

    var rs=new Array('szinapszis_26_1','szinapszis_26_2','szinapszis_26_3','szinapszis_26_4','szinapszis_26_5','szinapszis_26_6','szinapszis_26_7','szinapszis_26_8','szinapszis_26_9','szinapszis_26_26','szinapszis_26_11');

    if (mx_201_rangsor(rs)) {
        ret.errmsg='A rangsor meghatározásánál nem választhatja kétszer ugyanazt a szempontot.\n';
    }
    return ret;
}

function mx_202_rangsor_41() {

    var ret=new Object();
    ret.errmsg='';

    var rs=new Array('bankkartya_30_1','bankkartya_30_2','bankkartya_30_3','bankkartya_30_4');

    if (mx_201_rangsor(rs)) {
        ret.errmsg='A rangsor meghatározásához kérem használja 1-től 4-ig az értékeket! Minden szempontra különböző rangsorszámot adjon.\n';
    }
    return ret;
}

function mx_223_kor_5() {

    var ret=new Object();
    ret.errmsg='';

    var rs=new Array('kuttnsbebe_k4_1','kuttnsbebe_k4_2','kuttnsbebe_k4_3');

    for (var i=0;i<rs.length;i++) {
        var kor = mx_getstate(rs[i],'').value;
        if (kor!='' && (parseInt(kor)<3 || parseInt(kor)>8)) {
            ret.errmsg='A gyermekek kora 3 és 8 között kell hogy legyen.\n';
        }
    }
    return ret;
}

function mx_281_slider_39() {

    var ret=new Object();
    ret.errmsg='';

    var sval = mx_getstate('mobil1103conjoint2_q19','').value;
    if (sval=='' || parseInt(sval)<1 || parseInt(sval)>10) {
        ret.errmsg='Kérjük válasszon egy 1-től 10-ig terjedő értéket a skála segítségével.\n';
    }
    return ret;
}

function mx_227_min_6() {

    var ret=new Object();
    ret.errmsg='';

    var min6 = mx_getstate('ithakasecure_k8_a','').value;
    if (min6!='' && parseInt(min6)<6) {
        ret.errmsg='Ha hatan vagy hatnál többen laknak egy háztartásban, kérjük pontosítsa ezt egy 6-nál nem kisebb számmal.\n';
    }
    return ret;
}

function mx_282_osszeg_13() {

    var ret=new Object();
    ret.errmsg='';

    var rs=new Array('sakkomkekpont_13_1','sakkomkekpont_13_2','sakkomkekpont_13_3','sakkomkekpont_13_4','sakkomkekpont_13_5');

    var osszeg = 0;
    for (var i=0;i<rs.length;i++) {
        osszeg += 1*mx_getstate(rs[i],'').value;
    }

    if (osszeg!=100) {
        ret.errmsg='A tényezők összegének 100%-nak kell lennie.\n';
    }
    return ret;
}

function mx_284_osszeg_9() {

    var ret=new Object();
    ret.errmsg='';

    var rs=new Array('opencometkezesiu_h4c_1','opencometkezesiu_h4c_2','opencometkezesiu_h4c_3','opencometkezesiu_h4c_4','opencometkezesiu_h4c_5','opencometkezesiu_h4c_6','opencometkezesiu_h4c_98');

    var osszeg = 0;
    var visible_items = 0;
    for (var i=0;i<rs.length;i++) {
        var w=widget_ref[rs[i]];
        visible_items += w.visible;
        osszeg += 1*mx_getstate(rs[i],'').value;
    }

    if (osszeg!=100 && visible_items) {
        ret.errmsg='A megadott értékek összegének 100%-nak kell lennie.\n';
    }
    return ret;
}

function mx_243_osszeg_4() {

    var ret=new Object();
    ret.errmsg='';

    var rs=new Array('kutmail_h4','kutmail_h4_uzleti');

    if (mx_getstate('kutmail_h4_uzleti1','').value) {
        return ret;
    }

    var osszeg = 0;
    for (var i=0;i<rs.length;i++) {
        osszeg += 1*mx_getstate(rs[i],'').value;
    }

    if (osszeg!=100) {
        ret.errmsg='A tényezők összegének 100%-nak kell lennie.\n';
    }
    return ret;
}

function mx_221_osszeg_31() {

    var ret=new Object();
    ret.errmsg='';

    var rs=new Array('kutopencompghun_i29_1','kutopencompghun_i29_2','kutopencompghun_i29_3','kutopencompghun_i29_4','kutopencompghun_i29_5');

    var osszeg = 0;
    for (var i=0;i<rs.length;i++) {
        osszeg += 1*mx_getstate(rs[i],'').value;
    }

    if (osszeg!=100) {
        if (mx_formlang=='cz') {
            ret.errmsg='Celková hodnota má být 100%.\n';
        }
        else {
            ret.errmsg='A tényezők összegének 100%-nak kell lennie.\n';
        }
    }
    return ret;
}

function mx_201_rangsor(rs) {

    var rsv = new Array();
    for (var i=0;i<rs.length;i++) {
        rsv[i]=mx_getstate(rs[i],'').textvalue;
    }
    for (var i=0;i<rs.length;i++) {
        if (rsv[i].length) {
            for (var j=0;j<rs.length;j++) {
                if (rsv[j].length && i!=j && rsv[i]==rsv[j]) {
                    return 1;
                }
            }
        }
    }
    return 0;
}

function mx_264_min2_49() {

    var ret=new Object();
    ret.errmsg='';

    var internetcoinjoint_21e = ',' + mx_getstate('internetcoinjoint_21e','').value + ',';

    total21=0;
    other21=0;
    var i21vals = new Array(33898,33899,33900,33901);
    for (var i=0;i<i21vals.length;i++) {
        if (internetcoinjoint_21e.search(new RegExp(","+i21vals[i]+",","gi"))>=0 ) {
            total21++;
            if (i21vals[i]==33901) {
                other21=1;
            }
        }
    }
    if (!other21 && total21<2) {
        ret.errmsg='Ha nem az "egyikre sem" opciót választja ki akkor legalább kettőt ki kell választani.\n';
    }
    return ret;
}

function mx_285_images_2() {

    var ret=new Object();
    ret.errmsg='';

	var rimg = find_object('kuttnsicoregon_q12_k1_img');
	if (rimg.src.search(new RegExp("spacer","gi"))<0) {
		return ret;
	}
    var cid = mx_getstate('cid','').value;
    var random_group = '1';
	if (cid.search(new RegExp("([0-9])[^0-9]*([0-9])[^0-9]*$","gi"))>=0) {
        random_group = (RegExp.$1+RegExp.$2)%30+1;
    }
    if (random_group<10) {
        random_group = ''+'0'+random_group;
    }
	//alert(random_group);
    //eval('mxf.' + (mx_maxima=='mxform205'?"mobilconjoint_rotacio_szam":"mobilconjoint2_rotacio_szam") + '.value="'+random_group+'"');
    for (var i=1;i<=18;i++) {
        var ppart=i;
        ttpref = data==264?'v':'m';
        if (i<10) {
            ppart = ''+'0'+i;
        }
        if (i<6) {
			var rimg = find_object('kuttnsicoregon_q18_k'+i+'_img');
			rimg.src='http://www.kutatocentrum.hu/kutatasok/110523_internet/img'+data+'/' + ttpref + 'mo'+random_group+ppart+'.jpg';
		}
        var rimg = find_object('kuttnsicoregon_q12_k'+i+'_img');
        rimg.src='http://www.kutatocentrum.hu/kutatasok/110523_internet/img'+data+'/' + ttpref + 'to'+random_group+ppart+'.jpg';
    }
    return ret;
}

function mx_281_images_2() {

    var ret=new Object();
    ret.errmsg='';

    var mobil1103conjoint2_d4 = mx_getstate('mobil1103conjoint2_d4','').value;
    var mobil1103conjoint2_d4a;
    if (mobil1103conjoint2_d4<15) {
        mobil1103conjoint2_d4a=34414;
    }
    else if (mobil1103conjoint2_d4<=25) {
        mobil1103conjoint2_d4a=34415;
    }
    else if (mobil1103conjoint2_d4<=35) {
        mobil1103conjoint2_d4a=34416;
    }
    else if (mobil1103conjoint2_d4<=49) {
        mobil1103conjoint2_d4a=34417;
    }
    else {
        mobil1103conjoint2_d4a=34418;
    }
    eval('mxf.mobil1103conjoint2_d4a.value="'+mobil1103conjoint2_d4a+'"');
    //alert( mx_getstate('mobil1103conjoint2_d4a','').value);
	var rimg = find_object('mobil1103conjoint2_q7_k1_img');
	if (rimg.src.search(new RegExp("spacer","gi"))<0) {
		return ret;
	}
    var cid = mx_getstate('cid','').value;
    var random_group = '1';
	if (cid.search(new RegExp("([0-9])[^0-9]*([0-9])[^0-9]*$","gi"))>=0) {
        random_group = (RegExp.$1+RegExp.$2)%30+1;
    }
    if (random_group<10) {
        random_group = ''+'0'+random_group;
    }
	//alert(random_group);
    //eval('mxf.' + (mx_maxima=='mxform205'?"mobilconjoint_rotacio_szam":"mobilconjoint2_rotacio_szam") + '.value="'+random_group+'"');
    for (var i=1;i<=18;i++) {
        var ppart=i;
        ttpref = data==264?'v':'m';
        if (i<10) {
            ppart = ''+'0'+i;
        }
        if (i<6) {
			var rimg = find_object('mobil1103conjoint2_q8_k'+i+'_img');
			rimg.src='http://www.kutatocentrum.hu/kutatasok/110322_mobil/img'+data+'/' + ttpref + 'mo'+random_group+ppart+'.jpg';
		}
        var rimg = find_object('mobil1103conjoint2_q7_k'+i+'_img');
        rimg.src='http://www.kutatocentrum.hu/kutatasok/110322_mobil/img'+data+'/' + ttpref + 'to'+random_group+ppart+'.jpg';
    }
    return ret;
}

function mx_277_images_2() {

    var ret=new Object();
    ret.errmsg='';

    var mobil1102conjoint_q1 = mx_getstate('mobil1102conjoint_q1','').value;
    var mobil1102conjoint_q1a;
    if (mobil1102conjoint_q1<15) {
        mobil1102conjoint_q1a=34414;
    }
    else if (mobil1102conjoint_q1<=25) {
        mobil1102conjoint_q1a=34415;
    }
    else if (mobil1102conjoint_q1<=35) {
        mobil1102conjoint_q1a=34416;
    }
    else if (mobil1102conjoint_q1<=49) {
        mobil1102conjoint_q1a=34417;
    }
    else {
        mobil1102conjoint_q1a=34418;
    }
    eval('mxf.mobil1102conjoint_q1a.value="'+mobil1102conjoint_q1a+'"');
    //alert( mx_getstate('mobil1102conjoint_q1a','').value);
	var rimg = find_object('mobil1102conjoint_q5_k1_img');
	if (rimg.src.search(new RegExp("spacer","gi"))<0) {
		return ret;
	}
    var cid = mx_getstate('cid','').value;
    var random_group = '1';
	if (cid.search(new RegExp("([0-9])[^0-9]*([0-9])[^0-9]*$","gi"))>=0) {
        random_group = (RegExp.$1+RegExp.$2)%30+1;
    }
    if (random_group<10) {
        random_group = ''+'0'+random_group;
    }
	//alert(random_group);
    //eval('mxf.' + (mx_maxima=='mxform205'?"mobilconjoint_rotacio_szam":"mobilconjoint2_rotacio_szam") + '.value="'+random_group+'"');
    for (var i=1;i<=17;i++) {
        var ppart=i;
        ttpref = data==264?'v':'m';
        if (i<10) {
            ppart = ''+'0'+i;
        }
        if (i<8) {
			var rimg = find_object('mobil1102conjoint_q6_k'+i+'_img');
			rimg.src='img'+data+'/' + ttpref + 'mo'+random_group+ppart+'.jpg';
		}
        var rimg = find_object('mobil1102conjoint_q5_k'+i+'_img');
        rimg.src='img'+data+'/' + ttpref + 'to'+random_group+ppart+'.jpg';
    }
    return ret;
}

function mx_264_images_3() {

    var ret=new Object();
    ret.errmsg='';

    var internetcoinjoint_1 = mx_getstate('internetcoinjoint_1','').value;
    var internetcoinjoint_1hidden;
    if (internetcoinjoint_1<18) {
        internetcoinjoint_1hidden=33495;
    }
    else if (internetcoinjoint_1<=30) {
        internetcoinjoint_1hidden=33496;
    }
    else if (internetcoinjoint_1<=39) {
        internetcoinjoint_1hidden=33497;
    }
    else if (internetcoinjoint_1<=49) {
        internetcoinjoint_1hidden=33498;
    }
    else if (internetcoinjoint_1<=59) {
        internetcoinjoint_1hidden=33499;
    }
    else {
        internetcoinjoint_1hidden=33500;
    }
    eval('mxf.internetcoinjoint_1hidden.value="'+internetcoinjoint_1hidden+'"');
    //alert( mx_getstate('internetcoinjoint_1hidden','').value);
	var rimg = find_object('internetcoinjoint_13_t1_img');
	if (rimg.src.search(new RegExp("spacer","gi"))<0) {
		return ret;
	}
    var cid = mx_getstate('cid','').value;
    var random_group = '1';
	if (cid.search(new RegExp("([0-9])[^0-9]*([0-9])[^0-9]*$","gi"))>=0) {
        random_group = (RegExp.$1+RegExp.$2)%30+1;
    }
    if (random_group<10) {
        random_group = ''+'0'+random_group;
    }
	//alert(random_group);
    //eval('mxf.' + (mx_maxima=='mxform205'?"mobilconjoint_rotacio_szam":"mobilconjoint2_rotacio_szam") + '.value="'+random_group+'"');
    for (var i=1;i<=17;i++) {
        var ppart=i;
        ttpref = data==264?'v':'m';
        if (i<10) {
            ppart = ''+'0'+i;
			var rimg = find_object('internetcoinjoint_14_k1_'+i+'_img');
			rimg.src='img'+data+'/' + ttpref + 'mo'+random_group+ppart+'.jpg';
		}
        var rimg = find_object('internetcoinjoint_13_t'+i+'_img');
        rimg.src='img'+data+'/' + ttpref + 'to'+random_group+ppart+'.jpg';
    }
    return ret;
}

function mx_262_images_2() {

    var ret=new Object();
    ret.errmsg='';
    var hrihitelkartya2_q2 = mx_getstate('hrihitelkartya2_q2','').value;
    var hrihitelkartya_q2a;
    if (hrihitelkartya2_q2<23) {
        hrihitelkartya_q2a=33322;
    }
    else if (hrihitelkartya2_q2<=29) {
        hrihitelkartya_q2a=33323;
    }
    else if (hrihitelkartya2_q2<=49) {
        hrihitelkartya_q2a=33324;
    }
    else {
        hrihitelkartya_q2a=33325;
    }
	//alert(hrihitelkartya_q2a);
    eval('mxf.hrihitelkartya_q2a.value="'+hrihitelkartya_q2a+'"');
    return ret;
}

function mx_261_images_2() {

    var ret=new Object();
    ret.errmsg='';
    var hribankkartya2_q2 = mx_getstate('hribankkartya2_q2','').value;
    var hribankkartya2_q2a;
    if (hribankkartya2_q2<17) {
        hribankkartya2_q2a=33318;
    }
    else if (hribankkartya2_q2<=18) {
        hribankkartya2_q2a=33326;
    }
    else if (hribankkartya2_q2<=26) {
        hribankkartya2_q2a=33327;
    }
    else if (hribankkartya2_q2<=29) {
        hribankkartya2_q2a=33328;
    }
    else if (hribankkartya2_q2<=49) {
        hribankkartya2_q2a=33329;
    }
    else {
        hribankkartya2_q2a=33330;
    }
	//alert(hribankkartya2_q2a);
    eval('mxf.hribankkartya2_q2a.value="'+hribankkartya2_q2a+'"');
    return ret;
}

function mx_261_layer(which,close) {

    var lid='layer261_'+which;
    var l=find_object(lid);
    if (close) {
        if (l) {
            l.style.display='none';
        }
        return;
    }
    else {
        mx_261_layer(1-which,1);
    }
    if (l) {
        l.style.display='block';
    }
    else {
        var n=document.createElement("DIV");
        n.id=lid;
        document.body.appendChild(n);
        with (n.style) {
            position='absolute';
            width='820px';
            height='470px';
            overflow='auto';
            left=(mx_ofs(find_object('layers261'),"offsetLeft")+4)+'px';
            top=(mx_ofs(find_object('layers261'),"offsetTop")+24)+'px';
            backgroundColor='#fff';
            padding='8px';
            border='1px #044075 solid';
        }
        n.innerHTML='Loading...';
        eval('xmlreq("img'+data+'/'+which+'.php",mx_261_resp_'+which+')');
    }
}

function mx_261_resp_0(id,o) {
    var l=find_object('layer261_0');
    l.innerHTML=decodeURIComponent(o.response);
}
function mx_261_resp_1(id,o) {
    var l=find_object('layer261_1');
    l.innerHTML=decodeURIComponent(o.response);
}

function mx_182_tobb_3() {

    var ret=new Object();
    ret.errmsg='';

    var csalad = mx_getstate('faj_f2').value*1;
    var en = mx_getstate('faj_f3').value*1;

    if (!en || !csalad || csalad==21112) {
        return ret;
    }
    csalad=csalad-21112;
    en=en-21119;
    if (en>csalad) {
        ret.errmsg='Az Ön által beszedett mennyiség nem lehet nagyobb, mint a háztartás összes tagja által beszedett mennyiség! Kérjük ellenőrizze válaszát!';
    }
    return ret;
}

function mx_182_tobb_10() {

    var ret=new Object();
    ret.errmsg='';

    var csalad = mx_getstate('faj_f9').value*1;
    var en = mx_getstate('faj_f10').value*1;

    if (!en || !csalad || csalad==21161) {
        return ret;
    }
    csalad=csalad-21161;
    en=en-21168;
    if (en>csalad) {
        ret.errmsg='Az alkalmak száma nem lehet nagyobb, mint a háztartás összes tagja által megadott alkalmak száma! Kérjük ellenőrizze válaszát!';
    }
    return ret;
}

function mx_autoclear(id,text) {

    var o=find_object(id);
    if (o) {
        if (o.value==text) {
            o.value='';
        }
    }
}

function mx_social(link) {

    var to = find_object('megosztotta');
    if (to) {
		var from = mx_getstate('from_campaign_b2c','').textvalue;
        if (from) {
			to.value = 'from_campaign_b2c==='+from;
        }
    }
    window.open(link);
}

function mx_bgcolor(tdi, tdj, mode, optvals) {
    var tds = new Array, optl = optvals.length;
    for (var q = 0; q < optl; q++) {
        var ele = find_object("rad_td_"+tdi+"_"+optvals[q]);
        if (ele) { tds.push(ele); }
        var ele = find_object("rad_td_"+optvals[q]+"_"+tdj);
        if (ele) { tds.push(ele); }
    }
    var ele = find_object("rad_td_l_"+tdi);
    if (ele) { tds.push(ele); }
    var ele = find_object("rad_td_"+tdj+"_t");
    if (ele) { tds.push(ele); }
    var ele = find_object("rad_td_"+tdj+"_b");
    if (ele) { tds.push(ele); }
    var tdsl = tds.length;
    for (var i = 0; i < tdsl; i++) {
        if (mode == "add") {
            addClass(tds[i], "mxhover");
        }
        else {
            removeClass(tds[i], "mxhover");
        }
    }
}

function hasClass(ele,cls) {
	return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}

function addClass(ele,cls) {
	if (!this.hasClass(ele,cls)) ele.className += " "+cls;
}

function removeClass(ele,cls) {
	if (hasClass(ele,cls)) {
    	var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
		ele.className=ele.className.replace(reg,' ');
	}
}

function mx_omover(which,close) {

    var lid='layer261_'+which;
    var l=find_object(lid);
    if (close) {
        if (l) {
            l.style.display='none';
        }
        return;
    }
    else {
        mx_261_layer(1-which,1);
    }
    if (l) {
        l.style.display='block';
    }
    else {
        var n=document.createElement("DIV");
        n.id=lid;
        document.body.appendChild(n);
        with (n.style) {
            position='absolute';
            width='500px';
            height='100px';
            overflow='auto';
            left=(mx_ofs(find_object('kutkcetech_o2-' + which),"offsetLeft")+4)+'px';
            top=(mx_ofs(find_object('kutkcetech_o2-' + which),"offsetTop")+24)+'px';
            backgroundColor='#fff';
            padding='8px';
            border='1px #044075 solid';
        }
        if (which == 38712) {
            var text = "<u>Okostelefon</u> olyan mobiltelefon, mely a telefonáláson és az alapfunkciókon kívül e-mail fiókok kezelésére, internetezésre, navigációra és más összetett, korábban csak személyi számítógépeken végezhető feladatok elvégzésére is alkalmas, és az operációs rendszere az adott rendszerre írt programok széles skálájával bővíthető";
        }
        else {
            var text = "<u>A „felokosított” hagyományos telefon</u> esetében a vásárlás időpontjában csak az alapfunkciók kezelésére van lehetőség, melyet a felhasználó okostelefonokra jellemző új alkalmazásokkal egészít ki a későbbiekben. ";
        }
        n.innerHTML="<span class='mxt'>" + text + "</span>";
    }
}

function mx_286_play(page,index) {
    if (page == 8) {
        var dh = find_handle('rot2868');
        dh.display = (index?'none':'block');
        nextindex = 10;
    }
    else {
        if (index) {
            var dh = find_handle('rot286' + page + '' + (index-1));
            dh.display = 'none';
        }
        var dh = find_handle('rot286' + page + '' + index);
        dh.display = 'block';
        nextindex = index+1;
    }
    var wait = new Array(2000,5000,10000);
    if (index<10) {
        setTimeout("mx_286_play(" + page + "," + nextindex + ")",wait[page/2-2]);
        block_navigation = 1;
    }
    else {
        block_navigation = 0;
        mx_display(1,1,0);
    }
    if (index == 0 && (page == 4 || page == 6)) {
        var dh = find_handle('indit286' + page);
        dh.display = 'none';
    }
}
