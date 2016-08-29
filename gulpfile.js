var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
var gulp = require('gulp');
var webpack = require('laravel-elixir-webpack');

elixir(function(mix) {
    mix.sass('app.scss')
      .version('public/css/app.css');
      .webpack('store-front.js');
});
