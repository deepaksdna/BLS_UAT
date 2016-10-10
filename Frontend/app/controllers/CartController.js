(function () {
    var cartCtrl = function ($scope, $http, $stateParams, $state, blsService) {
      $scope.products = [];
		getCartDetails();
		

  function getConfigValues() {
            blsService.getConfigValues().success(function (data) {
                $scope.ConfigValues = data;
            });
        }
  
  $scope.Increase=function(qty, id)
  {
   var quantity=parseInt(qty)+1; 
   getProduct(id,quantity);
  
  }
  

  $scope.Decrease=function(qty, id)
  {
   if(qty==1){
    return false;
   }else{
   var quantity=parseInt(qty)-1;
   getProduct(id,quantity);
   }
  
  }
  
  function getProduct(id,quantity)
  {

           angular.forEach($scope.checkout.cart_products, function(value, key ) {
      if(value.prod_id==id)
      { 
         value.prod_quantity=quantity; 
		 
		addToCart(id,quantity);
		 
		 
      }  
   });
 
  }
  
  function addToCart(id,quantity){
	  
	   blsService.addToCart(id,quantity, blsService.Token, blsService.UserId).success(function (data) {
		    $scope.products = [];
		getCartDetails();
		   
            });
	  
  }
  
  $scope.removeFromCart=function(id)
  {
   removeFromCart(id);
  }
  
  function removeFromCart(id){
	  
	   blsService.removeFromCart(id, blsService.Token, blsService.UserId).success(function (data) {
		    $scope.products = [];
		getCartDetails();
		   
            });
	  
  }
  
  
  function getCartDetails() {
   getConfigValues();
   blsService.getCartDetails(blsService.Token, blsService.UserId).success(function (data) {
				$scope.checkout = data;
				$scope.cart_products = $scope.checkout.cart_products;
				$scope.grandPrices = $scope.checkout.user;
    
   }).error(function (err) {
                console.log(err);
            });

        }

    }
    angular.module('blsApp')
       .controller('cartCtrl', ['$scope', '$http', '$stateParams', '$state', 'blsService', cartCtrl]);
}());