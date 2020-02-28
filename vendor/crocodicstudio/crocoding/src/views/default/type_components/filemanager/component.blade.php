<div class='form-group {{$header_group_class}} {{ (isset($errors))?($errors->first($name))?"has-error":"":'' }}' id='form-group-{{$name}}'
     style='{{@$form["style"]}}'>
    <label>{{$form['label']}}
        @if($required)
            <span class='text-danger' title='This field is required'>*</span>
        @endif
    </label>

    @if($value=='')
        <div class="input-group">
            <input id="thumbnail-{{$name}}" class="form-control" type="text" readonly value='{{$value}}' name="{{$name}}">
            <span class="input-group-btn">
			        <a id="lfm-{{$name}}" data-input="thumbnail-{{$name}}" data-preview="holder-{{$name}}" class="btn btn-primary">
			          @if(@$form['filemanager_type'] == 'file')
                            <i class="fa fa-file-o"></i> Choose an file
                        @else
                            <i class='fa fa-picture-o'></i> Choose an image
                        @endif
			        </a>
			      </span>

        </div>
    @endif

    @if($value)
        <input id="thumbnail-{{$name}}" class="form-control" type="hidden" value='{{$value}}' name="{{$name}}">
        @if(@$form['filemanager_type'] == 'file')
            @if($value)
                <div style='margin-top:15px'><a id='holder-{{$name}}' href='{{asset($value)}}' target='_blank'
                                                title=' Download file {{ basename($value)}}'><i
                            class='fa fa-download'></i> Download file {{ basename($value)}}</a>
                    &nbsp;<a class='btn btn-danger btn-delete btn-xs'
                             onclick='swal({   title: "Are you sure ?",   text: "You will not be able to recover this record data!",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes!",cancelButtonText: "Cancel",   closeOnConfirm: false }, function(){  location.href="{{url($mainpath."/delete-filemanager?file=".$row->{$name}."&id=".$row->id."&column=".$name)}}" });'
                             href='javascript:void(0)' title='Delete'><i class='fa fa-ban'></i></a>
                </div>@endif
        @else
            <p><a data-lightbox="roadtrip" href="{{ ($value)?asset($value):'' }}"><img id='holder-{{$name}}'
                                                                                       {{ ($value)?'src='.asset($value):'' }} style="margin-top:15px;max-height:100px;"></a>
            </p>
        @endif

        @if(!$readonly || !$disabled)
            <p><a class='btn btn-danger btn-delete btn-sm'
                  onclick='swal({   title: "Are you sure ?",   text: "You will not be able to recover this record data!",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes!", cancelButtonText: "Cancel",   closeOnConfirm: false }, function(){  location.href="{{url(\crocodicstudio\crocoding\helpers\Crocoding::mainpath("update-single?table=$table&column=$name&value=&id=$id"))}}" });'><i
                        class='fa fa-ban'></i> Delete </a></p>
        @endif
    @endif


    <div class='help-block'>{{@$form['help']}}</div>
    <div class="text-danger">{!! (isset($errors))?$errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"":'' !!}</div>
</div>
@if(@$form['filemanager_type'])
    @push('bottom')
        <script type="text/javascript">$('#lfm-{{$name}}').filemanager('file', {prefix: "{{url(config('lfm.prefix'))}}"});</script>
    @endpush
@else
    @push('bottom')
        <script type="text/javascript">$('#lfm-{{$name}}').filemanager('images', {prefix: "{{url(config('lfm.prefix'))}}"});</script>
    @endpush
@endif

@push('bottom')
    <style>
            #lfm-{{$name}}{
                padding: 11px;
                color: #fff;
                border-top-left-radius: 0px !important;
                border-bottom-left-radius: 0px;
            }
    </style>
@endpush
