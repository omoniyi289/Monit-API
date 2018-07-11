#!/bin/bash

##### CI #####
composer install
php artisan key:generate --env=testing
#php artisan migrate --force
#./vendor/bin/phpunit #No tests yet

#### CD ####
rsync -vzrhe "ssh -i /var/lib/jenkins/.ssh/e360_prod_kp2.pem -o StrictHostKeyChecking=no" --exclude-from="deploy_exclude.txt"  . ubuntu@34.246.63.12:/var/www/Station-manager-api/temp

ssh -i /var/lib/jenkins/.ssh/e360_prod_kp2.pem -o StrictHostKeyChecking=no ubuntu@34.246.63.12 <<-EOF

    cd /var/www/Station-manager-api
    sudo rm -rf ./backup # Delete previous backup
    sudo mv ./live ./backup # Create new backup
    sudo mv ./temp ./live
    sudo mkdir ./temp # create new temp directory for next deployment
    sudo chown -R ubuntu:ubuntu ./temp
    sudo cp ./lara-config/.env ./live
    cd ./live
    sudo composer install --no-dev --optimize-autoloader --no-plugins --no-scripts
    sudo composer update
    sudo php artisan key:generate
    #sudo php artisan route:cache #resolve closure based routes before caching
    sudo php artisan migrate --force
    sudo php artisan db:seed
    sudo php artisan queue:restart
    sudo chmod -R 755 .
    sudo chown -R www-data:www-data .
EOF