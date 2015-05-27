'use strict';

/**
 * @ngdoc function
 * @name angularApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the angularApp
 */

 $(document).ready(function(){
  $(".owl-carousel").owlCarousel();
});
angular.module('angularApp')
  .controller('MainCtrl', function ($scope) {

  	 $scope.openReg = function (size) {

    var modalInstance = $modal.open({
      templateUrl: 'views/registration.html',
      controller: 'ModalInstanceCtrl',
      size: size,
      resolve: {
        items: function () {
          return $scope.items;
        }
      }
    });

    modalInstance.result.then(function (selectedItem) {
      $scope.selected = selectedItem;
    }, function () {
      $log.info('Modal dismissed at: ' + new Date());
    });
  };

  	
    $scope.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];
  });
