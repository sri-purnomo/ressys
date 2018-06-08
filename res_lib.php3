<?
function isLeapYear ($year) {
     if ((($year % 4)==0) && (($year % 100)!=0) || (($year % 400)==0)) {
          return true;
     } else { return false; }
     }

function getDaysInMonth($month,$year)  {
     $days = 30;
     if ($month==1 || $month==3 || $month==5 || $month==7 || $month==8 || $month==10 || $month==12)  $days=31;
     else if ($month==4 || $month==6 || $month==9 || $month==11) $days=30;
     else if ($month==2)  {
          if (isLeapYear($year)) { $days=29; }
          else { $days=28; }
          }
     return $days;
     }

function isFourDigitYear($year) {
     if (strlen($year) != 4) {
          echo ("Sorry, the year must be four-digits in length.");
     } else { return true; }
     }

function setToday() {
     $cnow   = date("Y-m-d");
     $cday   = substr($cnow,8,2);
     $cmonth = substr($cnow,5,2);
     $cyear  = substr($cnow,0,4);
     return "date=".$cday."&month=".$cmonth."&year=".$cyear ;
     }

function setPreviousYear($month,$year) {
     if (isFourDigitYear($year)) {
          $year--;
          $cday = 1 ;
          return "date=".$cday."&month=".$month."&year=".$year ;
          }
     }

function setPreviousMonth($month,$year) {
     if (isFourDigitYear($year)) {
          if ($month == 1) {
               $month = 12;
               if ($year > 1000) {
                    $year--;
                    }
               } else { $month--; }
          $cday = 1 ;
          return "date=".$cday."&month=".$month."&year=".$year ;
          }
     }

function setNextMonth($month,$year) {
     if (isFourDigitYear($year)) {
          $day   = 0;
          if ($month == 12) {
              $month = 1;
               $year++;
               } else { $month++; }
          $cday = 1 ;
          return "date=".$cday."&month=".$month."&year=".$year ;

          }
     }

function setNextYear($month,$year) {
     if (isFourDigitYear($year)) {
          $year++;
          $cday = 1 ;
          return "date=".$cday."&month=".$month."&year=".$year ;
          }
     }

function ymd2mdy($sdate){
      $cday   = substr($sdate,8,2);
      $cmonth = substr($sdate,5,2);
      $cyear  = substr($sdate,0,4);
      return $cmonth.'/'.$cday.'/'.$cyear ;
}

function dispCarCalendar($month, $year, $mainprog) {
     $now   = date("Y-m-d");
     $curday   = substr($now,8,2);
     $curmonth = substr($now,5,2);
     $curyear  = substr($now,0,4);
     $monthList = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

     // echo $curday."-".$curmonth."-".$curyear ;
     $i = 0;
     $days = getDaysInMonth($month,$year);
     $firstOfMonth = mktime(0,0,0,$month, 1, $year);
     $startingPos = date("w", $firstOfMonth);
     $days += $startingPos;
     // echo $days."-".$startingPos ;
     echo("<h3><center>".$monthList[$month-1]." - ".$year."</center></h3>") ;
     echo(" <pre> Su Mo Tu We Th Fr Sa");
     echo("<br> --------------------");
     for ($i = 0; $i < $startingPos; $i++) {
          if ( $i%7 == 0 ) echo( "<br> ");
          echo("   ");
          }
     for ($i = $startingPos; $i < $days; $i++) {
          if ( $i%7 == 0 ) echo( "<br> ");
          if (($i-$startingPos+1 == $curday) && ($month == $curmonth) && ($year == $curyear) )
             echo( "<strong>");
          echo ("<a href='$mainprog?date=");
          if ($i-$startingPos+1 < 10)
               echo("0");
          echo($i-$startingPos+1);
          echo ("&month=".$month."&year=".$year."' title='Click Here to see reservation status for this date'>");
          if ($i-$startingPos+1 < 10)
               echo("0");
          echo($i-$startingPos+1);
          if (($i-$startingPos+1 == $curday) && ($month == $curmonth) && ($year == $curyear) )
             echo( "</strong>");
          echo("</a> ");
          }
     for ($i=$days; $i<42; $i++)  {
          if ( $i%7 == 0 ) echo("<br>");
          echo("   ");
          }

     echo("<br>  <br> <a href='$mainprog?".setPreviousYear($month,$year)."' title='Previous Year'><<</a>  ");
     echo("<a href='$mainprog?".setPreviousMonth($month,$year)."' title='Previous Month'><</a>  ");
     echo("<a href='$mainprog?".setToday()."'>Today</a>") ;
     echo("  <a href='$mainprog?".setNextMonth($month,$year)."' title='Next Month'>></a>  ") ;
     echo("<a href='$mainprog?".setNextYear($month,$year)."' title='Next Year'>>></a>") ;
     echo("<br>");
     }

