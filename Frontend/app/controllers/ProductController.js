(function () {
    var productCtrl = function ($scope, $http, $stateParams, $state, $filter, blsService) {

        $scope.products = [];
        $scope.brandproducts = [];
        $scope.brandslist = [];
        $scope.brands = [];
        $scope.showme = false;
        $scope.cat_img = '';
        $scope.prod_img = '';
        $scope.marketingimage = '';
        $scope.productdetails = [];
        $scope.product_BRAND_DETAILS = [];
        $scope.productmarketingimages = [];
        $scope.relatedproducts = [];
        $scope.colours = [];
        $scope.currentPage = 0;
        $scope.pageSize = 4;
        if ($state.params.obj == undefined) {
        }
        else {
           // console.log($state.params.obj);
            $scope.products = $state.params.obj.products;
            $scope.prod_img = $state.params.obj.PRODUCTS_IMG_PATH;
        }
        var categoryId = $stateParams.CategoryId;
        var productId = $stateParams.ProductId;
        var brandId = $stateParams.BrandId;
        $scope.CategoryId = categoryId;
        if (categoryId != null && brandId == 0) {
            //console.log('first');
            getProducts();
        }
        if (productId != null) {
            getProductDetail();
        }
        if (brandId != null && brandId != 0 && categoryId != 0) {
            getProductBrand();
        }

        $scope.Next = function () {
            if ($scope.currentPage >= $scope.brands.length / $scope.pageSize - 1) {
                $scope.currentPage = 0;
            }
            else {
                $scope.currentPage = $scope.currentPage + 1;
            }

        }
        $scope.Previous = function () {
            $scope.currentPage = $scope.currentPage - 1;
        }


        $scope.count = 1;
        //  var max = $scope.count + 1;
        //  var min = $scope.count - 1;

        $scope.increment = function () {
            //if ($scope.count >= 11) { return; }
            $scope.count++;
            $scope.countValue = $scope.count;
        };
        $scope.decrement = function () {
            if ($scope.count <= 1) { return; }
            $scope.count--;
            $scope.countValue = $scope.count;
        };
        $scope.getProductInfo=function(prodId)
        {
            blsService.getProductDetail(prodId).success(function (data) {
                $scope.productInfo = data.PRODUCT_DETAILS;
                $scope.prodimage = data.PRODUCTMARKETING_IMAGE_PATH;
               // console.log($scope.productInfo);
               // console.log($scope.productInfo.image);
            });
        
        }
        $scope.addToCartItem = function () {
           // console.log('dfdf');
            blsService.addToCartItem(productId, $scope.count, blsService.Token, blsService.UserId).success(function (data) {
                console.log(data);
            });
        }
        function getProducts() {
            $scope.products = [];
            blsService.getProducts(categoryId).success(function (data) {
                angular.forEach(data.products, function (e, i) {
                    e.list_price = parseFloat(e.list_price);
                    if (i === data.products.length - 1) {
                        $scope.products = data.products;
                    }
                });
                $scope.brandslist = data.BRANDS_LIST_FOR_THIS_CAT;
                angular.forEach($scope.brandslist, function (index, itemData) {
                    $scope.brands.push({ 'id': index.brand_id, 'name': index.brand_name, 'templateId': index.BRAND_TEMPLATES });
                });
                $scope.cat_img = data.CATEGORY_IMG_PATH;
                $scope.prod_img = data.IMAGE_PATH;
                $scope.brandproducts = $scope.products;
            }).error(function (err) {
                console.log(err);
            });
        }
        //function getProducts() {
        //    $scope.products = [];
        //    blsService.getProducts(categoryId).success(function (data) {
        //        $scope.products = data.products;
        //        //console.log($scope.products);
        //        $scope.brandslist = [

        //            {
        //                "brand_id": "1",
        //                "brand_name": "Dell",
        //                "BRAND_TEMPLATES": "1",
        //                "category_id": "13",
        //                "category_name": "Letter Trayers & Drawers"
        //            },
        //            {
        //                "brand_id": "2",
        //                "brand_name": "Faber",
        //                "BRAND_TEMPLATES": "2",
        //                "category_id": "13",
        //                "category_name": "Letter Trayers & Drawers"
        //            },
        //            {
        //                "brand_id": "3",
        //                "brand_name": "Pelikan",
        //                "BRAND_TEMPLATES": "3",
        //                "category_id": "13",
        //                "category_name": "Letter Trayers & Drawers"
        //            },
        //            {
        //                "brand_id": "4",
        //                "brand_name": "Scwan",
        //                "BRAND_TEMPLATES": "1",
        //                "category_id": "13",
        //                "category_name": "Letter Trayers & Drawers"
        //            },
        //            {
        //                "brand_id": "5",
        //                "brand_name": "ABC",
        //                "BRAND_TEMPLATES": "1",
        //                "category_id": "13",
        //                "category_name": "Letter Trayers & Drawers"
        //            },
        //            {
        //                "brand_id": "6",
        //                "brand_name": "DEF",
        //                "BRAND_TEMPLATES": "1",
        //                "category_id": "13",
        //                "category_name": "Letter Trayers & Drawers"
        //            },
        //            {
        //                "brand_id": "7",
        //                "brand_name": "GHI",
        //                "BRAND_TEMPLATES": "1",
        //                "category_id": "13",
        //                "category_name": "Letter Trayers & Drawers"
        //            },
        //            {
        //                "brand_id": "8",
        //                "brand_name": "ere",
        //                "BRAND_TEMPLATES": "1",
        //                "category_id": "13",
        //                "category_name": "Letter Trayers & Drawers"
        //            },
        //            {
        //                "brand_id": "9",
        //                "brand_name": "fdfsf",
        //                "BRAND_TEMPLATES": "1",
        //                "category_id": "13",
        //                "category_name": "Letter Trayers & Drawers"
        //            }


        //        ];

        //        angular.forEach($scope.brandslist, function (index, itemData) {
        //            $scope.brands.push({ 'id': index.brand_id, 'name': index.brand_name, 'templateId': index.BRAND_TEMPLATES });
        //        });

        //        $scope.cat_img = data.CATEGORY_IMG_PATH;
        //        //console.log($scope.cat_img);
        //        // console.log(data.IMAGE_PATH);
        //        $scope.prod_img = data.IMAGE_PATH;

        //        //console.log($scope.brands);
        //    }).error(function (err) {
        //        console.log(err);
        //    });

        //}
        function getProductBrand() {
            $scope.brandproducts = [];
            $scope.brands = [];
            blsService.getProductBrand(brandId, categoryId).success(function (result) {
                angular.forEach(result.products, function (e, i) {
                    e.list_price = parseFloat(e.list_price);
                    if (i === result.products.length - 1) {
                        $scope.brandproducts = result.products;
                    }
                });
                $scope.brandslist = result.BRANDS_LIST_FOR_THIS_CAT;
                angular.forEach($scope.brandslist, function (index, itemData) {
                    $scope.brands.push({ 'id': index.brand_id, 'name': index.brand_name, 'templateId': index.BRAND_TEMPLATES });
                });
                $scope.prod_img = result.IMAGE_PATH;
               // console.log($scope.prod_img);
                $scope.brandprod_img = result.IMAGE_PATH;
            });
        }
        function getProductDetail() {
            blsService.getProductDetail(productId).success(function (data) {
                $scope.productdetails = data.PRODUCT_DETAILS;
                $scope.product_BRAND_DETAILS = data.PRODUCT_BRAND_DETAILS;
                $scope.productmarketingimages = data.PRODUCT_MARKETING_IMAGES;
                $scope.colours = [{ name: 'Choose Color', id: '-1' }];
                angular.forEach(data.COLORS, function (color) {
                    $scope.colours.push({ name: color.COLOR_NAME, id: color.COLOR_ID });
                });
                $scope.selectedColour = $scope.colours[0];
                $scope.relatedproducts = data.RELATED_PRODUCTS;
                $scope.marketingimage = data.PRODUCTMARKETING_IMAGE_PATH + data.PRODUCT_DETAILS.image;
                $scope.prodimage = data.PRODUCTMARKETING_IMAGE_PATH;
                //product information
                $scope.category_id = data.PRODUCT_DETAILS.category_id;
                $scope.brandImage = data.PRODUCT_BRAND_IMAGE_URL;
                $scope.brandName = data.PRODUCT_BRAND_DETAILS.BRAND_NAME;
                $scope.productModel = data.PRODUCT_DETAILS.PRODUCT_MODEL;
                $scope.productTitle = data.PRODUCT_DETAILS.PRODUCT_TITLE;
                $scope.productPrice = data.PRODUCT_DETAILS.list_price;
                $scope.productVideoLink = data.PRODUCT_DETAILS.PRODUCT_VIDEOLINK;
                $scope.productDescription = data.PRODUCT_DETAILS.PRODUCT_DESC;
                $scope.promoPrice = data.PRODUCT_DETAILS.FINAL_DISPLAY_PRICE_WITHOUT_GST;
                $scope.discountType = data.PRODUCT_DETAILS.PROMOTIONS_DISCOUNT_TYPE;
               // console.log($scope.promoPrice);
              
            }).error(function (err) {
                console.log(err);
            });
        };
        $scope.sortByPrice = function (order) {
            if (order == true) {
                var orderBy = $filter('orderBy');
                $scope.sortDesc = true;
            }
            if (order == false) {
                var orderBy = $filter('orderBy');
                $scope.sortDesc = false;
            }
            $scope.products = orderBy($scope.products, 'list_price', order);
            $scope.brandproducts = orderBy($scope.brandproducts, 'list_price', order);
        }
        $scope.Search = function (obj) {
            console.log('button click');
            var keyword = obj.value;
            blsService.searchProduct(keyword).success(function (data) {
                angular.forEach(data.products, function (e, i) {
                    e.list_price = parseFloat(e.list_price);
                    if (i === data.products.length - 1) {
                        $scope.products = data.products;
                    }
                });
                $state.go('product', { obj: data });
            });
        };
        $scope.setimage = function (img) {
            $scope.marketingimage = img;
        };
        $scope.selectedColourProduct = function (value) {
            blsService.getColourProducts(productId, $scope.selectedColour.id).success(function (data) {
                if ($scope.selectedColour.id != -1) {
                    $scope.marketingimage = '';
                    $scope.productmarketingimages = [];
                    $scope.productmarketingimages = data.products;
                    $scope.marketingimage = data.PRODUCTS_IMG_PATH + data.products[0].image;
                    $scope.prodimage = data.PRODUCTS_IMG_PATH;
                }
            });
        };
    }
    angular.module('blsApp')
       .controller('productCtrl', ['$scope', '$http', '$stateParams', '$state', '$filter', 'blsService', productCtrl]);
}());

