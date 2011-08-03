<?

class MxSubscribe 
{  
    
    function FormCollectSubscribe($data) {

        $this->Subscribe(
                $data["group"],
                $data["idname"],
                $data["hidden_subscribe"],
                $data["affiliate"],
                $data["othergroups"],
                $data["values"]
               );
    }
    
    function Subscribe($group,$idname,$hidden_subscribe,$affiliate,$othergroups,$values) {

        // Use the mail gateway, this will need to be changed to a direct call to the perl subscribe scripts

        global $_MX_var;

        if (count($othergroups)) {
            $body = implode("\n",$othergroups) . "\n##endgroups##\n\n";
        }
        else {
            $body="";
        }
        $idvalue="";
        foreach ($values as $dat) {
            $dat[1]=ereg_replace("[\r\n]"," ",$dat[1]);
            $body.="# $dat[0]:$dat[1]\n";
            if ($dat[0]==$idname) {
                $idvalue=$dat[1];
            }
        }
        $hidden_subscribe=="yes"?$hpref="zuiol-":$hpref="";
        $to=$hpref."subscribe@$group.maxima.hu";
        $body.="##end##\n";
        if ($_MX_var->test_version=="yes") {
            $body.="$to\n";
            $to="tbjanos@manufaktura.rs";
        }
        $subject="aff:$affiliate";
        if ($idname=="email") {
            $from=$idvalue;
        }
        else {
            $from="#$idname#$idvalue";
        }
        mail($to,$subject,$body,"From: $from");
    }
}