function displayCar($conn,$driver,$queryDate) {
     $myquery = "SELECT * FROM Car where Driver = '".$driver."' AND Rdate = ".$queryDate ;
     while (strlen($driver) < 21) {
          $driver = $driver." " ;
          }
     echo "<br>   ".$driver." :  " ;
     $result = odbc_exec($conn,$myquery);
     $n = 0 ;
     while (odbc_fetch_row($result)){
          $res_id  = odbc_result ($result, "Res_id");
          $sch_id[$n] = $res_id ;
     //       $room  = odbc_result ($result, "Driver");
          $name  = odbc_result ($result, "Res_by");
          $sch_name[$n] = $name ;
     //       $date  = odbc_result ($result, "Rdate");
          $from = odbc_result ($result, "From_time");
          $sch_from[$n] = $from ;
          $to  = odbc_result ($result, "To_time");
          $sch_to[$n] = $to ;
          $dest[$n] = odbc_result ($result, "Destination");
     //       $comment = odbc_result ($result, "Comment");
          $n++ ;
     }

     for ($j=0;$j < 24;$j++){
          $mytime = 8+$j*0.5 ;
          $filler = "--" ;
          for($i=0; $i < sizeof($sch_from); $i++) {
               $bottom = substr($sch_from[$i],-8,2) ;
               if (intval(substr($sch_from[$i],-5,2)) > 30) $bottom = intval($bottom) + 0.5 ;
               $ceil = substr($sch_to[$i],-8,2) ;
               if (intval(substr($sch_to[$i],-5,2)) >= 30) $ceil = intval($ceil) + 0.5 ;
               if (($mytime>=$bottom) && ($mytime<$ceil))
                    $filler = "<a href='car_dtl.php3?resid=".$sch_id[$i]."' title='Reserved by ".$sch_name[$i]." to ".$dest[$i]."'>**</a>" ;
               //echo $mytime." - ".$bottom.",".$ceil ;
          }
          echo $filler ;
     }

     unset($sch_from) ;
     unset($sch_to) ;
     unset($sch_name) ;

}

function waitingListCar($conn) {
     $myquery = "SELECT * FROM Car where Driver = 'Wait' order by res_id" ;
     $result = odbc_exec($conn,$myquery);
     $n = 0 ;
     echo ("<TABLE Border='1'><TR bgcolor='dcdcdc'><TH>No.<TH>Name<TH>Date<TH>From<TH>To<TH>Destination</TR>") ;
     while (odbc_fetch_row($result)){
     $res_id  = odbc_result ($result, "Res_id");
//       $room  = odbc_result ($result, "Room");
     $name  = odbc_result ($result, "Res_by");
     $date  = odbc_result ($result, "Rdate");
     $from = odbc_result ($result, "From_time");
     $to  = odbc_result ($result, "To_time");
     $dest = odbc_result ($result, "Destination");
//       $comment = odbc_result ($result, "Comment");
     $n++ ;
     echo ("<tr><td>".$n."<td>".$name."<td>".substr($date,8,2)."-".substr($date,5,2)."-".substr($date,0,4).
           "<td>".substr($from,-8,5)."<td>".substr($to,-8,5).
           "<td>".$dest.
           "</tr>") ;
     }
}


