<?php
/************************************************************************/
/* PHP Firewall: Universal Firewall for WebSite                         */
/* ============================================                         */
/* Write by Cyril Levert                                                */
/* Copyright (c) 2009                                                   */
/* http://www.php-firewall.info                                         */
/* dev@php-maximus.org                                                  */
/* Others projects:                                                     */
/* CMS PHP Maximus ( with mysql database ) www.php-maximus.org          */
/* Blog PHP Minimus ( with mysqli database ) www.php-minimus.org        */
/* Mini CMS PHP Nanomus ( without database ) www.php-nanomus.org        */
/* Stop Spam Referer ( without database ) www.stop-spam-referer.info    */
/* PHP Firewall ( without database ) www.php-firewall.info              */
/* Personnal blog www.cyril-levert.info                                 */
/* Release version 1.0.1                                                */
/* Release date : 01-24-2010                                            */
/*                                                                      */
/* This program is free software.                                       */
/************************************************************************/

/** configuration define */

define('PHP_FIREWALL_REQUEST_URI', str_replace( '/', DS, strip_tags( $_SERVER['REQUEST_URI'] ) ) );
define('PHP_FIREWALL_ACTIVATION', true );

define('PHP_FIREWALL_ADMIN_MAIL', 'parweb@gmail.com' );
define('PHP_FIREWALL_PUSH_MAIL', true );
define('PHP_FIREWALL_LOG_FILE', 'logs' );
define('PHP_FIREWALL_PROTECTION_RANGE_IP_DENY', true );
define('PHP_FIREWALL_PROTECTION_RANGE_IP_SPAM', true );
define('PHP_FIREWALL_PROTECTION_URL', true );
define('PHP_FIREWALL_PROTECTION_REQUEST_SERVER', true );
define('PHP_FIREWALL_PROTECTION_SANTY', true );
define('PHP_FIREWALL_PROTECTION_BOTS', true );
define('PHP_FIREWALL_PROTECTION_REQUEST_METHOD', true );
define('PHP_FIREWALL_PROTECTION_DOS', true );
define('PHP_FIREWALL_PROTECTION_UNION_SQL', true );
define('PHP_FIREWALL_PROTECTION_CLICK_ATTACK', true );
define('PHP_FIREWALL_PROTECTION_XSS_ATTACK', true );
define('PHP_FIREWALL_PROTECTION_COOKIES', true );
define('PHP_FIREWALL_PROTECTION_POST', true );
define('PHP_FIREWALL_PROTECTION_GET', true );
define('PHP_FIREWALL_PROTECTION_SERVER_OVH', true );
define('PHP_FIREWALL_PROTECTION_SERVER_KIMSUFI', true );
define('PHP_FIREWALL_PROTECTION_SERVER_DEDIBOX', true );
define('PHP_FIREWALL_PROTECTION_SERVER_DIGICUBE', true );
define('PHP_FIREWALL_PROTECTION_SERVER_OVH_BY_IP', true );
define('PHP_FIREWALL_PROTECTION_SERVER_KIMSUFI_BY_IP', true );
define('PHP_FIREWALL_PROTECTION_SERVER_DEDIBOX_BY_IP', true );
define('PHP_FIREWALL_PROTECTION_SERVER_DIGICUBE_BY_IP', true );