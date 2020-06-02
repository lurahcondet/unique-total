/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */


 define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/model/quote',
        'Xtendable_UniqueTotal/js/action/checkout/cart/totals'
    ],
    function($, ko ,quote, totals) {
        'use strict';
        var isLoading = ko.observable(false);

        return function (paymentMethod) {
            if (paymentMethod) {
                paymentMethod.__disableTmpl = {
                    title: true
                };
            }
            quote.paymentMethod(paymentMethod);
            totals(isLoading, paymentMethod['method']);
        }
    }
);