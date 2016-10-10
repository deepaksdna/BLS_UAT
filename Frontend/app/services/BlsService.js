angular.module('blsApp').factory('blsService', function ($http, $q) {
    //   var baseUrl = 'http://www.nanoappstore.com:8282/admin/API/';
	    var baseUrl = 'http://112.196.17.77:81/bls/admin/API/';
    //  var baseUrl = 'http://192.168.0.82/blsmukesh/API/';
    // var baseUrl = 'http://localhost/bls/API/';


    var Token;
    var UserId;
    var getCategories = function () {
        return $http.get(baseUrl + 'Categories/getAllSubCategories?callback=JSON_CALLBACK')
    }
    var getPromoOption = function () {
        return $http.get(baseUrl + 'Promotions/listallPromoOption?callback=JSON_CALLBACK')
    }
    var getPromoOptionValues = function (promoOptionId) {
        if (promoOptionId == 0) {
            return $http.get(baseUrl + 'Promotions/getPromoPageValuePlus?callback=JSON_CALLBACK')
        }
        else if (promoOptionId == 1) {
            return $http.get(baseUrl + 'Promotions/getPromoPageSoloPlus?callback=JSON_CALLBACK')
        }
        else {
            return $http.get(baseUrl + 'Promotions/getPromoPagePwp?callback=JSON_CALLBACK')
        }
    }
    var getImages = function () {
        return $http.get(baseUrl + 'sliders/sliderimages.json?callback=JSON_CALLBACK')
    }
    var searchProduct = function (keyword) {
        return $http.get(baseUrl + 'products/getSearchProducts/' + keyword + '.json?callback=JSON_CALLBACK')
    }
    var getLogin = function (user) {
        var url = baseUrl + 'logins/login';
        return $http({
            url: url,
            method: "POST",
            params: user,
            headers:
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
        });
    }
    var signOut = function () {
        return $http.get(baseUrl + 'users/getLogout.json?callback=JSON_CALLBACK')
        $scope.loggedIn = false;
    }
    var registerUser = function (register) {
        var url = baseUrl + 'users/register';
        return $http({
            url: url,
            method: "POST",
            params: register,
            headers:
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
        });
    }
    var contactUser = function (contact) {
        var url = baseUrl + 'users/contactUs.json';
        return $http({
            url: url,
            method: "POST",
            params: contact,
            headers:
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
        });
    }
    var forgotPassword = function (forgotpassword) {
        var url = baseUrl + 'logins/forgot.json';
        return $http({
            url: url,
            method: "POST",
            params: forgotpassword,
            headers:
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
        });
    }
    var setPassword = function (setPassword) {
        var url = baseUrl + 'users/setPasswordForgot.json';
        return $http({
            url: url,
            method: "POST",
            params: setPassword,
            headers:
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
        });
    }
    var updateAccountDetail = function (updateAccountDetail, token, userId) {
        var url = baseUrl + 'users/updateAccountDetails/' + token + '/' + userId + '.json';
        return $http({
            url: url,
            method: "POST",
            params: updateAccountDetail,
            headers:
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
        });
    }
    var updateAddressBookDetail = function (updateAddressBookDetail, token, userId) {
       // console.log(updateAddressBookDetail);
        var url = baseUrl + 'Users/saveUserAddressBook/' + token + '/' + userId + '.json';
        return $http({
            url: url,
            method: "POST",
            params: updateAddressBookDetail,
            headers:
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
        });
    }
    var addToCartItem = function (id, quantity, token, userId) {
        var url = baseUrl + 'Carts/addToCart/' + id + '/' + quantity + '/' + token + '/' + userId;
        console.log(url);
        return $http({
            url: url,
            method: "POST",
            params: addToCartItem,
            headers:
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
        });
    }
	
	//By Mukesh 
	var addToCart = function (id, quantity, token, userId) {
		var url = baseUrl + 'Carts/addToCart/' + id + '/' + quantity + '/' + token + '/' + userId;
        return $http({
            url: url,
            method: "POST",
            params: addToCart,
            headers:
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
        });
    }
	
	var removeFromCart = function (id, token, userId) {
        var url = baseUrl + 'Carts/removeProductCart/' + id + '/' + token + '/' + userId;
        return $http({
            url: url,
            method: "POST",
            params: removeFromCart,
            headers:
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
        });
    }
	
	var proceedToCheckout = function (token, userId) {
        var url = baseUrl + 'ProcessPayment/dopayment/' + token + '/' + userId + '.json?callback=JSON_CALLBACK';
        return $http({
            url: url,
            method: "POST",
            params: proceedToCheckout,
            headers:
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
        });
    }
	
	
	
	//By Mukesh ends
		
    var getAccountDetail = function () {
        return $http.get(baseUrl + 'users/getUserAccountDetails.json?callback=JSON_CALLBACK')

    }
    var getAccountDetail = function (token, userId) {
        return $http.get(baseUrl + 'users/getUserAccountDetails/' + token + '/' + userId + '.json?callback=JSON_CALLBACK')
    }
    var getAdressBookDetail = function (token, userId) {
        return $http.get(baseUrl + 'users/getUserAddressBook/' + token + '/' + userId + '.json?callback=JSON_CALLBACK')
    }
    var getOrderHistory = function (token, userId) {
        return $http.get(baseUrl + 'orders/getUserOrders/' + token + '/' + userId + '.json?callback=JSON_CALLBACK')
    }
    var getProducts = function (categoryId) {
        return $http.get(baseUrl + 'products/getProductByCat/' + categoryId + '.json?callback=JSON_CALLBACK')
    }
    var getProductDetail = function (productId) {
        return $http.get(baseUrl + 'Products/getProductDetails/' + productId + '.json?callback=JSON_CALLBACK')
    }
    var getColourProducts = function (productId, colourId) {
        return $http.get(baseUrl + 'Products/getColorImagesByProduct/' + productId + '/' + colourId + '.json?callback=JSON_CALLBACK')
    }
    var getProductBrand = function (brandId, categoyId) {
        //return $http.get(baseUrl + 'Products/getProductsByBrand/' + brandId + '.json?callback=JSON_CALLBACK')
        return $http.get(baseUrl + 'Products/getProductsByBrandWithCat/' + brandId + '/' + categoyId + '  .json?callback=JSON_CALLBACK')
    }
    var addWishList = function (token, userId, productId) {
        var url = baseUrl + 'users/addToWishlist/' + token + '/' + userId + '/' + productId + '.json';
       // console.log(url);
        return $http({
            url: url,
            method: "POST",
            headers:
                {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
        });
    }
    var getUserWishlist = function (token, userId) {
        return $http.get(baseUrl + 'users/getWishlist/' + token + '/' + userId + '.json?callback=JSON_CALLBACK')
    }

    //By Mukesh	Starts Here

    var getCartDetails = function (token, userId) {

        return $http.get(baseUrl + 'carts/detailsToCart/'+ token + '/' + userId+'.json?callback=JSON_CALLBACK')
    }

	
	var getDetailsToCheckout = function (token, userId) {
        return $http.get(baseUrl + 'checkout/detailsToCheckout/'+ token + '/' + userId)
    }
	
    var getConfigValues = function () {

        return $http.get(baseUrl + 'Config/allConfigDetails')
    }
    //By Mukesh Ends Here	

    return {
        getCategories: getCategories,
        getPromoOption: getPromoOption,
        getPromoOptionValues: getPromoOptionValues,
        getImages: getImages,
        getLogin: getLogin,
        searchProduct: searchProduct,
        signOut: signOut,
        registerUser: registerUser,
        contactUser: contactUser,
        forgotPassword: forgotPassword,
        setPassword: setPassword,
        updateAccountDetail: updateAccountDetail,
        updateAddressBookDetail:updateAddressBookDetail,
        getAccountDetail: getAccountDetail,
        getAdressBookDetail: getAdressBookDetail,
        getOrderHistory: getOrderHistory,
        getProducts: getProducts,
        getProductDetail: getProductDetail,
        getColourProducts: getColourProducts,
        getProductBrand: getProductBrand,
        addWishList: addWishList,
        getUserWishlist:getUserWishlist,
		 getCartDetails: getCartDetails,
		getConfigValues:getConfigValues,
		getDetailsToCheckout:getDetailsToCheckout,
		addToCart: addToCart,
		addToCartItem:addToCartItem,
		removeFromCart:removeFromCart,
		proceedToCheckout:proceedToCheckout
       
    };
});



