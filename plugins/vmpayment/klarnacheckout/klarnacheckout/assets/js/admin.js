/**
 * @package    VmPayment PayPal
 * @author Jeremy Magne
 * @copyright Copyright (C) 2010 Daycounts.com. All Rights Reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

jQuery().ready(function ($) {



    /************/
    /* Handlers */
    /************/
    handleLogo = function () {
        var payment_logos = $("input[name='params[payment_logos]']:checked").val();

        $('.show_payment_logo').parents('.control-group').hide();

        if (payment_logos == '1') {
            $('.show_payment_logo').parents('.control-group').show();
        }
    }
    /**********/
    /* Events */
    /**********/
    $("input[name='params[payment_logos]']").change(function () {
        handleLogo();
    });
    /*****************/
    /* Initial calls */
    /*****************/
    handleLogo();


});
