(function () {
    var aboutUsController = function ($scope, $http, $location, $state, blsService) {
        $scope.showme = true;
    };
    angular.module('blsApp')
      .controller('aboutUsController', ['$scope', '$http', '$location', '$state', 'blsService', aboutUsController]);
}());