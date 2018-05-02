#!/bin/bash

##### CI #####
composer install
php artisan key:generate --env=testing
#php artisan migrate --force
#./vendor/bin/phpunit #No tests yet

#### CD ####
rsync -vzrh --exclude-from="deploy_exclude.txt" . ubuntu@34.246.63.12:/var/www/Station-manager-api/temp

ssh ubuntu@34.246.63.12 <<-EOF
    cd /var/www/Station-manager-api
    sudo rm -rf ./backup # Delete previous backup
    sudo mv ./live ./backup # Create new backup
    sudo mv ./temp ./live
    sudo mkdir ./temp # create new temp directory for next deployment
    sudo cp ./lara-config/.env ./live
    cd ./live
    sudo composer install --no-dev --optimize-autoloader --no-plugins --no-scripts
    sudo composer update
    php artisan key:generate
    #php artisan route:cache resolve closure based routes before caching
    php artisan migrate --force
    php artisan db:seed
    sudo chmod -R 755 .
    sudo chown -R www-data:www-data .
EOF