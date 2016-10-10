angular.module('blsApp').directive('clientAutoComplete', ['$filter', clientAutoCompleteDir]);

function clientAutoCompleteDir($filter) {
    return {
        restrict: 'A',
        link: function (scope, elem, attrs) {
            elem.autocomplete({
                source: function (request, response) {

                    //term has the data typed by the user
                    var params = request.term;

                    //simulates api call with odata $filter
                    var data = scope.dataSource;
                    if (data) {
                        var result = $filter('filter')(data, { name: params });
                        angular.forEach(result, function (item) {
                            item['value'] = item['name'];
                        });
                    }
                    response(result);

                },
                minLength: 1,
                select: function (event, ui) {
                    //force a digest cycle to update the views
                    scope.$apply(function () {
                        scope.setClientData(ui.item);
                    });
                },

            });
        }

    };
}