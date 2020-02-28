<div class='bootstrap-timepicker'>
    <div class='form-group {{$header_group_class}} {{ (isset($errors))?($errors->first($name))?"has-error":"":'' }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
        <label >{{$form['label']}}
            @if($required)
                <span class='text-danger' title='This field is required'>*</span>
            @endif
        </label>

        <div class="input-group">
            @if(!$disabled)
                <span class="input-group-addon"><i class='fa fa-clock-o'></i></span>
            @endif
            <input type='text' title="{{$form['label']}}"
                   {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} class='form-control notfocus timepicker' name="{{$name}}" id="{{$name}}"
                   readonly value='{{$value}}'/>
        </div>
        <div class="text-danger">{!! (isset($errors))?$errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"":'' !!}</div>
        <p class='help-block'>{{ @$form['help'] }}</p>
    </div>
</div>
