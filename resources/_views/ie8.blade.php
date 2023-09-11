<!doctype html>
<html>
<body class="body-site">
<div class="ie8-block">

    <h2 class="centered">Ваш браузер немножко устарел</h2>
    <div class="img centered">
        <img src="{{ asset('/images/ie8.png') }}">
    </div>

    <div class="ie8-text">
        Вы пользуетесь устаревшей версией браузера Internet Explorer.
        Данная версия браузера не поддерживает многие современные технологии, из-за чего многие
        страницы отображаются некорректно, а главное — на сайтах могут работать не все функции.
        В связи с этим на Ваш суд представляются более современные браузеры. Все они бесплатны,
        легко устанавливаются и просты в использовании. При переходе на любой нижеуказанный браузер
        все ваши закладки и пароли будут перенесены из текущего браузера, вы ничего не потеряете.
    </div>
    <div class="row">
        <div class="col-md-3">
            <noindex>
                <a rel="nofollow" href="http://www.mozilla.org/firefox/">
                    <img style="border:0;" src="{{ asset('/images/firefox.png') }}">
                </a>
            </noindex>
        </div>
        <div class="col-md-3">
            <noindex>
                <a rel="nofollow" href="http://www.google.com/chrome/">
                    <img style="border:0;" src="{{ asset('/images/chrome.png') }}">
                </a>
            </noindex>
        </div>
        <div class="col-md-3">
            <noindex>
                <a rel="nofollow" href="http://www.opera.com/">
                    <img style="border:0;" src="{{ asset('/images/opera.png') }}">
                </a>
            </noindex>
        </div>
        <div class="col-md-3">
            <noindex>
                <a rel="nofollow" href="http://www.microsoft.com/rus/windows/internet-explorer/">
                    <img style="border:0;" src="{{ asset('/images/ie.png') }}">
                </a>
            </noindex>
        </div>
    </div>

</div>
<style>
    .row {
        margin-left: -15px;
        margin-right: -15px;
    }
    .col-md-3{
        position: relative;
        min-height: 1px;
        padding-left: 15px;
        padding-right: 15px;
        float: left;
        width: 20%;
        text-align: center;
    }
    body{
        background: url("/images/seamless_korova.png") repeat;
        background-attachment: fixed;
    }
    .row:before, .row:after {
        content: " ";
        display: table;
    }
    .row:after {
        clear: both;
    }
    .centered{
        text-align: center;
    }
    .ie8-block h2{
        margin: 60px 0 20px 0;
        font-size: 30px;
        color: #71A2FF;
    }
    .img img{
        width: 40%;
    }
    .ie8-text{
        color: #737373;
        margin: 20px 0;
        line-height: 20px;
        text-align: justify;
    }
    .ie8-block{
        padding: 0 25%;
    }
</style>
</body>
</html>