#!/bin/bash
echo -e "33[1mВведите название проекта (Например example.com):33[0m";
read NAME_OF_PROJECT

#создаем папки проекта
sudo mkdir /home/serg/www/$NAME_OF_PROJECT

#добавляем правила в конфигурационый файл апача
add_to_apache_conf="
<VirtualHost *:80>
	ServerName ${NAME_OF_PROJECT}
	ServerAdmin webmaster@localhost
	DocumentRoot /home/serg/www/${NAME_OF_PROJECT}/
	<Directory /home/serg/www/${NAME_OF_PROJECT}/>
	Options Indexes FollowSymLinks MultiViews
	AllowOverride All
	DirectoryIndex index.php index.html index.htm
	php_admin_value short_open_tag On
	php_admin_value mbstring.func_overload 2
	php_admin_value mbstring.internal_encoding UTF-8
	php_admin_value date.timezone Europe/Moscow
	php_admin_value opcache.revalidate_freq 0
	php_admin_value opcache.max_accelerated_files 100000
	php_admin_value display_errors On
	php_admin_value max_input_vars 10000
	php_admin_value upload_max_filesize 8M
	Require all granted
	</Directory>
	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>"

#добавляем новый хост
touch /etc/apache2/sites-available/${NAME_OF_PROJECT}.conf
echo "$add_to_apache_conf" >> /etc/apache2/sites-available/${NAME_OF_PROJECT}.conf

# добавляем домен в hosts
echo "127.0.0.1 ${NAME_OF_PROJECT}" >> /etc/hosts

#включаем конфигурацию сайта
sudo a2ensite ${NAME_OF_PROJECT}

#ставим права 777
chmod -R 777 //home/serg/www/${NAME_OF_PROJECT}

#перезапускаем апач
sudo systemctl restart apache2

echo "Сайт готов"
