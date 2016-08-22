!(function(module){
    "use strict";

    function MainController ($scope, APICurrencyProvider, $timeout) {
        $scope.currencies = [];
        $scope.highlight = false;

        APICurrencyProvider.onChange(function(currencies, changed) {
            currencies.forEach(function(currency){
                if (!changed.length || changed.indexOf(currency.isoCode) !== -1) {
                    currency.changed = true;
                }
            });

            $scope.currencies = currencies;
            $scope.highlight = true;

            $timeout(function(){
                $scope.highlight = false;
            }, 1000);
        });
    }

    module.controller('MainController',['$scope', 'APICurrencyProvider', '$timeout', MainController]);
})(angular.module('testApp'));