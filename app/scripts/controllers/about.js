'use strict';

/**
 * @ngdoc function
 * @name angularApp.controller:AboutCtrl
 * @description
 * # AboutCtrl
 * Controller of the angularApp
 */
angular.module('angularApp')
  .controller('AboutCtrl', function () {

  	this.tab = 1;
  	this.selectTab = function(setTab){
  		this.tab = setTab;
  	};
  	this.isSelected = function(selected){
  		return this.tab === selected;
  	};

  	this.devSelect = function(devSelect){
  		this.dev= devSelect;
  	};
  	this.isDevSelected = function(devSelected){
  		return this.dev === devSelected;
  	};
  });
