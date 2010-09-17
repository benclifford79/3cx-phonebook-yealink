<?
# PHP 5 Script to load 3CX XML phone book and allow searching from Yealink T2X style phones
# V 0.0.1 Initial commit
# Ben Clifford ben@hotel-broadband.com 17/09/2010


# Vars
#-------------------------------------------
$name=$_GET["name"];
$FROM=$_GET["FROM"];

$xml_pb 	= "pb.xml";
$search_string 	= "B";
$search_field 	= "display_name"; # Can be display_name, last_name or first_name
$xml 		= simplexml_load_file($xml_pb);

# Initial contact list parsed from XML
$contact_list 	= array();
# Filtered results array
$results_list 	= array();


# Main
#-------------------------------------------

parse_xml_phonebook($xml);

if ($name) {
  filter_results($name, true);
} else {
  filter_results("", false);
};

display_results();



## Subs
#-------------------------------------------

# Parse XML file
function parse_xml_phonebook() {
  global $xml;
  # Contacts
  foreach ($xml->children() as $contact) {
    # Contact items, e.g. Name, number
    $record = array();
    $display_name 	= '';
    $first_name		= '';
    $last_name		= '';
    $number 		= '';
    foreach ($contact->children() as $contact_item) {
      global $record;
      global $display_name;
      global $first_name;
      global $last_name;
      global $number;
            
      # Pull out DisplayName
      if (strcmp($contact_item->getName(),"DisplayName") == 0) {
        //echo "Display Name: $contact_item\n";
        $display_name = $contact_item;
      };

      # Pull out FirstName
      if (strcmp($contact_item->getName(),"FirstName") == 0) {
        //echo "First Name: $contact_item\n";
        $first_name = $contact_item;
      };
      
      # Pull out LastName
      if (strcmp($contact_item->getName(),"LastName") == 0) {
        //echo "Last Name: $contact_item\n";
        $last_name = $contact_item;
      };

      # Pull out Number
      if (strcmp($contact_item->getName(),"Number") == 0) {
        //echo "Number: $contact_item\n";
        $number = $contact_item;
      };
    };
    $record = array("display_name" => $display_name, "first_name" => $first_name, "last_name", "number" => $number);
    add_to_list($record);
  };
};

# Add parsed XML record to global array
function add_to_list($r) {
  global $contact_list;
  array_push($contact_list, $r);
};


# Filter results based on search parameter
function filter_results($search, $do_search) {
 global $contact_list;
 global $results_list;
 global $search_field;
 $str_length = strlen($search);
 foreach($contact_list as $c) {
  if ($do_search) {
    if (preg_match("/^${search}.*/i", $c[$search_field], $matches)) {
      //print "Search matched $search\n";
      array_push($results_list, $c);
    };
  } else {
     # No search criteria, push the whole list
     array_push($results_list, $c);
  };
 };
};


# Returns results to caller based on required format
function display_results() {
 global $results_list;
  print("<YealinkIPPhoneDirectory>\n");  
    foreach($results_list as $c)
    { 
      print("<DirectoryEntry>\n"); 
      print("\t<Name>"); 
      print($c['display_name']); 
      print("</Name>\n"); 
      print("\t<Telephone>"); 
      print($c['number']); 
      print("</Telephone>\n"); 
      print("</DirectoryEntry>\n"); 
    } 
    print("</YealinkIPPhoneDirectory>\n"); 
};



?>