function admWaitListCar($conn) {
     $myquery = "SELECT * FROM Car where Driver = 'Wait' order by res_id" ;
     $result = odbc_exec($conn,$myquery);
     $n = 0 ;
     echo ("<TABLE Border='1'><TR bgcolor='dcdcdc'><TH>No.<TH>Requested by<TH>Date<TH>From<TH>To<TH>Destination<TH>Assign to</TR>") ;
     while (odbc_fetch_row($result)){
     $res_id  = odbc_result ($result, "Res_id");
     $driver  = odbc_result ($result, "Driver");
     $name  = odbc_result ($result, "Res_by");
     $date  = odbc_result ($result, "Rdate");
     $from = odbc_result ($result, "From_time");
     $to  = odbc_result ($result, "To_time");
     $dest = odbc_result ($result, "Destination");
//       $comment = odbc_result ($result, "Comment");
     $n++ ;
     echo ("<tr><td><a href='car_dtl.php3?resid=".$res_id."'>".$n."</a>".
           "<td>".$name."<td>".substr($date,8,2)."-".substr($date,5,2)."-".substr($date,0,4).
           "<td>".substr($from,-8,5)."<td>".substr($to,-8,5).
           "<td>".$dest.
           "<td><form action='carasgn.php3' method='post'>".
           "<input type='hidden' name='res_id' value='".$res_id."'>") ;
     echo "<select name='driver' size='1'>" ;
       //echo "<option " ; if ($driver == "Suparjo") echo "selected "; echo ">Suparjo</option>" ;
       echo "<option " ; if ($driver == "Winto") echo "selected "; echo ">Winto</option>" ;
       echo "<option " ; if ($driver == "Slamet") echo "selected "; echo ">Slamet</option>" ;
      // echo "<option " ; if ($driver == "Rochmat") echo "selected "; echo ">Rochmat</option>" ;
       echo "<option " ; if ($driver == "Ujang") echo "selected "; echo ">Ujang</option>" ;
      // echo "<option " ; if ($driver == "Dayat") echo "selected "; echo ">Dayat</option>" ;
       echo "<option " ; if ($driver == "Mahmudi") echo "selected "; echo ">Mahmudi</option>" ;
        //   echo "<option " ; if ($driver == "Rochmat") echo "selected "; echo ">Rochmat</option>" ;
       echo "<option " ; if ($driver == "Wait") echo "selected "; echo ">Wait</option>" ;
       echo "</select><input type='submit' value=' Go '></tr></form> " ;
     }
}


function dispRoomCalendar($month, $year,$mainprog) {
     $mainprog .= "?item=room&" ;
     $now   = date("Y-m-d");
     $curday   = substr($now,8,2);
     $curmonth = substr($now,5,2);
     $curyear  = substr($now,0,4);
     $monthList = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

     // echo $curday."-".$curmonth."-".$curyear ;
     $i = 0;
     $days = getDaysInMonth($month,$year);
     $firstOfMonth = mktime(0,0,0,$month, 1, $year);
     $startingPos = date("w", $firstOfMonth);
     $days += $startingPos;
     // echo $days."-".$startingPos ;
     echo("<h3><center>".$monthList[$month-1]." - ".$year."</center></h3>") ;
     echo(" <pre> Su Mo Tu We Th Fr Sa");
     echo("<br> --------------------");
     for ($i = 0; $i < $startingPos; $i++) {
          if ( $i%7 == 0 ) echo( "<br> ");
          echo("   ");
          }
     for ($i = $startingPos; $i < $days; $i++) {
          if ( $i%7 == 0 ) echo( "<br> ");
          if (($i-$startingPos+1 == $curday) && ($month == $curmonth) && ($year == $curyear) )
             echo( "<strong>");
          echo ("<a href='$mainprog"."date=");
          if ($i-$startingPos+1 < 10)
               echo("0");
          echo($i-$startingPos+1);
          echo ("&month=".$month."&year=".$year."' title='Click Here to see reservation status for this date'>");
          if ($i-$startingPos+1 < 10)
               echo("0");
          echo($i-$startingPos+1);
          if (($i-$startingPos+1 == $curday) && ($month == $curmonth) && ($year == $curyear) )
             echo( "</strong>");
          echo("</a> ");
          }
     for ($i=$days; $i<42; $i++)  {
          if ( $i%7 == 0 ) echo("<br>");
          echo("   ");
          }

     echo("<br> <br> <a href='$mainprog".setPreviousYear($month,$year)."' title='Previous Year'><<</a>  ");
     echo("<a href='$mainprog".setPreviousMonth($month,$year)."' title='Previous Month'><</a>  ");
     echo("<a href='$mainprog".setToday()."'>Today</a>") ;
     echo("  <a href='$mainprog".setNextMonth($month,$year)."' title='Next Month'>></a>  ") ;
     echo("<a href='$mainprog".setNextYear($month,$year)."' title='Next Year'>>></a>") ;
     echo("<br>");
     }

