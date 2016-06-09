<?php
// fill in which metadata entries are to be displayed in the result table
// 'name of the entry' => 'default' entry if tag is not present
$displayTags = array(
    'name' => 'n.a.',
    'number' => 't.b.a.',
    'status' => 'n.a.',
  //'editor' => 't.b.a.',
  //'contributers' => 'n.a.',
    'submission_date' => 'n.a.',
    'download' => 'unavailable',
    'webView' => 'unavailable'
    );
// fill in which metadata entries should be made available in the search form
$searchTags = array ("status", "editor", "workpackage");

