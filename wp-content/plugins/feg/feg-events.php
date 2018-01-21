<?php
/*
This file adds the shortcode [feg_events]. Here are some examples:
- [feg_events]: HTML output of the next 5 events for all ChurchTools calendars events for the coming 3 weeks
- [feg_events limit="3"]: Limit output to 3
- [feg_events calendar="8,11"]: Only get events for the calendars/categories with the IDs 8 and 11. See churchtools.php for a list of available calendars/categories.
- [feg_events weeks="5"]: Increase the search for events to the coming 5 weeks.
*/
defined('ABSPATH') or die('No script!'); // Prevents direct access
include_once('churchtools.php');

add_shortcode('feg_events', 'feg_events_shortcode');

function feg_events_shortcode($attributes) {
  // Parse attributes with defaults
  $a = shortcode_atts( array(
    'limit' => 5,
    'calendar' => null,
    'weeks' => 3,
  ), $attributes );
  if ($a['calendar']) {
    $a['calendar'] = explode(',', $a['calendar']);
  }
  $events = getCalenderEvents($a['calendar'], 0, 7 * $a['weeks']);
  $limitedEvents = array_slice($events, 0, $a['limit']);
  $html = '';
  foreach($limitedEvents as $event) {
    $ort = isset($event->ort) ? $event->ort : '';
    $startDateTime = new DateTime($event->startdate);
    $endDateTime = new DateTime($event->enddate);
    $date = $startDateTime->format('d.m.Y');
    $startTime = $startDateTime->format('H:i');
    $endTime = $endDateTime->format('H:i');
    $html = $html . <<<HTML
<div class="fegEvent">
  <div class="fegEvent-title">$event->bezeichnung</div>
  <div class="fegEvent-date">$date</div>
  <div class="fegEvent-startTime">$startTime - $endTime</div>
  <div class="fegEvent-location">$ort</div>
</div>
HTML;
  }
  return $html;
}
