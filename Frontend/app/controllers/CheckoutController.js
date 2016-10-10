(function () {
    var checkoutCtrl = function ($scope, $http, $stateParams, $state, blsService) {
      $scope.checkout = [];
	/*   $scope.totalprice=0.00; */
	  getDetailsToCheckout();
	//  console.log('detailPanel');
	  $scope.detailPanel=true;
	  $scope.PaymentPanel=false;
	  $scope.ConfirmPanel=false;
	  $scope.nextDetailStep=function()
	  {
		   // $scope.IsShowClass=true;
			$scope.detailPanel=false;
			$scope.PaymentPanel=true;
	  }
	   $scope.previousStep=function()
	  {
			$scope.detailPanel=true;
			$scope.PaymentPanel=false;
			 $scope.ConfirmPanel=false;
	  }
	   $scope.nextPaymentStep=function()
	  {
			$scope.detailPanel=false;
			$scope.PaymentPanel=false;
			 $scope.ConfirmPanel=true;
	  }
	   $scope.backButton=function()
	  {
			$scope.detailPanel=false;
			$scope.PaymentPanel=true;
			 $scope.ConfirmPanel=false;
	  }
	  
	  
$scope.confirmButton=  function()
  {
	  proceedToCheckout();
  }
 
function proceedToCheckout(){
	   blsService.proceedToCheckout(blsService.Token, blsService.UserId).success(function (data) {  
	   console.log(data);
            });
  } 
  
	  
		function getDetailsToCheckout() {
			blsService.getDetailsToCheckout(blsService.Token, blsService.UserId).success(function (data) {
				$scope.checkout = data;
				$scope.billings = $scope.checkout.user.user_billing;
				$scope.values = $scope.checkout.user.user_addresses.length;
				$scope.delivery_address=$scope.checkout.user.user_addresses[$scope.values-1];
				$scope.cart_products = $scope.checkout.cart_products;
				$scope.grandPrices = $scope.checkout.user;
			}).error(function (err) {
                console.log(err);
            });

        }

    }
    angular.module('blsApp')
       .controller('checkoutCtrl', ['$scope', '$http', '$stateParams', '$state', 'blsService', checkoutCtrl]);
}());

