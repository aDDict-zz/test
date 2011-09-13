            <div id="basic-modal-content" class="vishid">
                <input type="hidden" id="lng_count" value="<?=sizeof($_MX_var->supported_langs);?>" />
                <input type="hidden" id="actual_help_row" value="" />
                <? if (mx_can_edit_help()) { ?>
                    <div class="bold" style="margin-bottom:15px;"><?=$word['insert_help_text'];?> "<?=$word["menu_$weare"];?>" <?=$word['help_for_page'];?></div>
                  <? foreach ($_MX_var->supported_langs as $k=>$lang) { ?>
                    <img src="<?=$_MX_var->baseUrl;?>/<?=$_MX_var->application_instance;?>/gfx/<?=$lang;?>.jpg" alt="" />
                    <textarea id="rte_<?=$lang;?>" style="width:99%;"> </textarea>
                    <input type="hidden" id="lng_<?=$lang;?>" value="<?=$lang;?>" />
                    <input type="button" value="Mentés" id="save_help_<?=$lang;?>" />
                    <div id="save_success_<?=$lang;?>" style="display:none;" class="bold dispinl"><?=$word['help_save_success'];?></div>
                    <div id="save_fail_<?=$lang;?>" style="display:none;" class="bold dispinl"><?=$word['help_save_fail'];?></div>                
                    <br />
                    <br />
                  <? } ?>
                    <div id="get_help" class="hidden">get_help</div>
                    <input type="hidden" id="pid" value="<?=$weare;?>" />
                    <input type="hidden" id="noedittext" value="0" />
                <? } else { ?>
                    <div class="bold" style="margin-bottom:15px;"><?=$word['help_text_for'];?> "<?=$word["menu_$weare"];?>" <?=$word['help_for_page'];?></div>
                    <div id="help" style="width:99%;"></div>
                    <div id="nohelp"><?=$word['no_help'];?></div>
                    <div id="get_help" class="hidden">get_help</div>
                    <input type="hidden" id="pid" value="<?=$weare;?>" />
                    <input type="hidden" id="lng" value="<?=$language;?>" />                    
                    <input type="hidden" id="noedittext" value="1" />                    
                <? } ?>
            </div>
