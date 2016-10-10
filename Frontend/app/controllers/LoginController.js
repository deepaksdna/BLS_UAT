(function () {
    var loginCtrl = function ($scope, $http, $location, $state, blsService) {
        $scope.IsloggedIn = false;
        $scope.IsDashboard = false;
        $scope.token = '';
        $scope.userId = '';
        $scope.userDetails = {};
        // console.log($location.search().token);
        $scope.showme = true;
        $scope.userData = [];
        $scope.login = function () {
            var user = { username: $scope.username, password: $scope.password };
            blsService.getLogin(user).success(function (result) {
                console.log(result);
                if (result.response_code == 1) {
                    $scope.IsloggedIn = true;
                    $scope.IsDashboard = true;
                    console.log($scope.IsDashboard);
                    blsService.Token = result.token;
                    blsService.UserId = result.getUsers.id;
                }
                else {
                }
            });
        };
        $scope.signOut = function () {
            blsService.signOut().success(function (result) {
                $scope.IsloggedIn = false;
                $scope.IsDashboard = false;

            });
        };
    }
    angular.module('blsApp')
     .controller('loginCtrl', ['$scope', '$http', '$location', '$state', 'blsService', loginCtrl]);
      // .controller('loginCtrl', ['$scope', '$http', '$location', '$state', 'blsService', 'localStorageService', loginCtrl]);
}());

