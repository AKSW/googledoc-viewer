## AKSW Google Document Viewer ##

The purpose of this application is to enable a view of the bachelor and master theses offered by the AKSW group at the University of Leipzig.
These theses are to be managed and organized in a collaborative Google document folder. This tool uses the Google Drive REST API for PHP to nagivate inside this folder and to display the contents on our website.

# dependencies #

1. PHP 5.2.1 or higher
2. Google APIs Client Library for PHP (https://github.com/google/google-api-php-client)

# deploy #

0. git clone, check dependencies and/or use composer.json
1. register at the google developer console (https://console.developers.google.com/) and create a project
2. check "APIs & auth" and search for "Drive API" and enable it
3. go "Credentials" and generate a new ID, choose "Service account"
4. follow "Creating a service account" on https://developers.google.com/api-client-library/php/auth/service-accounts
5. fill in the config with the given project credentials and rename it to config.php
