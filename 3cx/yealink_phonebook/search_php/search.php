<? 

 $URL = 'http://192.168.0.231/';
 $per_page = '10'; 

 $mysql_conn = mysql_connect('localhost', 'root', '');
 mysql_select_db('test', $mysql_conn );
    
$NAME=$_GET["name"];
$FROM=$_GET["FROM"];
$TO=$_GET["TO"];
 if ( ($FROM=='') and ($TO=='') )
 {
   //check to see how many 
   $result= mysql_query("SELECT count(phones.fname) as total 
                         FROM phones 
                         WHERE phones.fname LIKE '$NAME%' ", $mysql_conn);
   $howmany = mysql_fetch_row($result);

   if ($howmany[0] > $per_page)
   {
    $start = 0; 
    $index = 0;
    $total = $howmany[0];
    $remain = $per_page;
    print("\n");
    print("<YealinkIPPhoneDirectory>\n");  
   
    while ($start < ($total + 1))
    { 
      $limitstart = 'LIMIT '.$start.','.$per_page;
      $result = mysql_query("SELECT fname,phone 
                             FROM phones 
                             WHERE fname LIKE '$NAME%' ORDER BY fname $limitstart", $mysql_conn); 

      $row = mysql_fetch_row($result); 
      $from = $row[0];
      if (($total - $start) < $per_page) { $remain = $total - $start; }
      for ($i = 1; $i < $remain; ++$i) { $row = mysql_fetch_row($result); }
      $to = $row[0]; 
    
      print("<SoftKeyItem>\n"); 
      print("\t<Name>"); 
      print($index);         
      print("</Name>\n"); 
      print("\t<URL>"); 
      print($URL."search.php?FROM=".$from."&TO=".$to); 
      print("</URL>\n"); 
      print("</SoftKeyItem>\n"); 

      $start = $start + $per_page;
      $index = $index+1;
    } 
    print("</YealinkIPPhoneDirectory>\n"); 

   } else {

    $result = mysql_query("SELECT fname,lname,phone 
                           FROM phones 
                           WHERE phones.fname LIKE '$NAME%' 
                           ORDER BY fname ", $mysql_conn); 

    print("\n");
    print("<YealinkIPPhoneDirectory>\n");  

    while($row = mysql_fetch_row($result)) 
    { 
      print("<DirectoryEntry>\n"); 
      print("\t<Name>"); 
      print($row[0].", ".$row[1] ); 
      print("</Name>\n"); 
      print("\t<Telephone>"); 
      print($row[2]); 
      print("</Telephone>\n"); 
      print("</DirectoryEntry>\n"); 
    } 
    print("</YealinkIPPhoneDirectory>\n"); 
   }
   
   

 } else {

  $result = mysql_query("SELECT fname,lname,phone 
                         FROM phones 
                         WHERE fname>='$FROM' AND fname<='$TO' 
                         ORDER BY fname", $mysql_conn); 

   print("\n");
   print("<YealinkIPPhoneDirectory>\n");  

   while($row = mysql_fetch_row($result)) 
   { 
     print("<DirectoryEntry>\n"); 
     print("\t<Name>"); 
     print($row[0].", ".$row[1] ); 
     print("</Name>\n"); 
     print("\t<Telephone>"); 
     print($row[2]); 
     print("</Telephone>\n"); 
     print("</DirectoryEntry>\n"); 
   } 
   print("</YealinkIPPhoneDirectory>\n"); 
 }


?> 





