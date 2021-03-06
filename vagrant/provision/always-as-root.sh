#!/usr/bin/env bash
function info {
  echo " "
  echo "--> $1"
  echo " "
}
cp /vagrant/vagrant/php/php.ini /etc/php/7.0/apache2/
dos2unix /etc/php/7.0/apache2/php.ini
service php7.0-fpm restart
info "Copying Apache2 configuration"
cp /vagrant/vagrant/apache2/000-default.conf /etc/apache2/sites-available/
cp /vagrant/vagrant/apache2/envvars /etc/apache2/
dos2unix /etc/apache2/envvars
dos2unix /etc/apache2/sites-available/000-default.conf
service apache2 restart
service mysql restart
chmod -R 0755 /var/www/html
chown -R vagrant:vagrant /var/www/html
