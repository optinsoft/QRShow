<?php
/*
    QRSpaceForm class

    @created 15.11.2021
    @package optinsoft\QRShow
    @author Vitaly Yakovlev <vitaly@optinsoft.net>
    @copyright 2021 Vitaly Yakovlev
    @license BSD 2-Clause
*/
namespace optinsoft\QRShow;

class QRSpaceForm {
    public static function render($qrshow_url) {
?>
        <h4>Please, enter space (16-40 hex digits)</h4>
        <form id="spaceForm" name="spaceForm" method="GET" action="<?= htmlspecialchars($qrshow_url) ?>" onsubmit="return validateForm()">
            <table>
                <tr>
                    <td><input id="space" name="space" type="text" /></td>
                </tr><tr>
                    <td><input type="submit" value="Submit" /></td>
                </tr>
            </table>
        </form>
        <div id="error"></div>
        <script>
            function validateForm() {
                let space = document.forms["spaceForm"]["space"].value;
                if (!space.match(/^[0-9a-fA-F]{16,40}$/)) {
                    $('#error').html('Wrong space!');
                    return false;
                }
            }
        </script>
<?php
    }
}

?>

