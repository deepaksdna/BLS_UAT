var $urlRouterProviderRef = null;
var $stateProviderRef = null;

var blsApp = angular.module('blsApp', ['ngAnimate', 'ngTouch', 'ui.router', 'ngMessages','angular-growl']);

angular.module('blsApp').animation('.slide-animation', function () {
    return {
        beforeAddClass: function (element, className, done) {
            var scope = element.scope();

            if (className == 'ng-hide') {
                var finishPoint = element.parent().width();
                if (scope.direction !== 'right') {
                    finishPoint = -finishPoint;
                }
                TweenMax.to(element, 0.5, { left: finishPoint, onComplete: done });
            }
            else {
                done();
            }
        },
        removeClass: function (element, className, done) {
            var scope = element.scope();

            if (className == 'ng-hide') {
                element.removeClass('ng-hide');

                var startPoint = element.parent().width();
                if (scope.direction === 'right') {
                    startPoint = -startPoint;
                }

                TweenMax.fromTo(element, 0.5, { left: startPoint }, { left: 0, onComplete: done });
            }
            else {
                done();
            }
        }
    };
});
blsApp.config(function ($stateProvider, $urlRouterProvider, $locationProvider) {
    $urlRouterProviderRef = $urlRouterProvider;
    $locationProvider.html5Mode(false);
    $stateProviderRef = $stateProvider;
      $urlRouterProvider.otherwise('/home');
    $stateProvider
        .state('home', {
            url: '/home',
            templateUrl: './app/templates/home.html',
            controller: 'homeController'
        })
        .state('signup', {
              url: '/signup',
              templateUrl: './app/templates/signup.html',
              controller: 'signUpController'
          })
        .state('forgotpassword', {
            url: '/forgotpassword',
            templateUrl: './app/templates/forgotpassword.html',
            controller: 'signUpController'
        })
         .state('accountdetail', {
             url: '/accountdetail',
             templateUrl: './app/templates/accountdetail.html',
             controller: 'AccountDetailsController'
         })
        .state('addressbook', {
            url: '/addressbook',
            templateUrl: './app/templates/addressbook.html',
            controller: 'AccountDetailsController'
        })
        .state('orderhistory', {
            url: '/orderhistory',
            templateUrl: './app/templates/orderhistory.html',
            controller: 'orderController'
        })
       .state('wishlist', {
           url: '/wishlist',
           templateUrl: './app/templates/wishlist.html',
           controller: 'wishListController'
       })
        .state('setpassword', {
            url: '/setpassword',
            templateUrl: './app/templates/setpassword.html',
            controller: 'signUpController'
        })
         .state('product', {
             url: '/product',
             params: {
                 obj: null
             },
             templateUrl: './app/templates/product.html',
             controller: 'productCtrl'
         })
         .state('products', {
          
         })
        .state('promotion', {
          
        })
        .state('productTemplate1', {
            url: '/productTemplate1?CategoryId&BrandId',
            templateUrl: './app/templates/productTemplate1.html',
            controller: 'productCtrl'
        })
         .state('productTemplate2', {
             url: '/productTemplate2?CategoryId&BrandId',
             templateUrl: './app/templates/productTemplate2.html',
             controller: 'productCtrl'
         })
         .state('productTemplate3', {
             url: '/productTemplate3?CategoryId&BrandId',
             templateUrl: './app/templates/productTemplate3.html',
             controller: 'productCtrl'
         })
        .state('FOCProductDetails', {
            url: '/FOCProductDetails?ProductId',
            templateUrl: './app/templates/productdetail.html',
            controller: 'productCtrl'
        })
        .state('promotions', {
            url: '/promotions?PromoOptionId&Name',
            templateUrl: './app/templates/promotions.html',
            controller: 'homeController'
        })
          .state('about', {
              url: '/about',
              templateUrl: './app/templates/about.html',
              controller: 'aboutUsController'
          })
        .state('termsandconditions', {
            url: '/termsandconditions',
            templateUrl: './app/templates/termsandconditions.html',
            controller: 'loginCtrl'
        })
         .state('privacypolicy', {
             url: '/privacypolicy',
             templateUrl: './app/templates/privacypolicy.html'
         })
         .state('contact', {
             url: '/contact',
             templateUrl: './app/templates/contact.html',
             controller: 'contactController'
         })
         .state('viewcart', {
             url: '/viewcart',
             templateUrl: './app/templates/viewcart.html'
         })
		  .state('checkout', {
             url: '/checkout',
             templateUrl: './app/templates/checkout.html',
			 controller: 'checkoutCtrl'
         })
		 
});

blsApp.filter('slice', function () {
    return function (arr, start, end) {
        return arr.slice(start, end);
    };
});
