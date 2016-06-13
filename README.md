## AKSW Google Document Viewer ##

The purpose of this application is to enable a view of the bachelor and master theses offered by the AKSW group at the University of Leipzig.
These are to be managed and organized in a collaborative Google document folder. This tool uses the Google Drive REST API for PHP to nagivate inside this folder and JavsScript with JQuery to display the contents on our website.

# dependencies #

1. PHP 5.2.1 or higher
2. Google APIs Client Library for PHP (https://github.com/google/google-api-php-client)

# deploy #

0. Run `git clone`, check dependencies and/or run `composer install` (resp. `./composer.phar install`, http://getcomposer.org/)
1. Register at the Google developer console (https://console.developers.Google.com/) and create a new project
2. Enable and manage APIs ( resp. check "APIs & auth") and search for "Drive API" and enable it
3. Go to "Credentials" (in the left menu) and generate a new ID, by selecting "Create credentials" and choosing "Service account key"
4. In the next screen you can select which service account to use (App Engine, Compute Engine or a new Service Account)
5. Generate a new .p12 key and keep it save
6. Copy the `/backend/serviceconfigs/googleDrive.ini.tpl` file to `/backend/serviceconfigs/googleDrive.ini.` and fill in the given project credentials
7. Copy the `/backend/config.php.tpl` file to `/backend/config.php.` and fill in the `$searchTags`and `$displayTags` you want to use.
8. Create a new folder in your Google drive and share it with the Google service account email address or add a folder to your drive which the Google service account can access

# manual #

* please see Manual.md

# further development # 

* please see wiki section on how to implement adapters for different cloud services
