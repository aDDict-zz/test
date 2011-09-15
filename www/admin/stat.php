<?php	
    if (empty($_REQUEST["action"])) {
        $_REQUEST["action"] = "ct_news";
        $_REQUEST["period"] = "week";
    }
	switch($_REQUEST['action']){
		case "ct_news":
            $period = $_REQUEST["period"];
            if (empty($period)) {
                $from_year = $_REQUEST["from_Year"];
                $from_month = $_REQUEST["from_Month"];
                $from_day = $_REQUEST["from_Day"];
                if (strlen($from_month) == 1) $from_month = "0$from_month";
                if (strlen($from_day) == 1) $from_day = "0$from_day";
                $from_date = mysql_real_escape_string("$from_year-$from_month-$from_day");
                $to_year = $_REQUEST["to_Year"];
                $to_month = $_REQUEST["to_Month"];
                $to_day = $_REQUEST["to_Day"];
                if (strlen($to_month) == 1) $to_month = "0$to_month";
                if (strlen($to_day) == 1) $to_day = "0$to_day";
                $to_date = mysql_real_escape_string("$to_year-$to_month-$to_day");
            } else {
                switch ($period) {
                    case "today":
                        $from_year = date("Y");
                        $from_month = date("m"); 
                        $from_day = date("d"); 
                        $to_year = date("Y");
                        $to_month = date("m");
                        $to_day = date("d");
                        break;
                    case "yesterday":
                        $ts = time()-86400;
                        $from_year = date("Y", $ts);
                        $from_month = date("m", $ts); 
                        $from_day = date("d", $ts); 
                        $to_year = date("Y", $ts);
                        $to_month = date("m", $ts);
                        $to_day = date("d", $ts);
                        break;
                    case "week":
                        $ts = time()-6*86400;
                        $from_year = date("Y", $ts);
                        $from_month = date("m", $ts); 
                        $from_day = date("d", $ts); 
                        $to_year = date("Y");
                        $to_month = date("m");
                        $to_day = date("d");
                        break;
                    case "month":
                        $ts = time()-29*86400;
                        $from_year = date("Y", $ts);
                        $from_month = date("m", $ts); 
                        $from_day = date("d", $ts); 
                        $to_year = date("Y");
                        $to_month = date("m");
                        $to_day = date("d");
                        break;
                }
                $from_date = "$from_year-$from_month-$from_day";
                $to_date = "$to_year-$to_month-$to_day";
            }
            $agency_id = isset($_REQUEST["agency_id"]) ? mysql_real_escape_string($_REQUEST["agency_id"]) : 0;
            $rss_id = isset($_REQUEST["rss_id"]) ? mysql_real_escape_string($_REQUEST["rss_id"]) : 0;
            $smarty->assign("from_date", $from_date);
            $smarty->assign("to_date", $to_date);
            $smarty->assign("period", $period);
            $limit = "limit 20";
            $rss_join = "";
            $wheres = array();
            $wheres[] = "date(ct.date_add) >= '$from_date'";
            $wheres[] = "date(ct.date_add) <= '$to_date'";
            if ($rss_id) {
                $wheres[] =  "r.id = '$rss_id'";
                $rss_join = "inner join rss_feeds r on ct.rss_id = r.id";
            } elseif ($agency_id) {
                $wheres[] =  "a.agency_id = '$agency_id'";
                $rss_join = "inner join rss_feeds r on ct.rss_id = r.id inner join agencies a on r.agency_id = a.agency_id";
            }
            $where = count($wheres) ? ("where " . implode(" and ", $wheres)) : "";

            $ct_logged_in = array("0"=>0, "1"=>0, "2"=>0);
            $query= "select count(*) c, if(user_id = -1, 0, 1) logged_in from ct_news ct $rss_join $where group by logged_in ";
			$result = mysql_query($query) or die(mysql_error());
			while($row = mysql_fetch_assoc($result)){
                $ct_logged_in[$row["logged_in"]] = $row["c"];
                $ct_logged_in[2] += $row["c"];
            }
            $smarty->assign("ct_logged_in", $ct_logged_in);

            $where = count($wheres) ? ("where " . implode(" and ", $wheres)) : "";
            $ct_url = array();
            $query= "select count(*) c, min(date(ct.date_add)) date_from, max(date(ct.date_add)) date_to, count(*) / (max(to_days(ct.date_add)) - min(to_days(ct.date_add)) +1) ctperday, a.agency_name, r.rss_name, ct.url, ct.title, if(ct.user_id = -1, 0, 1) logged_in from ct_news ct left join rss_feeds r on ct.rss_id = r.id left join agencies a on r.agency_id = a.agency_id $where group by ct.url, logged_in order by ctperday desc $limit";
			$result = mysql_query($query) or die(mysql_error());
			while($row = mysql_fetch_assoc($result)){
                if (!isset($ct_url[$row["url"]])) {
                    $ct_url[$row["url"]] = array("c"=>$row["c"], "title"=>stripslashes($row["title"]), "$row[logged_in]"=>$row["c"], "agency"=>$row["agency_name"], "rss"=>$row["rss_name"], "date_from"=>$row["date_from"], "date_to"=>$row["date_to"], "ctperday"=>sprintf("%.2f", $row["ctperday"]));
                } else {
                     $ct_days[$row["url"]]["c"] += $row["c"];
                    $ct_url[$row["url"]][$row["logged_in"]] = $row["c"];
                }
            }
            $smarty->assign("ct_url", $ct_url);

            $ct_days = array();
            $query= "select count(*) c, to_days(date_add) d, unix_timestamp(date_add) ts_date_add, if(user_id = -1, 0, 1) logged_in from ct_news ct $rss_join $where group by d, logged_in order by d";
			$result = mysql_query($query) or die(mysql_error());
			while($row = mysql_fetch_assoc($result)){
                $day = date("Y-m-d", $row["ts_date_add"]);
                if (!isset($ct_days[$day])) {
                    $ct_days[$day] = array("c"=>$row["c"], "$row[logged_in]"=>$row["c"]);
                } else {
                     $ct_days[$day][$row["logged_in"]] = $row["c"];
                     $ct_days[$day]["c"] += $row["c"];
                }
            }
            $smarty->assign("ct_days", $ct_days);

            $agencies = array();
            $sql = "select * from agencies order by agency_name";
            $r = mysql_query($sql);
            while ($a = mysql_fetch_assoc($r)) {
                foreach($a as $key=>$val) $a[$key] = htmlspecialchars($val);
                if ($a["agency_id"] == $agency_id) $a["selected"] = 1;
                $agencies[] = $a;
            }
            $smarty->assign("agencies", $agencies);
            $rss_feeds = array();
            $where_agency = $agency_id ? "where agency_id = '$agency_id'" : "";
            $sql = "select * from rss_feeds $where_agency order by rss_name";
            $r = mysql_query($sql);
            while ($a = mysql_fetch_assoc($r)) {
                foreach($a as $key=>$val) $a[$key] = htmlspecialchars($val);
                if ($a["id"] == $rss_id) $a["selected"] = 1;
                $rss_feeds[] = $a;
            }
            $smarty->assign("rss_feeds", $rss_feeds);
    }
	switch($__sub_id){
		case 1:
			break;
	}
?>
