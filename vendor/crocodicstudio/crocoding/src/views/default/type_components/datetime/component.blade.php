<div class='form-group form-datepicker {{$header_group_class}} {{ (isset($errors))?($errors->first($name))?"has-error":"":'' }}' id='form-group-{{$name}}'
     style="{{@$form['style']}}">
    <label>{{$form['label']}}
        @if($required)
            <span class='text-danger' title='This field is required'>*</span>
        @endif
    </label>

    <div class="input-group">

            <span class="input-group-addon"><a href='javascript:void(0)' onclick='$("#{{$name}}").data("daterangepicker").toggle()'><i
                        class='fa fa-calendar'></i></a></span>

        <input type='text' title="{{$form['label']}}" readonly
               {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} class='form-control notfocus datetimepicker' name="{{$name}}" id="{{$name}}"
               value='{{$value}}'/>
    </div>
    <div class="text-danger">{!! (isset($errors))?$errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"":'' !!}</div>
    <p class='help-block'>{{ @$form['help'] }}</p>

</div>
