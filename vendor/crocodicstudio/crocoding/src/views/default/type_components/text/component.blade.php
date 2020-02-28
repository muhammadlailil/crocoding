<div class='form-group {{$header_group_class}} {{ (isset($errors))?($errors->first($name))?"has-error":"":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">

    <label>
        {{$form['label']}}
        @if($required)
            <span class='text-danger' title='This field is required'>*</span>
        @endif
    </label>

    <input type='text' title="{{$form['label']}}" {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} {{isset($validation['max'])?"maxlength=".$validation['max']:""}} class='form-control' name="{{$name}}" id="{{$name}}" value='{{$value}}'/>

    <div class="text-danger">{!! (isset($errors))?$errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"":"" !!}</div>
    <p class='help-block'>{{ @$form['help'] }}</p>

</div>
