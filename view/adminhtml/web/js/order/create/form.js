/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

define([
    'jquery',
    'Magento_Sales/order/create/scripts'
], function (jQuery) {
    'use strict';

    var $el = jQuery('#edit_form'),
        config,
        baseUrl,
        order,
        payment;

    if (!$el.length || !$el.data('order-config')) {
        return;
    }

    config = $el.data('order-config');
    baseUrl = $el.data('load-base-url');

    var tempOrder = new AdminOrder(config);

    tempOrder.switchPaymentMethod = function(method){
        jQuery('#edit_form').trigger('changePaymentMethod', [method]);
        this.setPaymentMethod(method);
        var data = {};
        data['order[payment_method]'] = method;
        this.loadArea(['card_validation','totals'], true, data);
    };

    tempOrder.changePaymentData = function(event){
        var elem = Event.element(event);
        if(elem && elem.method){
            var data = this.getPaymentData(elem.method);
            if (data) {
                this.loadArea(['card_validation','totals'], true, data);
            } else {
                return;
            }
        }
    };    

    order = tempOrder;
    order.setLoadBaseUrl(baseUrl);

    payment = {
        switchMethod: order.switchPaymentMethod.bind(order)
    };

    window.order = order;
    window.payment = payment;
});
