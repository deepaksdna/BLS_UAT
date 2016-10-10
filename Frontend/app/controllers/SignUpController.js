(function () {
    var signUpController = function ($scope, $http, $location, $state, $timeout, growl, blsService) {
        $scope.register = function () {
            var register = {
                email: $scope.main.email, password: $scope.main.password,  fname: $scope.main.firstName,
                lname: $scope.main.lastName, blockno: $scope.main.blockNo, unitno: $scope.main.unitNo,
                street: $scope.main.street, country: $scope.main.country, postalcode: $scope.main.postalCode,
                telephoneno: $scope.main.telephoneno, fax_no: $scope.main.fax_no, compname: $scope.main.companyName,
                position: $scope.main.position
            };
            blsService.registerUser(register).success(function (result) {
                if (result.response_code == 1) {
                    growl.success(result.msg, { title: 'Success!' });
                    $timeout(function () {
                        $state.go('signup', {}, { reload: true });
                    }, 1000);
                }
                else {
                    growl.error(result.msg, { title: 'Error!' });
                }
            });
        };
        $scope.forgotPassword = function () {
            var forgotpassword = { email: $scope.main.email };
            blsService.forgotPassword(forgotpassword).success(function (result) {
                if (result.response_code == 1) {
                    growl.success(result.msg, { title: 'Success!' });
                  //  $scope.main.email = '';
                    $timeout(function () {
                        $state.go('forgotpassword', {}, { reload: true });
                    }, 1000);
                }
                else {
                    growl.error(result.msg, { title: 'Error!' });
                }
            });
        };
        $scope.setPassword = function () {
            // console.log($location.search().token);
            var forgotpassword = { token: $location.search().token, password: $scope.main.password, confirm_password: $scope.main.confirm_password };
            blsService.setPassword(forgotpassword).success(function (result) {
                if (result.response_code == 1) {
                    growl.success(result.msg, { title: 'Success!' });
                    $timeout(function () {
                        $state.go('setpassword', {}, { reload: true });
                    }, 1000);
                }
                else {
                    growl.error(result.msg, { title: 'Error!' });
                }
            });
        };
    };
    angular.module('blsApp')
      .controller('signUpController', ['$scope', '$http', '$location', '$state', '$timeout', 'growl', 'blsService', signUpController]);
}());