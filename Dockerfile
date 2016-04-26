FROM whitegecko/docker-nginx-php

RUN mkdir /var/www/html2

WORKDIR /var/www/html2

ADD ["./index.html", "./labels.js", "./formhandler.js", "./nginx_site", "composer.json", "/var/www/html2/"]

RUN cd /var/www/html2 && cp -f nginx_site /etc/nginx/sites-enabled/default

RUN cd /var/www/html2 && php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php && php composer-setup.php && php -r "unlink('composer-setup.php');" && ./composer.phar install

ADD ["./requestHandler.php", "./documentHandler.php", "./deleteFile.php", "./config.php", "secret.p12", "/var/www/html2/"]

CMD ["/usr/bin/supervisord"]
