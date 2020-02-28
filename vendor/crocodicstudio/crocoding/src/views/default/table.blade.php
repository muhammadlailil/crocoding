@push('bottom')
    <script type="text/javascript">
        $(document).ready(function () {
            var $window = $(window);

            function checkWidth() {
                var windowsize = $window.width();
                if (windowsize > 500) {
                    console.log(windowsize);
                    $('#box-body-table').removeClass('table-responsive');
                } else {
                    console.log(windowsize);
                    $('#box-body-table').addClass('table-responsive');
                }
            }

            checkWidth();
            $(window).resize(checkWidth);

            $('#deleteSelctedButton').click(function () {
                var name = $(this).data('name');
                $('#form-table input[name="button_name"]').val(name);
                var title = $(this).attr('title');

                swal({
                        title: "Confirmation",
                        text: "Are you sure want to " + title + " ?",
                        showCancelButton: true,
                        confirmButtonColor: "#008D4C",
                        confirmButtonText: "Yes!",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    },
                    function () {
                        $('#form-table').submit();
                    });

            })

            $('table tbody tr .button_action a').click(function (e) {
                e.stopPropagation();
            })
        });
    </script>
@endpush
<?php
$parameters = requestAll();
$build_query = urldecode(http_build_query($parameters));
?>

<form id='form-table' method='post' action='{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath("action-selected")}}'>
    <input type='hidden' name='button_name' value=''/>
    <input type='hidden' name='_token' value='{{csrf_token()}}'/>
    <table class="table mb-0">
        <thead class="bg-light">
        <tr>
            @if($button_bulk_action && ( ($button_delete && \crocodicstudio\crocoding\helpers\Crocoding::isDelete()) || $button_selected) )
                <th scope="col" class="border-0" style="width: 10px">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id='checkall'>
                        <label class="custom-control-label" for="checkall"></label>
                    </div>
                </th>
            @endif
            @if($show_numbering)
                <th width="1%">No</th>
            @endif
            <?php
            foreach ($columns as $col) {
                if ($col['visible'] === FALSE) continue;

                $sort_column = g('filter_column');
                $colname = $col['label'];
                $name = $col['name'];
                $field = $col['field_with'];
                $width = (isset($col['width'])) ?: "auto";
                $mainpath = trim(\crocodicstudio\crocoding\helpers\Crocoding::mainpath(), '/') . $build_query;
                echo "<th scope='col' width='$width'>";
                if (isset($sort_column[$field])) {
                    switch ($sort_column[$field]['sorting']) {
                        case 'asc':
                            $url = \crocodicstudio\crocoding\helpers\Crocoding::urlFilterColumn($field, 'sorting', 'desc');
                            echo "<a href='$url' title='Click to sort descending'>$colname &nbsp; <i class='fa fa-sort-desc'></i></a>";
                            break;
                        case 'desc':
                            $url = \crocodicstudio\crocoding\helpers\Crocoding::urlFilterColumn($field, 'sorting', 'asc');
                            echo "<a href='$url' title='Click to sort ascending'>$colname &nbsp; <i class='fa fa-sort-asc'></i></a>";
                            break;
                        default:
                            $url = \crocodicstudio\crocoding\helpers\Crocoding::urlFilterColumn($field, 'sorting', 'asc');
                            echo "<a href='$url' title='Click to sort ascending'>$colname &nbsp; <i class='fa fa-sort'></i></a>";
                            break;
                    }
                } else {
                    $url = \crocodicstudio\crocoding\helpers\Crocoding::urlFilterColumn($field, 'sorting', 'asc');
                    echo "<a href='$url' title='Click to sort ascending'>$colname &nbsp; <i class='fa fa-sort'></i></a>";
                }

                echo "</th>";
            }
            ?>

            @if($button_table_action)
                @if(\crocodicstudio\crocoding\helpers\Crocoding::isUpdate() || \crocodicstudio\crocoding\helpers\Crocoding::isDelete() || \crocodicstudio\crocoding\helpers\Crocoding::isRead())
                    <th scope="col" width='{{$button_action_width?:"auto"}}' class="border-0 text-right">
                        <a href="javascript:;">
                            Action
                        </a>
                    </th>
                @endif
            @endif

        </tr>
        </thead>
        <tbody>
            @if(count($result)==0)
                <tr class='warning'>
                    @if($button_bulk_action && $show_numbering)
                        <td colspan='{{count($columns)+3}}'  class="text-center">
                    @elseif( ($button_bulk_action && !$show_numbering) || (!$button_bulk_action && $show_numbering) )
                        <td colspan='{{count($columns)+2}}'  class="text-center">
                    @else
                        <td colspan='{{count($columns)+1}}' class="text-center">
                            @endif
                            <i class='fa fa-search'></i> No Data Avaliable
                        </td>
                </tr>
            @endif
            @foreach($html_contents['html'] as $i=>$hc)
                @if($table_row_color)
                    <?php $tr_color = NULL;?>
                    @foreach($table_row_color as $trc)
                        <?php
                        $query = $trc['condition'];
                        $color = $trc['color'];
                        $row = $html_contents['data'][$i];
                        foreach ($row as $key => $val) {
                            $query = str_replace("[" . $key . "]", '"' . $val . '"', $query);
                        }

                        @eval("if($query) {
                                      \$tr_color = \$color;
                                  }");
                        ?>
                    @endforeach
                    <?php echo "<tr class='$tr_color'>";?>
                @else
                    <tr>
                @endif

                @foreach($hc as $h)
                    <td>{!! $h !!}</td>
                @endforeach
            </tr>

            @endforeach
        </tbody>
    </table>
