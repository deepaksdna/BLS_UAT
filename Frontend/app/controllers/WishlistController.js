(function () {
    var wishListController = function ($scope, $http, $stateParams, $location, $state, $timeout, growl, blsService) {
        var productId = $stateParams.ProductId;
        $scope.WishListData = [];
        $scope.prod_img = '';
        if (blsService.UserId != "")
        {
            blsService.getUserWishlist(blsService.Token, blsService.UserId).success(function (result) {
                $scope.WishListData = result.products;
                $scope.prod_img = result.PRODUCTS_IMG_PATH;
                console.log($scope.WishListData);
            }).error(function (err) {
                console.log(err);
            });
        }
        //add to wishlist
        $scope.addWishList = function () {
           blsService.addWishList(blsService.Token,blsService.UserId, productId).success(function (result) {
                console.log(result);
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
      .controller('wishListController', ['$scope', '$http', '$stateParams', '$location', '$state', '$timeout', 'growl', 'blsService', wishListController]);
}());