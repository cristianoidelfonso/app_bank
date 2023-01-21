## Criando container Docker MySQL [https://hub.docker.com/_/mysql]
docker pull mysql
docker run --name mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=123456 -d mysql

## Criando container Docker Adminer [https://hub.docker.com/_/adminer]
docker pull adminer
docker run --name adminer --link mysql:mysql -p 8080:8080 -d adminer

## Criando container Docker Mailer [https://hub.docker.com/r/schickling/mailcatcher]
docker pull schickling/mailcatcher
docker run -d -p 1080:1080 --name mailcatcher schickling/mailcatcher

## Listando containers docker
docker ps