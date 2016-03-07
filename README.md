## AKSW Google Document Viewer ##

The purpose of this application is to enable a view of the bachelor and master theses offered by the AKSW group at the University of Leipzig.
These are to be managed and organized in a collaborative Google document folder. This tool uses the Google Drive REST API for PHP to nagivate inside this folder and JavsScript with JQuery to display the contents on our website.

# dependencies #

1. PHP 5.2.1 or higher
2. Google APIs Client Library for PHP (https://github.com/google/google-api-php-client)

# deploy #

0. Run `git clone`, check dependencies and/or run `composer install` (resp. `./composer.phar install`, http://getcomposer.org/)
1. Register at the google developer console (https://console.developers.google.com/) and create a new project
2. Enable and manage APIs ( resp. check "APIs & auth") and search for "Drive API" and enable it
3. Go to "Credentials" (in the left menu) and generate a new ID, by selecting "Create credentials" and choosing "Service account key"
4. In the next screen you can select which service account to use (App Engine, Compute Engine or a new Service Account)
5. Generate a new .p12 key and keep it save
6. Copy the `config.tpl` file to `config.php` and add the given project credentials
7. if you don't want to impersonate a specific user, leave `$user_to_impersonate = '';` blank, create a new folder in your google drive and share with the google service account email adress or add a folder to your drive which the google service account can access

note: there seems to be a problem with impersonating a @gmail adress

# manual #

* please see Manual.md
