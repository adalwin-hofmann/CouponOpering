<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        @include('admin.master.templates.header')
    </head>
    <body>
        @if(!isset($navbar) || (isset($navbar) && $navbar == false))
        @include('admin.master.templates.navbar')
        @endif

        <div id="wrap">
        @if(!isset($sidebar) || (isset($sidebar) && $sidebar == false))
        @yield('sidebar', array())
        @endif
        @yield('body')
        </div>

        @include('admin.master.templates.footer')

    </body>
    <!--<script src="/nightsky/js/jquery.min.js"></script>-->
    <script src="/js/can.custom.js"></script>
    <!--<script src="http://cloud.github.com/downloads/bitovi/canjs/can.jquery-1.1.2.min.js"></script>-->
    <!-- <script src="/js/bootstrap.min.js"></script> -->
    <?php $cancache = Feature::findByName('canmodels_cache_version'); ?>
    <script src="/js/canmodels.js?version={{empty($cancache) ? '0' : $cancache->value}}"></script>
    <script src="/js/jquery.inputmask.js"></script>
    <script src="/js/jquery.inputmask.date.extension.js"></script>
    @include('admin.master.jscode.master')
    @section('code')
        {{isset($code) ? $code : ''}}
    @show
    <!--<script src="/js/jquery-effects.min.js"></script>-->
    <!-- <script src="/js/modernizr.custom.js"></script> -->


</html>
