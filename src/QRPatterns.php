<?php
/*
    QRPatterns class

    @created 16.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause
*/
namespace optinsoft\QRShow;

class QRPatterns {
    const ID = '/^[0-9a-zA-Z]{1,32}$/';
    const SPACE = '/^[0-9a-fA-F]{16,40}$/';
    const DATA = '/^.{1,4296}$/'; 
    const TITLE = '/^[a-zA-Z0-9#$_.-]{1,128}$/';
    const TTL = '/^[0-9]{1,10}$/';
}
?>