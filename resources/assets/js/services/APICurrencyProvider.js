!(function (module) {
    "use strict";

    function APICurrencyProvider ($http, $interval, $q) {
        var delay = 5 *1000,
            timer,
            started = false,
            hash = '',
            callbacks = [];

        this.onChange = function(callback) {
            if (!started) {
                this.startPooling();
            }

            callbacks.push(callback);
        };

        this.startPooling = function() {

            var cancel = $q.defer();

            var loop = function(){
                cancel.reject('long time request');

                cancel = $q.defer();

                $http.get('/currencies', {
                    timeout: cancel.promise,
                    params: {hash: hash}
                }).then(function success(response){
                        if (response.status !== 200) {
                            console.log('Error', response);
                            return;
                        }

                        var newHash = response.data.hash;

                        if (newHash !== hash) {
                            callbacks.forEach(function (callback){
                                callback(response.data.currencies, response.data.changed);
                            });

                            hash = newHash;
                        }
                    }, function error(response) {
                        console.log('Error', response);
                    });
            };

            loop();

            timer = $interval(loop, delay);
        };

        this.setDelay = function(newDelay) {
            delay = +newDelay;
            $interval.cancel(timer);
            this.startPooling();
        }
    }

    module.service('APICurrencyProvider', ['$http', '$interval', '$q', APICurrencyProvider]);
})(App);