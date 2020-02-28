<div class='form-group {{$header_group_class}} {{ (isset($errors))?($errors->first($name))?"has-error":"":'' }}' id='form-group-{{$name}}'
     style="{{@$form['style']}}">
    <label>{{$form['label']}}
        @if($required)
            <span class='text-danger' title='This field is required'>*</span>
        @endif
    </label>

    <div id="{{$name}}"></div>
    <textarea name="{{$name}}" style="display:none"></textarea>

    <div class="text-danger">{!! (isset($errors))?$errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"":'' !!}</div>
    <p class='help-block'>{{ @$form['help'] }}</p>

</div>

@push('bottom')
    <script type="text/javascript">
        $(function () {
            // Set an option globally
            JSONEditor.defaults.options.theme = 'bootstrap2';
            JSONEditor.plugins.select2.enable = false;
            JSONEditor.plugins.selectize.enable = true;//to avoid select2

            // Set an option during instantiation
            const container = document.getElementById('{{$name}}');
            var editor = new JSONEditor(container);

            $('[name="{{$name}}"]').parents('form').on('submit', function () {
                $('[name="{{$name}}"]').val(JSON.stringify(editor.getValue()));
                return true;
            })
        })

    </script>

@endpush