function displayRoom($conn,$roomname,$queryDate) {
     $myquery = "SELECT * FROM room where room = '".$roomname."' AND Rdate = ".$queryDate ;
     // $myquery = "SELECT * FROM room where room = '".$roomname."' AND Rdate > #08/02/2016# AND Rdate < #08/03/2016# " ;
     // echo $myquery ;
     // below is temporary name for mainboard
         //if ($roomname == "Mainboard") $roomname = "Boardroom A&B" ;

$arr_capacity["Sumatra"] = "(10,S)" ;
$arr_capacity["Kalimantan"] = "(8,S)" ;
$arr_capacity["Sulawesi"] = "(4,P)" ;
$arr_capacity["Bali"] = "(20,S)" ;        
$arr_capacity["Timor"] = "(4,P)" ;
$arr_capacity["Halmahera"] = "(8,S)" ;
$arr_capacity["Papua"] = "(10,S)" ;
$room_capacity = $arr_capacity[$roomname] ;
$roomname = $roomname." ".$room_capacity ;

	while (strlen($roomname) < 22) {
          $roomname = $roomname." " ;
          }
         echo "<br>   ".$roomname." :  " ;
     $result = odbc_exec($conn,$myquery);
     $n = 0 ;
     while (odbc_fetch_row($result)){
     $res_id  = odbc_result ($result, "Res_id");
     $sch_id[$n] = $res_id ;
//       $room  = odbc_result ($result, "Room");
     $name  = odbc_result ($result, "Res_by");
     $sch_name[$n] = $name ;
//       $date  = odbc_result ($result, "Rdate");
     $from = odbc_result ($result, "From_time");
     $sch_from[$n] = $from ;
     $to  = odbc_result ($result, "To_time");
     $sch_to[$n] = $to ;
     $client = odbc_result ($result, "Client");
     $sch_client[$n] = $client ;
//       $comment = odbc_result ($result, "Comment");
     $n++ ;
     }

     for ($j=0;$j < 24;$j++){
          $mytime = 8+$j*0.5 ;
          $filler = "--" ;
          for($i=0; $i < sizeof($sch_from); $i++) {
               $bottom = substr($sch_from[$i],-8,2) ;
               if (intval(substr($sch_from[$i],-5,2)) > 30) $bottom = intval($bottom) + 0.5 ;
               $ceil = substr($sch_to[$i],-8,2) ;
               if (intval(substr($sch_to[$i],-5,2)) >= 30) $ceil = intval($ceil) + 0.5 ;
               if (($mytime>=$bottom) && ($mytime<$ceil))
                    $filler = "<a href='room_dtl.php3?resid=".$sch_id[$i].
                    "' title='Reserved by ".$sch_name[$i]." for Client : ".addslashes($sch_client[$i])."'>**</a>" ;
               //echo $mytime." - ".$bottom.",".$ceil ;
          }
          echo $filler ;
     }

     unset($sch_from) ;
     unset($sch_to) ;
     unset($sch_name) ;

}

function waitingListRoom($conn) {
     $myquery = "SELECT * FROM room where room = 'Wait' order by res_id" ;
     $result = odbc_exec($conn,$myquery);
     $n = 0 ;
     echo ("<TABLE Border='1'><TR bgcolor='dcdcdc'><TH>No.<TH>Request by<TH>Date<TH>From<TH>To</TR>") ;
     while (odbc_fetch_row($result)){
     $res_id  = odbc_result ($result, "Res_id");
//       $room  = odbc_result ($result, "Room");
     $name  = odbc_result ($result, "Res_by");
     $date  = odbc_result ($result, "Rdate");
     $from = odbc_result ($result, "From_time");
     $to  = odbc_result ($result, "To_time");
//       $client = odbc_result ($result, "Client");
//       $comment = odbc_result ($result, "Comment");
     $n++ ;
     echo ("<tr><td>".$n."<td>".$name."<td>".substr($date,8,2)."-".substr($date,5,2)."-".substr($date,0,4).
           "<td>".substr($from,-8,5)."<td>".substr($to,-8,5).
           "</tr>") ;
     }
}

