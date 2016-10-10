(function () {
    var AccountDetailsController = function ($scope, $http, $location, $state, $timeout, growl, blsService) {
        //get account detail
        blsService.getAccountDetail(blsService.Token, blsService.UserId).success(function (result) {
            $scope.userData = result.userDetails;
        }).error(function (err) {
            console.log(err);
        });
        $scope.updateAccountDetail = function () {
            var accountDetail = { firstname: $scope.userData[0].firstname, lastname: $scope.userData[0].lastname, password: $scope.userData[0].password, confirm_password:$scope.userData[0].confirm_password };
            blsService.updateAccountDetail(accountDetail, blsService.Token, blsService.UserId).success(function (result) {
               // console.log(result);
                if (result.response_code == 1) {
                    growl.success(result.msg, { title: 'Success!' });
                }
                else {
                    growl.error(result.msg, { title: 'Error!' });
                }
            });
        };
        //get address book detail
        blsService.getAdressBookDetail(blsService.Token, blsService.UserId).success(function (result) {
            $scope.userAddressData = result.userDetails;
          //  console.log($scope.userAddressData);
        }).error(function (err) {
            console.log(err);
        });
        $scope.updateAddressBookDetail = function () {
            var addressBook = {
                USER_BILLING_SENDERNAME: $scope.userAddressData[0].USER_BILLING_SENDERNAME, USER_BILLING_CONTACT_NO: $scope.userAddressData[0].USER_BILLING_CONTACT_NO, USER_BILLING_FAXNO: $scope.userAddressData[0].USER_BILLING_FAXNO,
                USER_BILLING_EMAIL: $scope.userAddressData[0].USER_BILLING_EMAIL, USER_BILLING_STREET_NAME: $scope.userAddressData[0].USER_BILLING_STREET_NAME,USER_BILLING_UNIT_NO: $scope.userAddressData[0].USER_BILLING_UNIT_NO,
                USER_BILLING_POSTAL_CODE: $scope.userAddressData[0].USER_BILLING_POSTAL_CODE, USER_BILLING_COUNTRY: 'SINGAPORE',
              
                USER_SHIPPING_RECEIVER_NAME: $scope.userAddressData[0].USER_SHIPPING_RECEIVER_NAME, USER_SHIPPING_CONTACT_NO: $scope.userAddressData[0].USER_SHIPPING_CONTACT_NO,
                USER_SHIPPING_FAX_NO: $scope.userAddressData[0].USER_SHIPPING_FAX_NO, USER_SHIPPING_EMAIL: $scope.userAddressData[0].USER_SHIPPING_EMAIL,
                USER_SHIPPING_ADDRESS: $scope.userAddressData[0].USER_SHIPPING_ADDRESS, USER_SHIPPING_UNITNO: $scope.userAddressData[0].USER_SHIPPING_UNITNO,
                USER_SHIPPING_POSTAL_CODE: $scope.userAddressData[0].USER_SHIPPING_POSTAL_CODE, USER_SHIPPING_COUNTRY: $scope.userAddressData[0].USER_SHIPPING_COUNTRY
            };
            blsService.updateAddressBookDetail(addressBook, blsService.Token, blsService.UserId).success(function (result) {
                if (result.response_code == 1) {
                    growl.success(result.msg, { title: 'Success!' });
                }
                else {
                    growl.error(result.msg, { title: 'Error!' });
                }
            });
        };
    };
    angular.module('blsApp')
      .controller('AccountDetailsController', ['$scope', '$http', '$location', '$state', '$timeout', 'growl', 'blsService', AccountDetailsController]);
}());