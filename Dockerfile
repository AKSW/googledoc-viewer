FROM whitegecko/docker-nginx-php

RUN mkdir /var/www/html2

WORKDIR /var/www/html2

ADD ["./nginx_site", "./requestHandler.php", "./documentHandler.php", "./deleteFile.php", "./config.php", "composer.json", "secret.p12", "/var/www/html2/"]
ADD ["./index.html", "./labels.js", "./formhandler.js", "/var/www/html2/"]

RUN cd /var/www/html2 && cp -f nginx_site /etc/nginx/sites-enabled/default

RUN cd /var/www/html2 && php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php && php composer-setup.php && php -r "unlink('composer-setup.php');" && ./composer.phar install

CMD ["/usr/bin/supervisord"]
