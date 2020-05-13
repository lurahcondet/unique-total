/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

define(
    [
        'Xtendable_UniqueTotal/js/view/cart/summary/fee'
    ],
    function (Component) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Xtendable_UniqueTotal/cart/totals/fee'
            },
            /**
             * @override
             *
             * @returns {boolean}
             */
            isDisplayed: function () {
                return this.getPureValue() != 0;
            }
        });
    }
);
