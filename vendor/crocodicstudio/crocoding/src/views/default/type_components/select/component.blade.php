<?php $default = !empty($form['default']) ? $form['default'] : '** Please select a' . " " . $form['label'];?>
@if(isset($form['parent_select']))
    <?php
    $parent_select = (count(explode(",", $form['parent_select'])) > 1) ? explode(",", $form['parent_select']) : $form['parent_select'];
    $parent = is_array($parent_select) ? $parent_select[0] : $parent_select;
    $add_field = is_array($parent_select) ? $parent_select[1] : '';
    ?>
    @push('bottom')
        <script type="text/javascript">
            $(function () {
                $('#{{$parent}}, input:radio[name={{$parent}}]').change(function () {
                    var $current = $("#{{$form['name']}}");
                    var parent_id = $(this).val();
                    var fk_name = "{{$parent}}";
                    var fk_value = $(this).val();
                    var datatable = "{{$form['datatable']}}".split(',');
                        @if(!empty($add_field))
                    var add_field = ($("#{{$add_field}}").val()) ? $("#{{$add_field}}").val() : "";
                        @endif
                    var datatableWhere = "{{$form['datatable_where']}}";
                    @if(!empty($add_field))
                    if (datatableWhere) {
                        if (add_field) {
                            datatableWhere = datatableWhere + " and {{$add_field}} = " + add_field;
                        }
                    } else {
                        if (add_field) {
                            datatableWhere = "{{$add_field}} = " + add_field;
                        }
                    }
                        @endif
                    var table = datatable[0].trim('');
                    var label = datatable[1].trim('');
                    var value = "{{$value}}";

                    if (fk_value != '') {
                        $current.html("<option value=''>Please wait loading... {{$form['label']}}");
                        $.get("{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath('data-table')}}?table=" + table + "&label=" + label + "&fk_name=" + fk_name + "&fk_value=" + fk_value + "&datatable_where=" + encodeURI(datatableWhere), function (response) {
                            if (response) {
                                $current.html("<option value=''>{{$default}}");
                                $.each(response, function (i, obj) {
                                    var selected = (value && value == obj.select_value) ? "selected" : "";
                                    $("<option " + selected + " value='" + obj.select_value + "'>" + obj.select_label + "</option>").appendTo("#{{$form['name']}}");
                                })
                                $current.trigger('change');
                            }
                        });
                    } else {
                        $current.html("<option value=''>{{$default}}");
                    }
                })

                $('#{{$parent}}').trigger('change');
                $("input[name='{{$parent}}']:checked").trigger("change");
                $("#{{$form['name']}}").trigger('change');
            })
        </script>
    @endpush

@endif
<div class='form-group {{$header_group_class}} {{ (isset($errors))?($errors->first($name))?"has-error":"":"" }}' id='form-group-{{$name}}'
     style="{{@$form['style']}}">
    <label>
        {{$form['label']}}
        @if($required)
            <span class='text-danger' title='This field is required'>*</span>
        @endif
    </label>

    <select class='form-control' id="{{$name}}" data-value='{{$value}}' {{$required}} {!!$placeholder!!} {{$readonly}} {{$disabled}} name="{{$name}}">
        <option value=''>{{$default}}</option>
        <?php
        if (!isset($form['parent_select'])) {
            if (@$form['dataquery']):

                $query = DB::select(DB::raw($form['dataquery']));
                if ($query) {
                    foreach ($query as $q) {
                        $selected = ($value == $q->value) ? "selected" : "";
                        echo "<option $selected value='$q->value'>$q->label</option>";
                    }
                }

            endif;

            if (@$form['dataenum']):
                $dataenum = $form['dataenum'];
                $dataenum = (is_array($dataenum)) ? $dataenum : explode(";", $dataenum);

                foreach ($dataenum as $d) {

                    $val = $lab = '';
                    if (strpos($d, '|') !== FALSE) {
                        $draw = explode("|", $d);
                        $val = $draw[0];
                        $lab = $draw[1];
                    } else {
                        $val = $lab = $d;
                    }

                    $select = ($value == $val) ? "selected" : "";

                    echo "<option $select value='$val'>$lab</option>";
                }
            endif;

            if (@$form['datatable']):
                $raw = explode(",", $form['datatable']);
                $format = isset($form['datatable_format']) ? $form['datatable_format'] : "";
                $table1 = $raw[0];
                $column1 = $raw[1];

                @$table2 = $raw[2];
                @$column2 = $raw[3];

                @$table3 = $raw[4];
                @$column3 = $raw[5];

                $selects_data = DB::table($table1)->select($table1 . ".id");

                if (\Schema::hasColumn($table1, 'deleted_at')) {
                    $selects_data->where($table1 . '.deleted_at', NULL);
                }

                if (@$form['datatable_where']) {
                    $selects_data->whereraw($form['datatable_where']);
                }

                if ($table1 && $column1) {
                    $orderby_table = $table1;
                    $orderby_column = $column1;
                }

                if ($table2 && $column2) {
                    $selects_data->join($table2, $table2 . '.id', '=', $table1 . '.' . $column1);
                    $orderby_table = $table2;
                    $orderby_column = $column2;
                }

                if ($table3 && $column3) {
                    $selects_data->join($table3, $table3 . '.id', '=', $table2 . '.' . $column2);
                    $orderby_table = $table3;
                    $orderby_column = $column3;
                }

                if ($format) {
                    $format = str_replace('&#039;', "'", $format);
                    $selects_data->addselect(DB::raw("CONCAT($format) as label"));
                    $selects_data = $selects_data->orderby(DB::raw("CONCAT($format)"), "asc")->get();
                } else {
                    $selects_data->addselect($orderby_table . '.' . $orderby_column . ' as label');
                    $selects_data = $selects_data->orderby($orderby_table . '.' . $orderby_column, "asc")->get();
                }

                foreach ($selects_data as $d) {

                    $val = $d->id;
                    $select = ($value == $val) ? "selected" : "";

                    echo "<option $select value='$val'>" . $d->label . "</option>";
                }
            endif;
        } //end if not parent select
        ?>
    </select>
    <div class="text-danger">{!! (isset($errors))?$errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"":"" !!}</div>
    <p class='help-block'>{{ @$form['help'] }}</p>
</div>

@push('bottom')
    <script>
        $(function () {
            $('#{{$name}}').SumoSelect({search: true, searchText: 'Search....'});
        })
    </script>
@endpush
