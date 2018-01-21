<?php
/*
This file adds the shortcode [feg_events]. Here are some examples:
- [feg_events]: HTML output of the next 5 events for all ChurchTools calendars events for the coming 3 weeks
- [feg_events limit="3"]: Limit output to 3
- [feg_events calendar="8,11"]: Only get events for the calendars/categories with the IDs 8 and 11. See churchtools.php for a list of available calendars/categories.
- [feg_events weeks="5"]: Increase the search for events to the coming 5 weeks.
- [feg_events short="1"]: Uses short names month and day of the week.
*/
defined('ABSPATH') or die('No script!'); // Prevents direct access
include_once('churchtools.php');

add_shortcode('feg_events', 'feg_events_shortcode');

function feg_events_shortcode($attributes) {
  //-- Initialisations
  $currentLocal = setlocale(LC_TIME, 0);
  setlocale (LC_TIME, 'de');
  //-- Parse attributes with defaults
  $a = shortcode_atts( array(
    'limit' => 5,
    'calendar' => null,
    'weeks' => 3,
    'short' => 0
  ), $attributes );
  if ($a['calendar']) {
    $a['calendar'] = explode(',', $a['calendar']);
  }
  $events = getCalenderEvents($a['calendar'], 0, 7 * $a['weeks']);
  $limitedEvents = array_slice($events, 0, $a['limit']);
  $html = '';
  foreach($limitedEvents as $event) {
    $ort = isset($event->ort) ? $event->ort : '';
    //-- Start date
    $startDateTime = strtotime($event->startdate);    // timestamp
    $startTime = strftime('%H:%M', $startDateTime);   // e.g. '16:00'
    $startDay = strftime('%d', $startDateTime);       // e.g. '07'
    $startWeekday = strftime($a['short'] ? '%a' : '%A', $startDateTime); // e.g. 'Mittwoch' or 'Mi'
    $startMonth = strftime($a['short'] ? '%b' : '%B', $startDateTime);   // e.g. 'November' or 'Nov'
    $startDate = strftime('%d.%m.', $startDateTime);  // e.g. '07.11.'
    //-- End date
    $endDateTime = strtotime($event->enddate);
    $endTime = strftime('%H:%M', $endDateTime);
    $endDay = strftime('%d', $endDateTime);
    $endMonth = strftime($a['short'] ? '%b' : '%B', $endDateTime);
    $endDate = strftime('%d.%m.', $endDateTime);
    //-- Resulting time string
    $timeString = '';
    if ($startDay === $endDay && $startMonth === $endMonth) {
      $timeString = "$startTime &ndash; $endTime";
    } else {
      $timeString = "$startDate@$startTime &ndash; $endDate@$endTime";
    }
    //-- Append html
    $html = $html . <<<HTML
<div class="fegEvent">
  <div class="fegEvent-dateContainer">
    <div class="fegEvent-month">$startMonth</div>
    <div class="fegEvent-day">$startDay</div>
    <div class="fegEvent-weekday">$startWeekday</div>
  </div>
  <div class="fegEvent-detailContainer">
    <div class="fegEvent-time">$timeString</div>
    <div class="fegEvent-title">$event->bezeichnung</div>
    <div class="fegEvent-location">$ort</div>
  </div>
</div>
HTML;
  }
  // Clean-up
  setlocale (LC_TIME, $currentLocal);
  return $html;
}
