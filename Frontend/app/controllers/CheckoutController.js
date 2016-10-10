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
	   $scope.confirmButton=function()
	  {
			$scope.detailPanel=false;
			$scope.PaymentPanel=false;
			 $scope.ConfirmPanel=false;
	  }
	  
	   //console.log($scope.products);
		/* function getConfigValues() {
            blsService.getConfigValues().success(function (data) {
                $scope.ConfigValues = data;
            });
        }
		function getTotalValue()
		{
			$scope.totalprice=0.00;
			angular.forEach($scope.products,function(product){
				$scope.totalprice+=(product.PRODUCT_DETAILS.BEST_DISCOUNTED_AMOUNT_WITHOUT_GST * product.PRODUCT_QUANTITY);

			});
			
		$scope.GSTPrice = ($scope.totalprice*$scope.ConfigValues.gst)/100;
		$scope.totalpriceWithGST = $scope.GSTPrice+$scope.totalprice;
				
								if($scope.totalpriceWithGST<$scope.ConfigValues.delivery_charge){
									
									$scope.deliveryFeeDisplay =  'S$'+$scope.ConfigValues.delivery_charge;
									$scope.deliveryFee =  parseInt($scope.ConfigValues.delivery_charge);
										
								}else{
									$scope.deliveryFeeDisplay= 'Free';
									$scope.deliveryFee = parseInt(0);
								}
												
		$scope.GrandTotalpriceWithGST = ($scope.totalpriceWithGST + $scope.deliveryFee);  
			
		}
		
		$scope.Increase=function(qty, id)
		{
			var quantity=parseInt(qty)+1;	
			getProduct(id,quantity);
			getTotalValue();
		}
		

		$scope.Decrease=function(qty, id)
		{
			if(qty==1){
				return false;
			}else{
			var quantity=parseInt(qty)-1;
			getProduct(id,quantity);
			}
			getTotalValue();
		}
		
		function getProduct(id,quantity)
		{

           angular.forEach($scope.products, function(value, key ) {
			   if(value.PRODUCT_ID==id)
			   {
				     value.PRODUCT_QUANTITY=quantity; 
			   }  
			});
 
		} */
		function getDetailsToCheckout() {
			blsService.getDetailsToCheckout().success(function (data) {
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

