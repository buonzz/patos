#! /bin/bash

composer install

if [ -f 'dist/*.phar' ];
then
   rm dist/*.phar
fi

./box.phar build
echo 'build complete!'