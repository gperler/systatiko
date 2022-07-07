# install nginx and configure


# install php 8.1
apt-get install -y software-properties-common
add-apt-repository ppa:ondrej/php
apt-get install -y php8.1 libapache2-mod-php8.1
apt-get install -y php8.1-common php8.1-cli php8.1-zip php8.1-curl php8.1-mbstring php8.1-xml php8.1-gd php8.1-bcmath php8.1-soap php8.1-xdebug

apt-get install -y php8.1-fpm
apt-get install -y php8.1-json
apt-get install -y php8.1-mysql

#grep -q "extension=json.so" /etc/php/8.1/cli/php.ini || echo 'extension=json.so' | sudo tee -a /etc/php/8.1/cli/php.ini;
