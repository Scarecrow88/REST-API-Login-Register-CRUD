# Como es php sin ningún framework, solo ejecutamos
# desde la carpeta del src el servidor local de php 
# para que se ejecute la aplicación
php -S localhost:8080 -t ./src

# o

cd ./src
php -S localhost:8080

# Para instalar las dependencias necesarias para 
# ejecutar la aplicación, se pueden usar los 
# siguientes comandos:
sudo apt install php-mbstring

# Ejecutar cron job, en este caso para 5 minutos
crontab -e
*/5 * * * * /usr/bin/php /yourDirectory/v1.0.0/src/timerToken/updateToken.php >> /yourDirectory/v1.0.0/src/logs/token.log 2>&1