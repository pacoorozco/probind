#!/bin/bash

php artisan key:generate 
php artisan migrate --seed
