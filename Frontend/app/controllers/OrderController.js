(function () {
    var orderController = function ($scope, $http, $location, $state, blsService) {
        blsService.getOrderHistory(blsService.Token, blsService.UserId).success(function (result) {
            $scope.orderData = result.orders;
            console.log(result);
        }).error(function (err) {
            console.log(err);
        });
    };
    angular.module('blsApp')
      .controller('orderController', ['$scope', '$http', '$location', '$state', 'blsService', orderController]);
}());