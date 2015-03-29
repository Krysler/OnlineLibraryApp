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
    'ngMessages'
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'MainCtrl'
      })
      .when('/about', {
        templateUrl: 'views/about.html',
        controller: 'AboutCtrl'
      })
      .when('/contact', {
        templateUrl: 'views/contact.html',
        controller: 'ContactCtrl'
      })
      .when('/registration', {
        templateUrl: 'views/registration.html',
        controller: 'SignUpCtrl'
      })
      .when('/login', {
        templateUrl: 'views/login.html',
        controller: 'login'
      })
      .when('/user', {
        templateUrl: 'views/user.html',
        controller: 'user'
      })
      .otherwise({
        redirectTo: function() {
        window.location = "/404.html";
    }
      });
  });
