@push('bottom')

    @if (app()->getLocale() != 'en')
        <script src="{{ asset ('vendor/crocoding/assets/datepicker/locales/bootstrap-datepicker.'.app()->getLocale().'.js') }}"
                charset="UTF-8"></script>
    @endif
    <script type="text/javascript">
        var lang = '{{app()->getLocale()}}';
        $(function () {
            $('.input_date').datepicker({
                format: 'yyyy-mm-dd',
                @if (in_array(app()->getLocale(), ['ar', 'fa']))
                rtl: true,
                @endif
                language: lang
            });

            $('.open-datetimepicker').click(function () {
                $(this).next('.input_date').datepicker('show');
            });

        });

    </script>
@endpush
