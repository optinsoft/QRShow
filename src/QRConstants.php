<?php
/*
    QRConstants class

    @created 16.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause 
*/
namespace optinsoft\QRShow;

use chillerlan\QRCode\{QRCode};

class QRConstants {
    const QRCODE_OPTIONS = [
        'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
        'eccLevel'     => QRCode::ECC_L,
        'imageBase64'  => false,
    ];
}
?>