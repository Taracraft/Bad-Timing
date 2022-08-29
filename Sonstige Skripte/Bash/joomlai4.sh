#!/bin/bash
##Ich uebernehme Keine Haftung f�r Schaeden am System oder An der Hardware.
##############################################################################
joomlav=4-0-0 		# Die - Zeichen M�ssten so bleiben , oder . Funkttionieren nicht!
phpv=8.0			# Das . Zeichen Muss so bleiben - oder , Funkttioniert nicht!
#[Empfohlene PHP Version (7.5) und (8.x) Version 5.x Wird NICHT Empfohlen [Outdatet!]]

##############################################################################
#//Update System//#
echo -e "\e[01;32;32m System update ueberpruefung...\e[0m"
sleep 1
apt-get update
apt-get -y upgrade
apt-get -y autoremove
apt-get -y autoclean
sleep 1
if [ ! -f "$notwenp" ]; then
apt-get -y install sudo mc nano
else		
echo -e "\e[01;32;32m sudo mc nano ist bereits installiert!\e[0m"
sleep 2
fi

if [ ! -f "$apache" ]; then
apt-get -y install apache2
else 
echo -e "\e[01;32;32m Apache2 ist bereits installiert!\e[0m"
sleep 2
fi

if [ ! -f "$mysqls" ]; then
apt-get -y install mysql-server mysql-client
else
echo -e "\e[01;32;32m MySQL Server ist bereits installiert!\e[0m"
sleep 2
fi

echo -e "\e[01;32;32m Notwendige PHP Packete werden installiert\e[0m"
sleep 1
if [ ! -f "$phpi" ]; then
apt-get -y install php$phpv php-curl php-gd php-mbstring php-xml php-xmlrpc php-soap php-intl php-zip php-bcmath php-mysql php-json libapache2-mod-php$phpv
else
echo -e "\e[01;32;32m PHP, PHP-Erweiterungen sind bereits installiert!\e[0m"
sleep 2
fi
systemctl restart apache2
sleep 1
echo -e "\e[01;32;32m Datenbank erstellen\e[0m"
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
 if [ ! -f "Joomla_$joomlav-Stable-Full_Package.tar.gz" ]
 then wget https://downloads.joomla.org/cms/joomla4/$joomlav/Joomla_$joomlav-Stable-Full_Package.tar.gz
 fi
 cp  "Joomla_$joomlav-Stable-Full_Package.tar.gz" /var/www/html/
 cd /var/www/html
 tar -zxvf "Joomla_$joomlav-Stable-Full_Package.tar.gz"
 chown -R www-data:www-data /var/www/html/
 sleep 1
 echo
 echo "Username & Password & Datenbanknamen Bitte Aufschreiben"
 echo "Datenbank username: $username"
 echo "Datenbank Passwort: $userpass"
 echo "Datenbank Name: $dbname"
 sleep 60