function admWaitListRoom($conn) {
     $myquery = "SELECT * FROM room where room = 'Wait' order by res_id" ;
     $result = odbc_exec($conn,$myquery);
     $n = 0 ;
     echo ("<TABLE Border='1'><TR bgcolor='dcdcdc'><TH>No.<TH>Requested by<TH>Date<TH>From<TH>To<TH>Assign to</TR>") ;
     while (odbc_fetch_row($result)){
     $res_id  = odbc_result ($result, "Res_id");
     $room  = odbc_result ($result, "Room");
     $name  = odbc_result ($result, "Res_by");
     $date  = odbc_result ($result, "Rdate");
     $from = odbc_result ($result, "From_time");
     $to  = odbc_result ($result, "To_time");
//       $client = odbc_result ($result, "Client");
//       $comment = odbc_result ($result, "Comment");
     $n++ ;
     echo ("<tr><td><a href='room_dtl.php3?resid=".$res_id."'>".$n."</a><td>".$name."<td>".substr($date,8,2)."-".substr($date,5,2)."-".substr($date,0,4).
           "<td>".substr($from,-8,5)."<td>".substr($to,-8,5).
           "<td><form action='roomasgn.php3' method='post'>".
           "<input type='hidden' name='res_id' value='".$res_id."'>") ;
      echo "<select name='roomname' size='1'>" ;
      echo "<option " ; if ($room == "Main Boardroom") echo "selected "; echo ">Jawa</option>" ;
          echo "<option " ; if ($room == "Boardroom A") echo "selected "; echo ">Jawa Timur</option>" ;
          echo "<option " ; if ($room == "Boardroom B") echo "selected "; echo ">Jawa Tengah</option>" ;
          echo "<option " ; if ($room == "Boardroom C") echo "selected "; echo ">Jawa Barat</option>" ;
          echo "<option " ; if ($room == "Boardroom A and B") echo "selected "; echo ">Jawa Timur and Tengah</option>" ;
          echo "<option " ; if ($room == "Boardroom B and C") echo "selected "; echo ">Jawa Barat and Tengah</option>" ;
          echo "<option " ; if ($room == "Boardroom and Hallway") echo "selected "; echo ">Jawa and Hallway</option>" ;
          //echo "<option " ; if ($room == "Boardroom") echo "selected "; echo ">Boardroom</option>" ;
      	echo "<option " ; if ($room == "Bangka Belitung") echo "selected "; echo ">Bangka Belitung</option>" ;
      	echo "<option " ; if ($room == "Sumatra") echo "selected "; echo ">Sumatra</option>" ;
      	echo "<option " ; if ($room == "Kalimantan") echo "selected "; echo ">Kalimantan</option>" ;
      	echo "<option " ; if ($room == "Sulawesi") echo "selected "; echo ">Sulawesi</option>" ;
      	echo "<option " ; if ($room == "Bali") echo "selected "; echo ">Bali</option>" ;
      	echo "<option " ; if ($room == "Timor") echo "selected "; echo ">Timor</option>" ;
      	echo "<option " ; if ($room == "Halmahera") echo "selected "; echo ">Halmahera</option>" ;
      	echo "<option " ; if ($room == "Papua") echo "selected "; echo ">Papua</option>" ;
            echo "<option " ; if ($room == "Lvl. 3 Meeting Room") echo "selected "; echo ">Lvl. 3 Meeting Room</option>" ;
            echo "<option " ; if ($room == "Canceled") echo "selected "; echo ">Canceled</option>" ;
//      	echo "<option " ; if ($room == "Biak") echo "selected "; echo ">Biak</option>" ;
//      	echo "<option " ; if ($room == "Kenanga") echo "selected "; echo ">Kenanga</option>" ;
          //echo "<option " ; if ($room == "Flores") echo "selected "; echo ">Flores</option>" ;
      echo "<option " ; if ($room == "Wait") echo "selected "; echo ">Wait</option>" ;
      echo "</select><input type='submit' value=' Go '> " ;
      echo "</tr></form> " ;
     }
}



function checkAdmin($user){

     $adminlist[]="BAKERNET\JKTSPB" ;
     $adminlist[]="BAKERNET\JKTRECEPTION" ;
     $adminlist[]="BAKERNET\JKTIHT" ;
     $adminlist[]="BAKERNET\JKTJS"  ;
     $adminlist[]="BAKERNET\JKTCSO" ;
     $adminlist[]="BAKERNET\JKTRECEPTIONADM" ;
     $adminlist[]="BAKERNET\JKTMLS" ;
     $adminlist[]="BAKERNET\JKTRTI" ;
     $adminlist[]="BAKERNET\JKTMTN" ;

     $right = false ;
     for($i=0;$i < sizeof($adminlist);$i++){
          if(strtoupper($user)==$adminlist[$i]) $right = true ;
     }

     return $right ;
}

?>