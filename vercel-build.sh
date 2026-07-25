#!/bin/bash
echo "Installing PHP..."
curl -s https://php.net/distributions/php-8.2.0.tar.gz | tar xz
export PATH=$PWD/php-8.2.0/bin:$PATH
php -v