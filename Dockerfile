FROM whitegecko/docker-nginx-php

RUN mkdir /var/www/html2 /var/www/html2/backend /var/www/html2/backend/documentHandler /var/www/html2/backend/serviceConfigs
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

WORKDIR /var/www/html2

ADD ["./index.html", "./nginx_site", "composer.json", "frontend/formhandler.js", "frontend/labels.js", "/var/www/html2/"]

RUN cd /var/www/html2 && cp -f nginx_site /etc/nginx/sites-enabled/default

RUN cd /var/www/html2 && php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php && php composer-setup.php && php -r "unlink('composer-setup.php');" && ./composer.phar install

ADD ["backend/config.php", "backend/requestHandler.php", "/var/www/html2/backend/"]

ADD ["backend/documentHandler/abstractDocumentHandler.php", "backend/documentHandler/documentHandlerMain.php", "backend/documentHandler/googleDriveHandler.php", "backend/documentHandler/"]

ADD ["backend/serviceConfigs/googleDrive.ini", "backend/serviceConfigs/configLoader.php", "backend/serviceConfigs/secret.p12", "backend/serviceConfigs/"]

CMD ["/usr/bin/supervisord"]
