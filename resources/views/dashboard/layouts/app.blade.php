<!DOCTYPE html>
<html lang="{{ App::getLocale() }} " dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title','Hiring')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {{-- <!-- Bootstrap 3.3.7 --> --}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_files/css/skin-blue.min.css') }}">

    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/font-awesome-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/AdminLTE-rtl.min.css') }}">
        <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/bootstrap-rtl.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/rtl.css') }}">

        <style>
            body,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                font-family: 'Cairo', sans-serif !important;
            }

            .col-print-1 {
                width: 8%;
                float: left;
            }

            .col-print-2 {
                width: 16%;
                float: left;
            }

            .col-print-3 {
                width: 25%;
                float: left;
            }

            .col-print-4 {
                width: 33%;
                float: left;
            }

            .col-print-5 {
                width: 42%;
                float: left;
            }

            .col-print-6 {
                width: 50%;
                float: left;
            }

            .col-print-7 {
                width: 58%;
                float: left;
            }

            .col-print-8 {
                width: 66%;
                float: left;
            }

            .col-print-9 {
                width: 75%;
                float: left;
            }

            .col-print-10 {
                width: 83%;
                float: left;
            }

            .col-print-11 {
                width: 92%;
                float: left;
            }

            .col-print-12 {
                width: 100%;
                float: left;
            }

            @media print {
                .no-print {
                    visibility: hidden;
                }
            }

        </style>
    @else
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/AdminLTE.min.css') }}">
    @endif

    <style>
        .mr-2 {
            margin-right: 5px;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid #367fa9;
            width: 60px;
            height: 60px;
            -webkit-animation: spin 1s linear infinite;
            animation: spin 1s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

    </style>
    {{-- <!-- jQuery 3 --> --}}
    <script src="{{ asset('dashboard_files/js/jquery.min.js') }}"></script>

    {{-- noty --}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/noty/noty.css') }}">
    <script src="{{ asset('dashboard_files/plugins/noty/noty.min.js') }}"></script>

    {{-- <!-- morris --> --}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/morris/morris.css') }}">

    {{-- <!-- iCheck --> --}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/icheck/all.css') }}">

    {{-- html in  ie --}}
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    @stack('style')
</head>

<body class="hold-transition skin-blue sidebar-mini">

    <div class="wrapper">



        @include('dashboard.layouts._navbar')


        @include('dashboard.layouts._aside')


        @yield('content')

        @include('dashboard.partials._session')

        @stack('char')

        @include('dashboard.layouts._footer')


    </div><!-- end of wrapper -->

    {{-- <!-- Bootstrap 3.3.7 --> --}}
    <script src="{{ asset('dashboard_files/js/bootstrap.min.js') }}"></script>

    {{-- icheck --}}
    <script src="{{ asset('dashboard_files/plugins/icheck/icheck.min.js') }}"></script>

    {{-- <!-- FastClick --> --}}
    <script src="{{ asset('dashboard_files/js/fastclick.js') }}"></script>

    {{-- <!-- AdminLTE App --> --}}
    <script src="{{ asset('dashboard_files/js/adminlte.min.js') }}"></script>

    {{-- <!-- Jqurey Number --> --}}
    <script src="{{ asset('dashboard_files/js/jquery.number.min.js') }}"></script>

    {{-- <!-- Jqurey Print_this --> --}}
    <script src="{{ asset('dashboard_files/js/printThis.js') }}"></script>


    {{-- <!-- CKEditor App --> --}}
    <script src="{{ asset('dashboard_files/plugins/ckeditor/ckeditor.js') }}"></script>

    {{-- morris --}}
    <script src="{{ asset('dashboard_files/plugins/morris/raphael-min.js') }}"></script>
    <script src="{{ asset('dashboard_files/plugins/morris/morris.min.js') }}"></script>

    {{-- custom js --}}
    <script src="{{ asset('dashboard_files/js/custom/order.js') }}"></script>
    @yield('scripts')

    <script>
        $(document).ready(function() {
            $('.sidebar-menu').tree();

            //icheck
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

            // delete Noty
            $('.delete').click(function(e) {

                var that = $(this)

                e.preventDefault();

                var n = new Noty({
                    text: "@lang('site.confirm_delete')",
                    type: "warning",
                    killer: true,
                    buttons: [
                        Noty.button("@lang('site.yes')", 'btn btn-success mr-2',
                    function() {
                            that.closest('form').submit();
                        }),

                        Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function() {
                            n.close();
                        })
                    ]
                });

                n.show();

            }); //end of delete


            //image Preview
            $(".image").change(function() {

                if (this.files && this.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('.image-preview').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });

            CKEDITOR.config.language = "{{ app()->getLocale() }}";




        });


/////ajax code dyanamic select
//country && city
$('select[name="country_id"]').on('change',function(){
    var id = $(this).val();
    if(!id){
        $('select[name="city_id"]').empty();

    }
    var url = "{{route('dashboard.getcities',':id')}}";
    url = url.replace(':id',id);
    $.ajax({
                    url: url  ,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="city_id"]').empty();
                        $.each(data, function(key, value) {
                                $('select[name="city_id"]').append('<option value="' +
                                    value.id + '">' + value.name+ '</option>');
                           
                            });
                    },
                });

           
        });
        
//onload 
var id = $('select[name="country_id"]').val();
var url = "{{route('dashboard.getcities',':id')}}";
    url = url.replace(':id',id);
    $.ajax({
                    url: url  ,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="city_id"]').empty();
                        $.each(data, function(key, value) {
                                $('select[name="city_id"]').append('<option value="' +
                                    value.id + '">' + value.name+ '</option>');
                           
                            });
                    },
                });
//job category and job specialist
$('select[name="job_id"]').on('change',function(){
    var id = $(this).val();
    var url = "{{route('dashboard.getAvMeeing',':id')}}";
    url = url.replace(':id',id);
    $.ajax({
                    url: url  ,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="avmeeting_id"]').empty();
                        $.each(data, function(key, value) {
                                $('select[name="avmeeting_id"]').append('<option value="' +
                                    value.id + '">' + value.time_from+'-'+value.time_to+ '</option>');
                           
                            });
                    },
                });

           
        });
$('select[name="category_id"]').on('change',function(){
    var id = $(this).val();
            var url = "{{route('dashboard.getjobs',':id')}}"
            url = url.replace(':id',id);
                $.ajax({
                    url: url  ,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="job_specialize"]').empty();
                        $.each(data, function(key, value) {
                                $('select[name="job_specialize"]').append('<option value="' +
                                    value.id + '">' + value.name+ '</option>');
                           
                            });
                    },
                });
           
        });
//onload
var id = $('select[name="category_id"]').val();
var url = "{{route('dashboard.getjobs',':id')}}";
    url = url.replace(':id',id);
    $.ajax({
                    url: url  ,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        $('select[name="job_specialize"]').empty();
                        $.each(data, function(key, value) {
                                $('select[name="job_specialize"]').append('<option value="' +
                                    value.id + '">' + value.name+ '</option>');
                           
                            });
                    },
                });        

    </script>
    @stack('script')

</body>

</html>
