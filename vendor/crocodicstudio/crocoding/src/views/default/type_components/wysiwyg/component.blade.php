@push('bottom')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#textarea_{{$name}}').summernote({
                height: ($(window).height() - 300),
                callbacks: {
                    onImageUpload: function (image) {
                        uploadImage{{$name}}(image[0]);
                    }
                }
            });

            function uploadImage{{$name}}(image) {
                var data = new FormData();
                data.append("userfile", image);
                $.ajax({
                    url: '{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath("upload-summernote")}}',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: data,
                    type: "post",
                    success: function (url) {
                        var image = $('<img>').attr('src', url);
                        $('#textarea_{{$name}}').summernote("insertNode", image[0]);
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            }
        })
    </script>
@endpush
<div class='form-group' id='form-group-{{$name}}' style="{{@$form['style']}}">
    <label>{{$form['label']}}</label>

    <textarea id='textarea_{{$name}}' id="{{$name}}" {{$required}} {{$readonly}} {{$disabled}} name="{{$form['name']}}" class='form-control'
              rows='5'>{{ $value }}</textarea>
    <div class="text-danger">{{ (isset($errors))?$errors->first($name):'' }}</div>
    <p class='help-block'>{{ @$form['help'] }}</p>
</div>
