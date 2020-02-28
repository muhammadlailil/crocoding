@foreach($addaction as $a)
    <?php
    foreach ($row as $key => $val) {
        $a['url'] = str_replace("[".$key."]", $val, $a['url']);
    }

    $confirm_box = '';
    if (isset($a['confirmation']) && ! empty($a['confirmation']) && $a['confirmation']) {

        $a['confirmation_title'] = ! empty($a['confirmation_title']) ? $a['confirmation_title'] : 'Confirmation';
        $a['confirmation_text'] = ! empty($a['confirmation_text']) ? $a['confirmation_text'] : 'Are you sure want to do this action?';
        $a['confirmation_type'] = ! empty($a['confirmation_type']) ? $a['confirmation_type'] : 'warning';
        $a['confirmation_showCancelButton'] = empty($a['confirmation_showCancelButton']) ? 'true' : 'false';
        $a['confirmation_confirmButtonColor'] = ! empty($a['confirmation_confirmButtonColor']) ? $a['confirmation_confirmButtonColor'] : '#DD6B55';
        $a['confirmation_confirmButtonText'] = ! empty($a['confirmation_confirmButtonText']) ? $a['confirmation_confirmButtonText'] : 'Yes!';
        $a['confirmation_cancelButtonText'] = ! empty($a['confirmation_cancelButtonText']) ? $a['confirmation_cancelButtonText'] : 'No';
        $a['confirmation_closeOnConfirm'] = empty($a['confirmation_closeOnConfirm']) ? 'true' : 'false';

        $confirm_box = '
        swal({   
            title: "'.$a['confirmation_title'].'",
            text: "'.$a['confirmation_text'].'",
            type: "",
            showCancelButton: '.$a['confirmation_showCancelButton'].',
            confirmButtonColor: "'.$a['confirmation_confirmButtonColor'].'",
            confirmButtonText: "'.$a['confirmation_confirmButtonText'].'",
            cancelButtonText: "'.$a['confirmation_cancelButtonText'].'",
            closeOnConfirm: '.$a['confirmation_closeOnConfirm'].', }, 
            function(){  location.href="'.$a['url'].'"});        

        ';
    }

    $label = $a['label'];
    $title = ($a['title']) ?: $a['label'];
    $icon = $a['icon'];
    $color = $a['color'] ?: 'primary';
    $confirmation = $a['confirmation'];


    $url = $a['url'];
    if (isset($confirmation) && ! empty($confirmation)) {
        $url = "javascript:;";
    }

    if (isset($a['showIf'])) {

        $query = $a['showIf'];

        foreach ($row as $key => $val) {
            $query = str_replace("[".$key."]", '"'.$val.'"', $query);
        }

        @eval("if($query) {
          echo \"<a class='btn btn-sm btn-\$color' title='\$title' onclick='\$confirm_box' href='\$url'><i class='\$icon'></i> $label</a>&nbsp;\";
      }");
    } else {
        echo "<a class='btn btn-sm btn-$color' title='$title' onclick='$confirm_box' href='$url'><i class='$icon'></i> $label</a>&nbsp;";
    }
    ?>
@endforeach

@if(\crocodicstudio\crocoding\helpers\Crocoding::isRead() && $button_detail)
    <a class='btn btn-sm btn-primary mr-1 btn-detail' title='Detail Data'
       href='{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath("detail/".$row->$pk)."?return_url=".urlencode(requestFullUrl())}}'>
        <i class="material-icons">remove_red_eye</i>
    </a>
@endif

@if(\crocodicstudio\crocoding\helpers\Crocoding::isUpdate() && $button_edit)
    <a class='btn btn-sm btn-royal-blue mr-1 btn-edit' title='Edit Data'
       href='{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath("edit/".$row->$pk)."?return_url=".urlencode(requestFullUrl())."&parent_id=".g("parent_id")."&parent_field=".$parent_field}}'>
        <i class="material-icons">edit</i>
    </a>
@endif

@if(\crocodicstudio\crocoding\helpers\Crocoding::isDelete() && $button_delete)
    <?php $url = \crocodicstudio\crocoding\helpers\Crocoding::mainpath("delete/".$row->$pk);?>
    <a class='btn btn-sm btn-danger mr-1 btn-delete' title='Delete' href='javascript:;'
       onclick='{{\crocodicstudio\crocoding\helpers\Crocoding::deleteConfirm($url)}}'>
        <i class="material-icons">delete</i>
    </a>
@endif
