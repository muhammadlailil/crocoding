<div class="buttonheadertable">
    <div class="d-inline-flex mb-4 mb-sm-0 mx-auto btn-table-nav" role="group" aria-label="Page actions">
        @if($button_filter)
            <a href="javascript:void(0)" class="btn btn-white" id='btn_advanced_filter'>
                <i class="fa fa-filter"></i> Filter
            </a>
        @endif

        @if(\crocodicstudio\crocoding\helpers\Crocoding::getCurrentMethod() == 'getIndex')
            @if($button_add && \crocodicstudio\crocoding\helpers\Crocoding::isCreate())
                <a href="{{ \crocodicstudio\crocoding\helpers\Crocoding::mainpath('add').'?return_url='.urlencode(requestFullUrl()).'&parent_id='.g('parent_id').'&parent_field='.$parent_field}}"
                   id='btn_add_new_data' class="btn btn-white active" title="Add Data">
                    <i class="fa fa-plus-circle"></i> Add Data
                </a>
            @endif

            @if($button_export)
                <a href="javascript:void(0)" id='btn_export_data' data-url-parameter='' title='Export Data'
                   class="btn btn-white btn-export-data">
                    <i class="fa fa-upload"></i> Export Data
                </a>
            @endif

            @if($button_import)
                <a href="{{ \crocodicstudio\crocoding\helpers\Crocoding::mainpath('import-data') }}" id='btn_import_data'
                   data-url-parameter='' title='Import Data'
                   class="btn btn-white active btn-import-data">
                    <i class="fa fa-download"></i> Import Data
                </a>
            @endif

        <!--ADD ACTIon-->
            @if(count($index_button))

                @foreach($index_button as $ib)
                    <a href='{{$ib["url"]}}' id='{{str_slug($ib["label"])}}' class='btn {{($ib['color'])?'btn-'.$ib['color']:'btn-primary'}} btn-sm'
                       @if($ib['onClick']) onClick='return {{$ib["onClick"]}}' @endif
                       @if($ib['onMouseOver']) onMouseOver='return {{$ib["onMouseOver"]}}' @endif
                       @if($ib['onMouseOut']) onMouseOut='return {{$ib["onMouseOut"]}}' @endif
                       @if($ib['onKeyDown']) onKeyDown='return {{$ib["onKeyDown"]}}' @endif
                       @if($ib['onLoad']) onLoad='return {{$ib["onLoad"]}}' @endif
                    >
                        <i class='{{$ib["icon"]}}'></i> {{$ib["label"]}}
                    </a>
                @endforeach
            @endif
        <!-- END BUTTON -->


        @endif

    </div>
</div>
