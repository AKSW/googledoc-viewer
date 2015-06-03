== AKSW Google Document Viewer ==

The purpose of this application is to enable a view of the bachelor and master theses offered by the AKSW group at the University of Leipzig.
These theses are to be managed and organized in a collaborative Google document folder. This tool uses the Google Drive REST API for PHP to nagivate inside this folder and to display the contents on our website.

= dependencies =

PHP 5.2.1 or higher
Google APIs Client Library for PHP (https://github.com/google/google-api-php-client)

= deploy =

0. check dependencies and download the api for php
1. register at the google developer console and create an app
2. follow "Creating a service account" on https://developers.google.com/api-client-library/php/auth/service-accounts
3. git clone and fill in the config; rename it to config.php

