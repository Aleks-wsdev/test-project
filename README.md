
docker compose build

docker compose up -d

make bash or docker compose exec  --workdir=/var/www php81-cli bash

cd api

composer install

php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

php bin/console doctrine:fixtures:load -n

cd ../cli

composer install

php ./console.php api:list-groups-users

php ./console.php list