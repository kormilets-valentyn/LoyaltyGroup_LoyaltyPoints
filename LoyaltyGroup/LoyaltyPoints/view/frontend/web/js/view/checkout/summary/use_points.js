define (
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Customer/js/model/customer',
        'jquery',
        'Magento_Checkout/js/model/error-processor',
        'mage/url',
        'Magento_Checkout/js/action/get-totals',
        'ko'
    ],
    function (
        Component,
        customer,
        $,
        errorProcessor,
        url,
        getTotals,
        ko
        ){
        'use strict';
        return Component.extend({
            defaults: {
                template: 'LoyaltyGroup_LoyaltyPoints/checkout/summary/use_points'
            },
            someFunc: $.cookie('checkoutPosition'),
            usePoints: function (){
                return $.ajax({
                    url: url.build('customer/customer/customer'),
                    data: {'test': this.isCheck()},
                    type: "POST",
                    dataType: 'json'
                }).done(function () {
                    getTotals([], false);
                }).fail(function (response) {
                    errorProcessor.process(response);
                })
            },
            isDisplayed: function(){
                return (customer.isLoggedIn());
                },
            isCheck: function () {
                var points = $.cookie('usePointsOrNot');
                if (points == 0 || points == null){
                    return 1;
                } else return 0;
            }
        })
    }
);