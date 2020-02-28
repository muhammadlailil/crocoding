@extends('crocoding::admin_template')

@section('content')

    @push('head')
        <style>
            .table-api tbody tr td a {
                color: #db0e00;
                font-family: arial;
            }
        </style>
    @endpush

    @push('bottom')
        <script>
            $(function () {
                $(".link_name_api").click(function () {
                    $(".detail_api").slideUp();
                    $(this).parent("td").find(".detail_api").slideDown();
                })
                $(".selected_text").each(function () {
                    var n = $(this).text();
                    if (n.indexOf('api_') == 0) {
                        $(this).attr('class', 'selected_text text-danger');
                    }
                })
            })

            function deleteApi(id) {
                var url = "{{url(config('crocoding.ADMIN_PATH').'/api_generator/delete-api')}}/" + id;
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this data!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                }, function () {
                    $.get(url, function (resp) {
                        if (resp.status == 1) {
                            swal("Deleted!", "The data has been deleted.", "success");
                            location.href = document.location.href;
                        }
                    })
                });
            }
        </script>
    @endpush


    <div class="col-sm-12">
        <div class="card card-small mb-4">
            <div class="card-header border-bottom">
                <strong style="margin-top: 4px;display: block;float: left;">
                    API Documentation
                </strong>
                <div class="pull-right">
                    <div class="btn-group btn-group-sm btn-group-toggle d-flex my-auto mx-auto mx-sm-0">
                        <a class="btn btn-white active" href="{{ \crocodicstudio\crocoding\helpers\Crocoding::mainpath() }}">
                            <i class='fa fa-info'></i> API Documentation
                        </a>
                        <a class="btn btn-white" href="{{ \crocodicstudio\crocoding\helpers\Crocoding::mainpath('generator') }}">
                            <i class='fa fa-table'></i> API Generator
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body row cardBodyForm">
                <div class="col-sm-12">
                    <div class='form-group'>
                        <label>API BASE URL</label>
                        <input type='text' readonly class='form-control' title='Hanya klik dan otomatis copy to clipboard (kecuali Safari)'
                               onClick="this.setSelectionRange(0, this.value.length); document.execCommand('copy');" value='{{url('api')}}'/>
                    </div>
                    <table class='table table-striped table-api table-bordered'>
                        <thead>
                        <tr class='info'>
                            <th width='2%'>No</th>
                            <th>API Name
                                <span class='pull-right'>
                      <a class='btn btn-sm btn-xs btn-warning' target="_blank" href='{{\crocodicstudio\crocoding\helpers\Crocoding::mainpath("download-postman")}}'>Export For POSTMAN <sup>Beta</sup>
                      </a>
                    </span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 0;?>
                        @foreach($apis as $api)
                            <?php
                            $parameters = ($api->parameters) ? unserialize($api->parameters) : array();
                            $responses = ($api->responses) ? unserialize($api->responses) : array();
                            ?>
                            <tr>
                                <td><?= ++$no;?></td>
                                <td>
                                    <a href='javascript:void(0)' title='API {{$ac->nama}}' style='color:#009fe3' class='link_name_api'><?=$api->nama;?></a>
                                    &nbsp;
                                    <sup>
                                        <a title='Delete this API' onclick="deleteApi({{$api->id}})" href="javascript:void(0)">
                                            <i class='fa fa-trash'></i>
                                        </a>
                                        <a title='Edit This API' href="{{url(config('crocoding.ADMIN_PATH').'/api_generator/edit-api').'/'.$api->id}}">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    </sup>
                                    <div class='detail_api' style='display:none'>
                                        <table class='table table-bordered'>
                                            <tr>
                                                <td width='12%'>
                                                    <strong>URL</strong>
                                                </td>
                                                <td>
                                                    <input title='Click and copied !' type='text' class='form-control' readonly
                                                           onClick="this.setSelectionRange(0, this.value.length); document.execCommand('copy');"
                                                           value="/{{$api->permalink}}"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>METHOD</strong></td>
                                                <td>{{strtoupper($api->method_type)}}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>PARAMETER</strong></td>
                                                <td>
                                                    <table class='table table-bordered table-hover'>
                                                        <thead>
                                                        <tr class='active'>
                                                            <th width="3%">No</th>
                                                            <th width="5%">Type</th>
                                                            <th>Parameter Names</th>
                                                            <th>Description / Validate / Rule</th>
                                                            <th>Mandatory</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php $i = 0;?>
                                                        @foreach($parameters as $param)
                                                            @if($param['used'])
                                                                <?php
                                                                $param_exception = ['in', 'not_in', 'digits_between'];
                                                                if ($param['config'] && substr($param['config'], 0, 1) != '*' && !in_array($param['type'], $param_exception)) continue;?>
                                                                <tr>
                                                                    <td>{{++$i}}</td>
                                                                    <td width="5%"><em>{{$param['type']}}</em></td>
                                                                    <td>{{$param['name']}}</td>
                                                                    <td>

                                                                        @if(substr($param['config'],0,1) == '*')
                                                                            <span class='text-info'>{{substr($param['config'],1)}}</span>
                                                                        @else
                                                                            {{$param['config']}}
                                                                        @endif

                                                                    </td>
                                                                    <td>{!! ($param['required'])?"<span class='label label-primary'>REQUIRED</span>":"<span class='label label-default'>OPTIONAL</span>"!!}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                        @if($i == 0)
                                                            <tr>
                                                                <td colspan='5' class="text-center"><i class='fa fa-search'></i> There is no parameter</td>
                                                            </tr>
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>RESPONSE</strong></td>
                                                <td>
                                                    <table class='table table-bordered table-hover'>
                                                        <thead>
                                                        <tr class='active'>
                                                            <th width="3%">No</th>
                                                            <th width="5%">Type</th>
                                                            <th>Response Names</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php $i = 1;?>
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td><em>integer</em></td>
                                                            <td>api_status</td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td><em>string</em></td>
                                                            <td>api_message</td>
                                                        </tr>

                                                        @if($api->aksi == 'list')
                                                            <tr class='active'>
                                                                <td>#</td>
                                                                <td>Array</td>
                                                                <td><strong>data</strong></td>
                                                            </tr>
                                                        @endif

                                                        @if($api->aksi == 'list' || $api->aksi == 'detail')
                                                            @foreach($responses as $resp)
                                                                @if($resp['used'])
                                                                    <tr>
                                                                        <td>{{$i++}}</td>
                                                                        <td width="5%"><em>{{$resp['type']}}</em></td>
                                                                        <td>{{ ($api->aksi=='list')?'-> ':'' }} {{$resp['name']}}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                        @if($api->aksi == 'save_add')
                                                            <tr>
                                                                <td width="5%">{{$i++}}</td>
                                                                <td><em>integer</em></td>
                                                                <td>id</td>
                                                            </tr>
                                                        @endif
                                                        </tbody>
                                                    </table>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>DESCRIPTION</strong></td>
                                                <td><em>{!! $api->keterangan !!}</em></td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection
