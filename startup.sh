#!/bin/bash

    echo "Creating Nginx config file"
    cat <<EOL > /home/site/default
    server {
        listen 8080;
        server_name ecomtest-staging.azurewebsites.net;
        root /home/site/wwwroot/public;

        index index.php index.html index.htm;

        location / {
            try_files \$uri /index.php\$is_args\$args;
        }

        location ~ \.php\$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
            fastcgi_param PATH_INFO \$fastcgi_path_info;
            fastcgi_param HTTPS on;
        }

        error_page 404 /index.php;

        location ~ /\.ht {
            deny all;
        }
    }
EOL

    cp /home/site/default /etc/nginx/sites-available/default
    cp /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default
    pkill -o -USR2 php-fpm
    service nginx reload

   
    cd /home/site/wwwroot
    echo "Clearing symfony cache..."
    php bin/console cache:clear --env=prod
    echo "Running Doctrine migrations..."
    php bin/console doctrine:migrations:migrate --no-interaction

