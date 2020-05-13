/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Magento_Checkout/js/model/totals'
    ],
    function (Component, quote, priceUtils, totals) {
        "use strict";
        return Component.extend({
            defaults: {
                isFullTaxSummaryDisplayed: window.checkoutConfig.isFullTaxSummaryDisplayed || false,
                template: 'Xtendable_UniqueTotal/checkout/summary/unique-amount'
            },
            totals: quote.getTotals(),
            isTaxDisplayedInGrandTotal: window.checkoutConfig.includeTaxInGrandTotal || false,

            isDisplayed: function() {
                return this.isFullMode() && this.getPureValue() != 0 &&  this.getPureValue() != 0.00 &&  this.getPureValue() != 0.000 &&  this.getPureValue() != 0.0000;
            },

            getPureValue: function() {
                var price = 0;
                if (this.totals() && totals.getSegment('unique_amount').value) {
                    price = parseFloat(totals.getSegment('unique_amount').value);
                }
                return price;
            },

            getTitle: function() {
                if (this.totals() && totals.getSegment('unique_amount').title) {
                    return totals.getSegment('unique_amount').title;
                }

                return 'Unique Amount';
            },

            getValue: function() {
                var price = 0;
                if (this.totals()) {
                    price = totals.getSegment('unique_amount').value;
                }
                return this.getFormattedPrice(price);
            },

            getBaseValue: function() {
                var price = 0;
                if (this.totals()) {
                    price = this.totals().base_payment_charge;
                }
                return priceUtils.formatPrice(price, quote.getBasePriceFormat());
            }
        });
    }
);