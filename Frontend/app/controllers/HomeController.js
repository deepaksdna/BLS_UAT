(function () {
    var homeController = function ($scope, $http, $stateParams,$location, blsService) {
        //console.log('Call homecontroller');
        $scope.categories = [];
        $scope.slides = [];
        $scope.PromoOption = [];
        $scope.promoValues = [];
        $scope.showme = false;
        var promoOptionId = $stateParams.PromoOptionId;
        var promoOptionName = $stateParams.Name;
        $scope.name = promoOptionName;
        $scope.direction = 'left';
        $scope.currentIndex = 0;

        $scope.setCurrentSlideIndex = function (index) {
            $scope.direction = (index > $scope.currentIndex) ? 'left' : 'right';
            $scope.currentIndex = index;
        };

        $scope.isCurrentSlideIndex = function (index) {
            return $scope.currentIndex === index;
        };

        $scope.prevSlide = function () {
            $scope.direction = 'left';
            $scope.currentIndex = ($scope.currentIndex < $scope.slides.length - 1) ? ++$scope.currentIndex : 0;
        };

        $scope.nextSlide = function () {
            $scope.direction = 'right';
            $scope.currentIndex = ($scope.currentIndex > 0) ? --$scope.currentIndex : $scope.slides.length - 1;
        };
        $scope.isActive = function (route) {
            return route === $location.path();
        }
        getCategories();
        getImages();
        getPromoOption();
        if (promoOptionId != null) {
            getPromoOptionValues();
        }
        function getCategories() {
            blsService.getCategories().success(function (data) {
                $scope.categories = data;
              // console.log($scope.categories);
              }).error(function (err) {
              //  console.log(err);
            });
        };
        function getImages() {
            blsService.getImages().success(function (data) {
                $scope.sliderimage = data.image_path;
                angular.forEach(data.results, function (index, itemData) {
                    $scope.slides.push({ 'id': index.id, 'image': index.image });
                });
            });
        }
        function getPromoOption() {
            blsService.getPromoOption().success(function (data) {
                $scope.promoOptions = data;
            });
        }
        function getPromoOptionValues() {
            blsService.getPromoOptionValues(promoOptionId).success(function (result) {
                $scope.promoValues = result.parent_cat;
                console.log($scope.promoValues);
            });
        }
    }
    angular.module('blsApp')
        .controller('homeController', ['$scope', '$http', '$stateParams', '$location', 'blsService', homeController]);
}());