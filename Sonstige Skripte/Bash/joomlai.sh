#!/bin/bash

#//Update System//#
 #echo "Joomla installiert?"
 #read ja
 #if read "ja"
 #echo "Ordner Rechte Werden geändert"
 #chown www-data:www-data /var/www/html
 #chmod 644 -R /var/www/html
 #if read "nein"
 #then
 
echo -e "\e[01;32;32m System update ueberpruefung...\e[0m"
sleep 1
apt-get update
apt-get -y upgrade
apt-get -y autoremove
apt-get -y autoclean
sleep 1
echo "Notwendige Programme Werden installiert"
apt-get -y install sudo
apt-get -y install mc
apt-get -y install nano
sleep 1
echo "Apache 2 Wird installiert"
apt-get -y install apache2
sleep 1
echo "MySql Server Wird installiert"
apt-get -y install mysql-server
sleep 1
echo "Notwendige PHP Packete werden installiert"
apt install -y php php-curl php-gd php-mbstring php-xml php-xmlrpc php-soap php-intl php-zip php-bcmath php-mysql php-json
systemctl restart apache2
sleep 1
echo "Datenbank erstellen"

	if [ -f /root/.my.cnf ]; then
        echo "Datei Exestiert";	
	else
	echo "Bitte gebe das root MySQL password ein!";
        read -p dbuser;
	echo "Hinweis: Das Password wird unsichtbar eingegeben!"
	read -s passwd;
        echo -e "[client]" >> /root/.my.cnf;
	echo -e "user=$dbuser\npassword=$passwd" >> /root/.my.cnf;
        exit;
	fi


 echo "Datenbank name eingeben!"
 read dbname
    
 echo "Erstelle neue MySQL Datenbank..."
 mysql -e "CREATE DATABASE ${dbname} /*\!40100 DEFAULT CHARACTER SET utf8 */;"
 echo "Datenbank erfolgreich erstellt!"
 
 echo "Datenbank Benutzer eingeben!"
 read username
 
 echo "Password fuer Datenbank Benutzer Eingeben!"
 echo "Hinweis: Das Password wird unsichtbar eingegeben!"
 read -s userpass
    
 echo "Erstelle neuen Benutzer..." 
 mysql -e "CREATE USER ${username}@localhost IDENTIFIED BY '${userpass}';"
 echo "Benutzer erfolgreich erzeugt!"

 echo "Gebe alle Rechte von Datenbank: ${dbname} zu Benutzer: ${username}!"
 mysql -e "GRANT ALL PRIVILEGES ON ${dbname}.* TO '${username}'@'localhost';"
 mysql -e "ALTER USER '$username'@'localhost' IDENTIFIED WITH mysql_native_password BY '$userpass';"
 mysql -e "FLUSH PRIVILEGES;"
 
 echo "joomla Download"
 sleep 2
 rm /var/www/html/index.html
 wget https://github.com/joomlagerman/joomla/releases/download/3.10.9v1/Joomla_3.10.9-Stable-Full_Package_German.tar.gz
 cp  "Joomla_3.10.9-Stable-Full_Package_German.tar.gz" /var/www/html/
 cd /var/www/html
 tar -xvzf /var/www/html/Joomla_3.10.9-Stable-Full_Package_German.tar.gz
  chown www-data:www-data -R /var/www/html
 chmod 777 -R /var/www/html 
 sleep 1
 echo
 echo "Username & Password & Datenbanknamen Bitte Aufschreiben"
 echo "Datenbank username: $username"
 echo "Datenbank Passwort: $userpass"
 echo "Datenbank Name: $dbname"
 sleep 60