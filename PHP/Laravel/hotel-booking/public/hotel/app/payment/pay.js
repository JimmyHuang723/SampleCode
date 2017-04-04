'use strict';

angular.module('myApp.pay', ['ngRoute','ui.materialize'])

    .config(['$routeProvider', function($routeProvider) {
        $routeProvider.when('/pay/:reserv_id', {
            templateUrl: 'payment/pay.html',
            controller: 'PayController'
        });
    }])

    .controller('PayController', function($scope,$http,reservationData,$location,$routeParams) {

        $scope.res_id = $routeParams.reserv_id;
        $scope.paid = false;

        $scope.handleStripe = function(status, response){
            alert("handle Stripe");
            if(response.error) {
                alert("Error from Stripe.com");
                $scope.paid= false;
                $scope.message = "Error from Stripe.com"
            } else {
                alert("OK from Stripe.com");
                console.log(response.id);
                var $payInfo = {
                    'token' : response.id,
                    'customer_id' : $scope.reservation_info.customer_id,
                    'total':$scope.reservation_info.total_price
                };

                $http.post('/hotel-booking/public/api/payreservation', $payInfo).success(function(data){
                    if(data.status=="OK"){
                        alert("paid ok");
                        $scope.paid= true;
                        $scope.message = data.message;
                    }else{
                        alert("paid false");
                        $scope.paid= false;
                        $scope.message = data.message;
                    }
                });

            }
        };

        $scope.init = function(){

            $scope.loaded = false;

            $http.get('/hotel-booking/public/api/reservation/'+$scope.res_id).success(function(data){
                $scope.reservation_info = data;
                console.log(data);
                $scope.loaded=true;
            });
        };

        $scope.init();

    }
);
