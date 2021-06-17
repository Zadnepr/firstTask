Инструкция
--------------------------

Скачивание и развертывание
--
    git clone https://github.com/Zadnepr/firstTask.git firstTask

Для развертывания необходимо наличие Docker
--

Перед развертыванием контейнеров нужно настроить окружение под локальные настройки. Сделать это можно в файле .env (в корне проекта)

Настройки по умолчанию (.env):

    WEBSERVER_CONTAINER_NAME=zadnepr-apache-php
    MYSQL_CONTAINER_NAME=zadnepr-mysql
    PHPMYADMIN_CONTAINER_NAME=zadnepr-phpmyadmin
    COMPOSER_CONTAINER_NAME=zadnepr-composer
    WEBSERVER_PORT=80
    MYSQL_PORT=3306
    PHPMYADMIN_PORT=8080
    DEFAULT_LANGUAGE=en-US

Настройка DEFAULT_LANGUAGE (язык приложения) может принимать 1 из 2-х значений (en-US или ru-RU)

    docker-compose up -d --build

Установка всех библиотек производится автоматически используя контейнер с composer
После развертывания в первый раз нужно будет подождать несколько минут чтобы composer скачал и подключил все зависимости

После окончания установки всех контейнеров задание будет доступно по адресу 

    http://127.0.0.1/orders

(Данные БД автоматически инсталированы)

phpmyadmin доступен по адресу:

    http://127.0.0.1:8080/