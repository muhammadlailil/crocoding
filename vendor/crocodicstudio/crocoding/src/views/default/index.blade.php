    @extends('crocoding::admin_template')

    @section('content')

        @if(!is_null($pre_index_html) && !empty($pre_index_html))
            {!! $pre_index_html !!}
        @endif


        @if(g('return_url'))
            <p><a href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left'></i>
                    &nbsp;Back To List Data {{urldecode(g('label'))}}</a></p>
        @endif


        <div class="col-sm-12">

            @if(isset($parent_table))
                <div class="card card-default">
                    <div class="card-body table-responsive no-padding">
                        <table class='table table-bordered'>
                            <tbody>
                            <tr class='active'>
                                <td colspan="2"><strong><i class='fa fa-bars'></i> {{ ucwords(urldecode(g('label'))) }}</strong></td>
                            </tr>
                            @foreach(explode(',',urldecode(g('parent_columns'))) as $c)
                                <tr>
                                    <td width="25%"><strong>
                                            @if(urldecode(g('parent_columns_alias')))
                                                {{explode(',',urldecode(g('parent_columns_alias')))[$loop->index]}}
                                            @else
                                                {{  ucwords(str_replace('_',' ',$c)) }}
                                            @endif
                                        </strong></td>
                                    <td> {{ $parent_table->$c }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="card card-small mb-4">
                <div class="card-header border-bottom">
                    @if($button_bulk_action && ( ($button_delete && \crocodicstudio\crocoding\helpers\Crocoding::isDelete()) || $button_selected) )
                        <div class="btn-group selected-action">
                            <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                Bulk Action
                            </button>
                            <div class="dropdown-menu">
                                @if($button_delete && \crocodicstudio\crocoding\helpers\Crocoding::isDelete())
                                    <a data-name='delete' class="dropdown-item" href="javascript:;" id="deleteSelctedButton">Delete Selected</a>
                                @endif
                                @if($button_selected)
                                    @foreach($button_selected as $button)
                                        <a href="javascript:void(0)" data-name='{{$button["name"]}}' title='{{$button["label"]}}'>
                                            {{$button['label']}}
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="pull-right">
                        <div class="box-tools pull-right" style="position: relative;margin-bottom: -15px;margin-right: -10px">
                            <form method="GET" style="display:inline-block;width: 260px;margin-top: -1px;float: left;margin-right: 10px;"
                                  action="{{requestUrl()}}">
                                <div class="input-group mb-3">
                                    {!! \crocodicstudio\crocoding\helpers\Crocoding::getUrlParameters(['q']) !!}
                                    <input type="text" class="form-control form-search-table" value="{{ g('q') }}" placeholder="Cari ..."
                                           aria-label="Recipient's username" aria-describedby="basic-addon2" style="padding: 4px 10px" name="q">
                                    <div class="input-group-append">

                                        @if(g('q'))
                                            <?php
                                            $parameters = requestAll();
                                            unset($parameters['q']);
                                            $build_query = urldecode(http_build_query($parameters));
                                            $build_query = ($build_query) ? "?" . $build_query : "";
                                            $build_query = (requestAll()) ? $build_query : "";
                                            ?>
                                            <button type="button"
                                                    onclick='location.href="{{ \crocodicstudio\crocoding\helpers\Crocoding::mainpath().$build_query}}"'
                                                    class="btn btn-warning" style="padding: 6px 12px;font-size: 16px;color: #fff">
                                                <i class="fa fa-ban"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-white" type="submit" style="padding: 6px 12px;font-size: 16px;">
                                            <i class="material-icons">search</i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <form method="get" action="{{requestUrl()}}" id="form-limit-paging" style="display:inline-block" action="">
                                {!! \crocodicstudio\crocoding\helpers\Crocoding::getUrlParameters(['limit']) !!}
                                <select class="custom-select custom-select-sm" onchange="$('#form-limit-paging').submit()" name='limit'>
                                    <option {{($limit==5)?'selected':''}} value='5'>5</option>
                                    <option {{($limit==10)?'selected':''}} value='10'>10</option>
                                    <option {{($limit==20)?'selected':''}} value='20'>20</option>
                                    <option {{($limit==25)?'selected':''}} value='25'>25</option>
                                    <option {{($limit==50)?'selected':''}} value='50'>50</option>
                                    <option {{($limit==100)?'selected':''}} value='100'>100</option>
                                    <option {{($limit==200)?'selected':''}} value='200'>200</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <br style="clear:both"/>
                </div>

                <div class="card-body p-0 text-center table-responsive">
                    @include("crocoding::default.table")
                </div>

                @if(!is_null($post_index_html) && !empty($post_index_html))
                    {!! $post_index_html !!}
                @endif
            </div>
        </div>

    @endsection
