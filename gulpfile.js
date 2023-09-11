let elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */


let paths = {
    'jquery': './vendor/bower_components/jquery/',
    'masonry': './vendor/bower_components/masonry/',
    'ckeditor': './vendor/unisharp/laravel-ckeditor/',
    'bootstrap': './vendor/bower_components/bootstrap-sass-official/assets/',
    'imagesloaded': './vendor/bower_components/imagesloaded/',
    'lazyload': './vendor/bower_components/jquery_lazyload/',
    'raty': './vendor/bower_components/raty/lib/',
    'chosen': './vendor/bower_components/chosen/',
    'jqueryTouchswipe': './vendor/bower_components/jquery-touchswipe/',
    'jqueryFileUpload': './vendor/bower_components/blueimp-file-upload/',
    'datepicker': './vendor/bower_components/bootstrap-datepicker/dist/',
    'jqTree': './vendor/bower_components/jqTree/',
}

elixir(function (mix) {
    mix.sass([
        paths.raty + "jquery.raty.css",
        paths.chosen + "chosen.min.css",
        paths.jqueryFileUpload + 'css/jquery.fileupload.css',
        paths.jqTree + 'jqtree.css',
        "style.scss",
        'common.scss',
        'responsive.scss',
        'slick-theme.css',
        'timeTo.css',
        'animate.css',
        'nivo-slider.css',
        'shopping_cart_btn.scss'
    ], 'public/css/style.css', {includePaths: [paths.bootstrap + 'stylesheets/']})
        .copy([
            paths.raty + 'fonts/**'
        ], 'public/fonts')
        .copy([
            './vendor/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
        ], 'public/js')
        .copy([
            './vendor/bower_components/moment/min/moment.min.js'
        ], 'public/js')
        .copy([
            './vendor/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'
        ], 'public/css')
        .copy([
            paths.bootstrap + 'fonts/bootstrap/**'
        ], 'public/build/fonts/bootstrap')
        .scripts([
            paths.jquery + "dist/jquery.js",
            paths.bootstrap + "javascripts/bootstrap.js",
            paths.masonry + "dist/masonry.pkgd.js",
            paths.imagesloaded + "imagesloaded.js",
            paths.lazyload + "jquery.lazyload.js",
            paths.raty + "jquery.raty.js",
            paths.datepicker + "js/bootstrap-datepicker.min.js",
            paths.chosen + "chosen.jquery.min.js",
            paths.jqTree + 'tree.jquery.js',
            'jquery.maskedinput.min.js',
            'bootstrap3-typeahead.min.js',
            'jquery.nivo.slider.pack.js',
            'jquery.time-to.js',
            'blur.js',
            'app.js',
            'shopping_cart.js'
        ])
        .scripts([
            paths.jqueryFileUpload + 'js/jquery.fileupload.js',
            paths.jqueryFileUpload + 'js/jquery.fileupload-process.js',
            paths.jqueryFileUpload + 'js/jquery.fileupload-image.js',
            paths.jqueryFileUpload + 'js/jquery.iframe-transport.js',
            paths.jqueryFileUpload + 'js/vendor/jquery.ui.widget.js'
        ], 'public/js/file-upload.js')
        .scripts([
            'easyzoom.js', 'product.js'
        ], 'public/js/product.js')
        .version(["css/style.css", "js/all.js", "js/product.js", "js/file-upload.js"]).copy([
            paths.chosen + "chosen-sprite.png",
            paths.chosen + "chosen-sprite@2x.png"
        ], 'public/build/css'
    );
});
