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
        'mage/storage',
        'mage/url',
        'Magento_Checkout/js/action/get-totals',
    ],
    function(
        $,
        ko,
        storage,
        urlBuilder,
        getTotalsAction
    ) {
        'use strict';

        return function (isLoading, payment) {
            var serviceUrl = urlBuilder.build('paymentfee/checkout/totals');
            $("button.checkout").attr("disabled", true);
            return storage.post(
                serviceUrl,
                JSON.stringify({payment: payment})
            ).done(
                function(response) {
                    if (response) {
                        var deferred = $.Deferred();
                        isLoading(false);
                        $("button.checkout").attr("disabled", false);
                        getTotalsAction([], deferred);
                    }
                }
            ).fail(
                function (response) {
                    isLoading(false);
                    $("button.checkout").attr("disabled", false);
                }
            );
        }
    }
);