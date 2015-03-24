app.controller("myNoteCtrl", function($scope, $http) {
    $scope.message = "";
    $scope.left  = function() {return 100 - $scope.message.length;};
    $scope.clear = function() {$scope.message = "";};
    $scope.save  = function() {alert($scope.message + "   Saved");};
    
    $scope.testhttp = function() 
                              {
                              	    $http.get("http://www.w3schools.com/website/Customers_JSON.php")
                                    .success(function(response) {$scope.names = response;});
    
                              };

    
});