(function () {
    var contactController = function ($scope, $http, $location, $state, $timeout, growl, blsService) {
        $scope.contact = function () {
            var contact = {
                name: $scope.main.name, email: $scope.main.email, block_no: $scope.main.blockNo, unit_no: $scope.main.unitNo, street: $scope.main.street, country: $scope.main.country, postal_code: $scope.main.postal_code,
                telephone_no: $scope.main.telephone_no, fax_no: $scope.main.fax_no, website: $scope.main.website, yourmessage: $scope.main.yourmessage
            };
            blsService.contactUser(contact).success(function (result) {
                if(result.response_code==1)
                {
                    growl.success(result.msg, { title: 'Success!' });
                    $timeout(function () {
                        $state.go('contact', {}, { reload: true });
                    }, 1000);
                }
                else
                {
                    growl.error(result.msg, { title: 'Error!' });
                }
            });
        }
    };
    angular.module('blsApp')
      .controller('contactController', ['$scope', '$http', '$location', '$state', '$timeout', 'growl', 'blsService', contactController]);
}());