<?php
defined( 'ABSPATH' ) or die( 'No script!' );
$cookies = array();

function getCookies() {
  global $cookies;
  $res = "";
  //foreach (($cookies || array()) as $key => $cookie) {
  //  $res .= "$key=$cookie; ";
  //}
  return $res;
}

function saveCookies($r) {
  global $cookies;
  foreach ($r as $hdr) {
    if (preg_match('/^Set-Cookie:\s*([^;]+)/', $hdr, $matches)) {
      parse_str($matches[1], $tmp);
      $cookies += $tmp;
    }
  }
}

function sendChurchToolsRequest($data) {
  $url = 'https://churchtools.feg-rheinbach.de/index.php?q=churchcal/ajax';
  $options = array(
    'http'=> array(
      'header' => "Cookie: " . getCookies() . "\r\nContent-type: application/x-www-form-urlencoded\r\n",
      'method' => 'POST',
      'content' => http_build_query($data),
    )
  );
  $context = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  $obj = json_decode($result);
  if ($obj->status == 'error') {
    echo "There is an error: $obj->message";
    return array();
  } else if ($obj->status == 'fail') {
    echo "There is an error: $obj->data";
    return array();
  } else {
    //saveCookies($http_response_header);
    return $obj->data;
  }
}

// The following calendars (= categories) are currently available:
// "id": "2", "bezeichnung": "Gottesdienste" 
// "id": "3", "bezeichnung": "Junge Generation" 
// "id": "8", "bezeichnung": "Regelm\u00e4\u00dfige Veranstaltungen"
// "id": "11", "bezeichnung": "Schulferien NRW"
// "id": "16", "bezeichnung": "Besondere Veranstaltungen" 
// "id": "17", "bezeichnung": "Pfadfinder"
function getCalenderEvents($categories, $from = 0, $to = 21) {
  $categories = $categories ?: [2,3,8,16];
  $data = array(
    'func' => 'getCalendarEvents', 
    'category_ids' => $categories,
    'from' => $from,  
    'to' => $to);
  $events = sendChurchToolsRequest($data);
  // Sort by date:
  usort($events, function($a, $b) {
    return strtotime($a->startdate) - strtotime($b->startdate);
  });
  return $events;
}
?>