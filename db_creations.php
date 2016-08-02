<?php
/**
 * Created by PhpStorm.
 * User: Neo-pc
 * Date: 22.07.2016
 * Time: 21:33
 */
require_once('config.php');

$connect = mysqli_connect(CONFIG_HOST_NAME, CONFIG_DB_USER_NAME, CONFIG_DB_USER_PASSWORD);
if (!$connect) {
    die('Ошибка подключения (' . mysqli_connect_errno() . ') '
        . mysqli_connect_error());
}

mysqli_query($connect,"CREATE DATABASE IF NOT EXISTS `bn_main` CHARACTER SET utf8 COLLATE utf8_general_ci");
mysqli_close($connect);

$connect = mysqli_connect(CONFIG_HOST_NAME, CONFIG_DB_USER_NAME, CONFIG_DB_USER_PASSWORD, CONFIG_MAIN_DB_NAME);
if (!$connect) {
    die('Ошибка подключения (' . mysqli_connect_errno() . ') '
        . mysqli_connect_error());
}

//Создаю Таблицу пользователей
mysqli_query($connect,"CREATE TABLE IF NOT EXISTS `users` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `reg_date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
");

//Создаю Таблицу заметок
mysqli_query($connect,"CREATE TABLE IF NOT EXISTS `module_notes` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(6) NOT NULL,
  `content` text NOT NULL,
  `name` varchar(64) NOT NULL,
  `folder` int(2) unsigned NOT NULL,
  `folder_name` varchar(32) NOT NULL,
  `creation_datetime` int(10) unsigned NOT NULL,
  `modification_datetime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

//Создаю Таблицу папок
mysqli_query($connect,"CREATE TABLE IF NOT EXISTS `module_notes_folders` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(6) NOT NULL,
  `name` varchar(32) NOT NULL,
  `creation_datetime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");