</form>

<div class="dataTables_info text-left">
    <?php
    $from = $result->count() ? ($result->perPage() * $result->currentPage() - $result->perPage() + 1) : 0;
    $to = $result->perPage() * $result->currentPage() - $result->perPage() + $result->count();
    $total = $result->total();
    ?>
    <span>Showing  {{ $from }} to {{ $to }}  of {{ $total }} entries</span>
</div>
<div class="dataTables_paginate paging_simple_numbers">
    {!! urldecode(str_replace("/?","?",$result->appends(requestAll())->render())) !!}
</div>
@if($columns)
    @push('bottom')

        <script>
            $(function () {
                $('.btn-filter-data').click(function () {
                    $('#filter-data').modal('show');
                })

                $('.btn-export-data').click(function () {
                    $('#export-data').modal('show');
                })

                var toggle_advanced_report_boolean = 1;
                $(".toggle_advanced_report").click(function () {

                    if (toggle_advanced_report_boolean == 1) {
                        $("#advanced_export").slideDown();
                        $(this).html("<i class='fa fa-minus-square'></i> Show Advanced Export");
                        toggle_advanced_report_boolean = 0;
                    } else {
                        $("#advanced_export").slideUp();
                        $(this).html("<i class='fa fa-plus-square'></i> Show Advanced Export");
                        toggle_advanced_report_boolean = 1;
                    }

                })
            })
        </script>

        <!-- MODAL FOR EXPORT DATA-->
        <div class="modal" id='export-data'>
            <div class="headerModal">
                <h4 class="modal-title"><i class='fa fa-download'></i> Export Data</h4>
            </div>

            <form method='post' target="_blank" action='{{ \crocodicstudio\crocoding\helpers\Crocoding::mainpath("export-data?t=".time()) }}'>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                {!! \crocodicstudio\crocoding\helpers\Crocoding::getUrlParameters() !!}
                <div class="bodyModal">
                    <div class="form-group">
                        <label>File Name</label>
                        <input type='text' name='filename' class='form-control' required value='Report {{ $module_name }} - {{date("d M Y")}}'/>
                        <div class='help-block'>
                            You can rename the filename according to your whises
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Max Data</label>
                        <input type='number' name='limit' class='form-control' required value='100' max="100000" min="1"/>
                        <div class='help-block'>Minimum 1 and maximum 100,000 rows per export session</div>
                    </div>

                    <div class='form-group'>
                        <label>Columns</label><br/>
                        @foreach($columns as $col)
                            <div class='checkbox inline'><label><input type='checkbox' checked name='columns[]'
                                                                       value='{{$col["name"]}}'>{{$col["label"]}}</label></div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <label>Format Export</label>
                        <select name='fileformat' class='form-control'>
                            <option value='pdf'>PDF</option>
                            <option value='xls'>Microsoft Excel (xls)</option>
                            <option value='csv'>CSV</option>
                        </select>
                    </div>

                    <p><a href='javascript:void(0)' class='toggle_advanced_report' style="font-size: 13px"><i
                                class='fa fa-plus-square'></i> Show Advanced Export</a></p>

                    <div id='advanced_export' style='display: none'>


                        <div class="form-group">
                            <label>Page Size</label>
                            <select class='form-control' name='page_size'>
                                <option <?=($setting->default_paper_size == 'Letter') ? "selected" : ""?> value='Letter'>Letter</option>
                                <option <?=($setting->default_paper_size == 'Legal') ? "selected" : ""?> value='Legal'>Legal</option>
                                <option <?=($setting->default_paper_size == 'Ledger') ? "selected" : ""?> value='Ledger'>Ledger</option>
                                <?php for($i = 0;$i <= 8;$i++):
                                $select = ($setting->default_paper_size == 'A' . $i) ? "selected" : "";
                                ?>
                                <option <?=$select?> value='A{{$i}}'>A{{$i}}</option>
                                <?php endfor;?>

                                <?php for($i = 0;$i <= 10;$i++):
                                $select = ($setting->default_paper_size == 'B' . $i) ? "selected" : "";
                                ?>
                                <option <?=$select?> value='B{{$i}}'>B{{$i}}</option>
                                <?php endfor;?>
                            </select>
                            <div class='help-block'><input type='checkbox' name='default_paper_size'
                                                           value='1'/> Set As Default Paper Size</div>
                        </div>

                        <div class="form-group">
                            <label>Page Orientation</label>
                            <select class='form-control' name='page_orientation'>
                                <option value='potrait'>Potrait</option>
                                <option value='landscape'>Landscape</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="footerModal text-right">
                    <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary btn-submit" type="submit">Submit</button>
                </div>
            </form>
        </div>
    @endpush
@endif
