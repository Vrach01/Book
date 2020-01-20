<?php
return array(
    //Главная страница:
    '/' =>'main/index',
    '/page-([0-9]+)' => 'main/index/$1',

    //Администраторы:
    '/admin/?' => 'admin/index',
    '/admin/ban/([0-9]+)' => 'admin/ban/$1',
    '/admin/delete/([0-9]+)' => 'admin/delete/$1',
    '/admin/changeStatus/([0-9]+)' => 'admin/status/$1',
    '/admin/writes/([0-9]+)' => 'admin/writes/$1',
    '/admin/writes/([0-9]+)/page-([0-9]+)' => 'admin/writes/$1/$2',

    // Пользователи:
    '/user/?' => 'user/index',
    '/user/([0-9]+)' => 'user/index/$1',
    '/user/login/?' => 'user/login',
    '/user/register/?' => 'user/register',
    '/user/logout/?' => 'user/logout',
    '/usersList/?' => 'user/list',
    '/user/update/?' => 'user/update',


    // Записи:
    '/writes/([0-9]+)' => 'write/index/$1',
    '/writes/([0-9]+)/page-([0-9]+)' => 'write/index/$1/$2',
    '/writes/([0-9]+)/edit-([0-9]+)' => 'write/edit/$1/$2',
    //Оставшиеся случаи:
    '/(.+)' => 'main/index',
);