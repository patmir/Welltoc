#!/usr/bin/env bash
ROOTDBPASSWD=$2
G4ADBPASSWD=$3
#== Import script args ==

timezone=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
}

#== Provision script ==

info "Provision-script user: `whoami`"
info "Allocate swap for MySQL 5.6"
fallocate -l 2048M /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo '/swapfile none swap defaults 0 0' >> /etc/fstab

info "Configure locales"
update-locale LC_ALL="C"
dpkg-reconfigure locales

info "Configure timezone"
echo ${timezone} | tee /etc/timezone
dpkg-reconfigure --frontend noninteractive tzdata

info "Prepare root password for MySQL"
debconf-set-selections <<< "mysql-server mysql-server/root_password password $ROOTDBPASSWD"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $ROOTDBPASSWD"
echo "Done!"

info "Update OS software"
apt-get install -y language-pack-en-base
apt-get install software-properties-common
LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
apt-get update
apt-get upgrade -y

info "Install additional software"
apt-get install -y php-gettext apache2 php7.0-dev php-curl php7.0-curl php7.0-cli php7.0-intl php7.0-mysql php7.0-mysql php7.0-gd libapache2-mod-php7.0 php7.0-mcrypt mysql-server-5.6 git php7.0-mbstring php7.0-xml php7.0-zip unzip

info "Configure MySQL"
sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/my.cnf
echo "Done!"

info "install php7.0-fpm"
apt-get install -y php7.0-fpm
echo 'Done!'
a2enmod proxy_fcgi setenvif
a2enconf php7.0-fpm
info "Configure Apache2"

cp /vagrant/vagrant/apache2/000-default.conf /etc/apache2/sites-available/
cp /vagrant/vagrant/apache2/envvars /etc/apache2/
info "Installing dos2unix"
apt-get install dos2unix -y
apt-get install tofrodos -y
dos2unix /etc/apache2/envvars
service apache2 restart
echo "Done!"

info "Initailize databases for MySQL"
mysql -uroot -p$ROOTDBPASSWD <<< "CREATE DATABASE g4a_panel"
mysql -uroot -p$ROOTDBPASSWD <<< "CREATE DATABASE g4a_panel_tests"
echo "Done!"

a2enmod rewrite
phpenmod mcrypt

info "Installing PhpMyAdmin"
wget  "https://files.phpmyadmin.net/phpMyAdmin/4.6.4/phpMyAdmin-4.6.4-all-languages.zip" -P /usr/share/ -q
unzip /usr/share/phpMyAdmin-4.6.4-all-languages.zip -d /usr/share/
mv /usr/share/phpMyAdmin-4.6.4-all-languages /usr/share/phpmyadmin/
chmod -R 0755 /usr/share/phpmyadmin
echo "Done!"

echo "Installing WordPress"
wget "https://wordpress.org/latest.zip" -P /var/www/html/ -q
unzip /var/www/html/latest.zip -d /var/www/html/
rm -rf /var/www/html/index.html
mv /var/www/html/wordpress/* .
rm -rf /var/www/html/wordpress
echo "Done!"