<div class='form-group {{$header_group_class}} {{ (isset($errors))?($errors->first($name))?"has-error":"":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
    <label>
        {{$form['label']}}
        @if($required)
            <span class='text-danger' title='This field is required'>*</span>
        @endif
    </label>


    @if($value)
        <?php
        if(Storage::exists($value) || file_exists($value)):
        $url = asset($value);
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $images_type = array('jpg', 'png', 'gif', 'jpeg', 'bmp', 'tiff');
        if(in_array(strtolower($ext), $images_type)):
        ?>
        <p>
            <a class="image-popup" href='{{$url}}'>
                <img style='max-width:160px' title="Image For {{$form['label']}}" src='{{$url}}'/>
            </a>
        </p>
        <?php else:?>
        <p><a href='{{$url}}'>Download file</a></p>
        <?php endif;
        echo "<input type='hidden' name='_$name' value='$value'/>";
        else:
            echo "<p class='text-danger'><i class='fa fa-exclamation-triangle'></i> Oops looks like File was Broken !. Click Delete and Re-Upload.</p>";
        endif;
        ?>
        @if(!$readonly || !$disabled)
                <a class='btn btn-danger btn-delete btn-sm' onclick="if(!confirm('Are you sure ?')) return false"
                   href='{{url(\crocodicstudio\crocoding\helpers\Crocoding::mainpath("delete-image?image=".$value."&id=".$row->id."&column=".$name))}}'>
                    <i class='fa fa-ban'></i> Delete
                </a>
        @endif
    @endif
    @if(!$value)
        <input type='file' id="{{$name}}" title="{{$form['label']}}" {{$required}} {{$readonly}} {{$disabled}} class='form-control' name="{{$name}}"/>
        <p class='help-block'>{{ @$form['help'] }}</p>
    @else
        <br>
        <em class="help-block">* If you want to upload other file, please first delete the file.</em>
    @endif
    <div class="text-danger">{!! (isset($errors))?$errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"":"" !!}</div>

</div>
