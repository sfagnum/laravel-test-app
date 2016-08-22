/**
 * Created by sfagnum on 22.08.16.
 */
var moduleDependencies = [
    'ui.bootstrap',
    'ngAnimate'
];

var App = angular.module('testApp', moduleDependencies);

App.config(['$httpProvider', '$interpolateProvider', function($httpProvider, $interpolateProvider){
    $httpProvider.defaults.headers.common["X-Requested-With"] = 'XMLHttpRequest';


    $interpolateProvider.startSymbol('{%');
    $interpolateProvider.endSymbol('%}');
}]);