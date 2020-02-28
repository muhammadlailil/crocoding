@push('head')
    <style type="text/css">
        .bootstrap-timepicker .dropdown-menu {
            left: 30px !important;
            box-shadow: 0px 0px 20px #aaaaaa;
        }
    </style>
@endpush

@push('bottom')
    <script>
        $(function () {
            $('.bootstrap-timepicker-widget table td a i.glyphicon-chevron-up').addClass('fa fa-sort-asc').removeClass('glyphicon glyphicon-chevron-up')
            $('.bootstrap-timepicker-widget table td a i.glyphicon-chevron-down').addClass('fa fa-sort-desc').removeClass('glyphicon glyphicon-chevron-down')
        })
    </script>
@endpush
