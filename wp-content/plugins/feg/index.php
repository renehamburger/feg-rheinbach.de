<?php
/*
Plugin Name: FeG Rheinbach website plugin
Description: Functionality used by the WordPress site www.feg-rheinbach.de
Version: 0.0.1
Author: Rene Hamburger
*/
include_once('churchtools.php');

if (true || isset($wp_version)) {
  add_shortcode('feg_events', 'feg_events_shortcode');
} else {
  feg_events_shortcode(array());
}

function feg_events_shortcode( $attributes, $content = null ) {
  $events = getCalenderEvents();
  $html = '';
  foreach(array_slice($events, 0, 5) as $event) {
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
