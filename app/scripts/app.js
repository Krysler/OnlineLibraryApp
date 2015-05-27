'use strict';

/**
 * @ngdoc overview
 * @name angularApp
 * @description
 * # angularApp
 *
 * Main module of the application.
 */
angular
  .module('angularApp', [
    'ngAnimate',
    'ngCookies',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'ngTouch',
    'ngAria',
    'ngMessages',
    'ui.bootstrap'
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'ModalDemoCtrl'
      })
      .when('/moreInfo', {
        templateUrl: 'views/moreInfo.html'
      })
      .when('/about', {
        templateUrl: 'views/about.html',
        controller: 'AboutCtrl'
      })
      .when('/registration', {
        templateUrl: 'views/registration.html',
        controller: 'SignUpCtrl'
      })
      .when('/login', {
        templateUrl: 'views/login.html',
        controller: 'ModalDemoCtrl'
      })
      .when('/user', {
        templateUrl: 'views/user.html',
        controller: 'UserCtrl'
      })
      .otherwise({
        redirectTo: function() {
        window.location = "/404.html";
    }
      });
  });
