<?php
include_once "auth.php";
include_once "common.php";
require_once "_subscribe.php";

class WsSubscribe {  
   
    /** 
     * Subscribes to the given group. Returns 1 on success 0 otherwise
     * 
     * @param string    $group      Name of the group or multigroup to subscribe to
     * @param string    $idname     Name of the variable that identifies the subscriber, usually email, cid or mobil
     * @param string    $hidden     Without validation or not
     * @param string    $affiliate  Affiliate id
     * @param array     $grouplist  Array of names of the groups in case of a multi subscribe
     * @param array     $values     Array of (variable_name,variable_value) arrays holding data of the subscriber
     * @return integer 1 on success 0 otherwise
     */  
	public function Subscribe($group,$idname,$hidden,$affiliate,$grouplist,$values) {

        $_MX_subscribe = new MxSubscribe();
        $_MX_subscribe->Subscribe($group,$idname,$hidden,$affiliate,$grouplist,$values);

        return 1;
	}

    /** 
     * Unsubscribes from the given group. Returns string
     * 
     * @param string $group The group to unsubscribe from, id or name
     * @param string $email The email address to unsubscribe
     * @return string returns 'success' or error string
     */  
	public function UnSubscribe($group,$email) {

        return mx_ppos_unsub($email,$group,"webservice");
	}
}
?>
