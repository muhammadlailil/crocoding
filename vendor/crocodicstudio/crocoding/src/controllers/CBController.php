<?php
namespace crocodicstudio\crocoding\controllers;

error_reporting(E_ALL ^ E_NOTICE);

use crocodicstudio\crocoding\excel\ExportExcel;
use crocodicstudio\crocoding\excel\ImportCollection;
use crocodicstudio\crocoding\helpers\Crocoding;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Maatwebsite\Excel\Facades\Excel;

class CBController extends Controller
{
    public $data_inputan;

    public $columns_table;

    public $module_name = 'x';

    public $table;

    public $title_field;

    public $primary_key = 'id';

    public $arr = [];

    public $col = [];

    public $form = [];

    public $data = [];

    public $addaction = [];

    public $orderby = null;

    public $password_candidate = null;

    public $date_candidate = null;

    public $limit = 20;

    public $global_privilege = false;

    public $show_numbering = false;

    public $alert = [];

    public $index_button = [];

    public $button_filter = false;

    public $button_export = true;

    public $button_import = true;

    public $button_show = true;

    public $button_addmore = true;

    public $button_table_action = true;

    public $button_bulk_action = true;

    public $button_add = true;

    public $button_delete = true;

    public $button_cancel = true;

    public $button_save = true;

    public $button_edit = true;

    public $button_detail = true;

    public $button_action_style = 'button_icon';

    public $button_action_width = null;

    public $index_statistic = [];

    public $index_additional_view = [];

    public $pre_index_html = null;

    public $post_index_html = null;

    public $load_js = [];

    public $load_css = [];

    public $script_js = null;

    public $style_css = null;

    public $sub_module = [];

    public $show_addaction = true;

    public $table_row_color = [];

    public $button_selected = [];

    public $return_url = null;

    public $parent_field = null;

    public $parent_id = null;

    public $hide_form = [];

    public $index_return = false; //for export

    public $sidebar_mode = 'normal';

    public function cbLoader()
    {
        $this->cbInit();

        $this->checkHideForm();

        $this->primary_key = Crocoding::pk($this->table);
        $this->columns_table = $this->col;
        $this->data_inputan = $this->form;
        $this->data['pk'] = $this->primary_key;
        $this->data['forms'] = $this->data_inputan;
        $this->data['hide_form'] = $this->hide_form;
        $this->data['addaction'] = ($this->show_addaction) ? $this->addaction : null;
        $this->data['table'] = $this->table;
        $this->data['title_field'] = $this->title_field;
        $this->data['appname'] = Crocoding::getSetting('appname');
        $this->data['alerts'] = $this->alert;
        $this->data['index_button'] = $this->index_button;
        $this->data['show_numbering'] = $this->show_numbering;
        $this->data['button_detail'] = $this->button_detail;
        $this->data['button_edit'] = $this->button_edit;
        $this->data['button_show'] = $this->button_show;
        $this->data['button_add'] = $this->button_add;
        $this->data['button_delete'] = $this->button_delete;
        $this->data['button_filter'] = $this->button_filter;
        $this->data['button_export'] = $this->button_export;
        $this->data['button_addmore'] = $this->button_addmore;
        $this->data['button_cancel'] = $this->button_cancel;
        $this->data['button_save'] = $this->button_save;
        $this->data['button_table_action'] = $this->button_table_action;
        $this->data['button_bulk_action'] = $this->button_bulk_action;
        $this->data['button_import'] = $this->button_import;
        $this->data['button_action_width'] = $this->button_action_width;
        $this->data['button_selected'] = $this->button_selected;
        $this->data['index_statistic'] = $this->index_statistic;
        $this->data['index_additional_view'] = $this->index_additional_view;
        $this->data['table_row_color'] = $this->table_row_color;
        $this->data['pre_index_html'] = $this->pre_index_html;
        $this->data['post_index_html'] = $this->post_index_html;
        $this->data['load_js'] = $this->load_js;
        $this->data['load_css'] = $this->load_css;
        $this->data['script_js'] = $this->script_js;
        $this->data['style_css'] = $this->style_css;
        $this->data['sub_module'] = $this->sub_module;
        $this->data['parent_field'] = (g('parent_field')) ?: $this->parent_field;
        $this->data['parent_id'] = (g('parent_id')) ?: $this->parent_id;
        $this->data['module_name '] = $this->module_name;

        if ($this->sidebar_mode == 'mini') {
            $this->data['sidebar_mode'] = 'sidebar-mini';
        } elseif ($this->sidebar_mode == 'collapse') {
            $this->data['sidebar_mode'] = 'sidebar-collapse';
        } elseif ($this->sidebar_mode == 'collapse-mini') {
            $this->data['sidebar_mode'] = 'sidebar-collapse sidebar-mini';
        } else {
            $this->data['sidebar_mode'] = '';
        }

        if (Crocoding::getCurrentMethod() == 'getProfile') {
            session()->put('current_row_id', Crocoding::myId());
            $this->data['return_url'] = Request::fullUrl();
        }

        view()->share($this->data);
    }

    public function cbView($template, $data)
    {
        $this->cbLoader();
        echo view($template, $data);
    }

    private function checkHideForm()
    {
        if (count($this->hide_form)) {
            foreach ($this->form as $i => $f) {
                if (in_array($f['name'], $this->hide_form)) {
                    unset($this->form[$i]);
                }
            }
        }
    }

    public function getIndex()
    {
        $this->cbLoader();

        $module = Crocoding::getCurrentModule();

        if (! Crocoding::isView() && $this->global_privilege == false) {
            Crocoding::insertLog('Try view the data '.$module->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        if (g('parent_table')) {
            $parentTablePK = Crocoding::pk(g('parent_table'));
            $data['parent_table'] = DB::table(g('parent_table'))->where($parentTablePK, g('parent_id'))->first();
            if (g('foreign_key')) {
                $data['parent_field'] = g('foreign_key');
            } else {
                $data['parent_field'] = Crocoding::getTableForeignKey(g('parent_table'), $this->table);
            }

            if ($this->parent_field) {
                foreach ($this->columns_table as $i => $col) {
                    if ($col['name'] == $this->parent_field) {
                        unset($this->columns_table[$i]);
                    }
                }
            }
        }

        $data['table'] = $this->table;
        $data['table_pk'] = Crocoding::pk($this->table);
        $data['page_title'] = $module->name;
        $data['page_description'] = 'Data List';
        $data['date_candidate'] = $this->date_candidate;
        $data['limit'] = $limit = (g('limit')) ? g('limit') : $this->limit;

        $tablePK = $data['table_pk'];
        $table_columns = Crocoding::getTableColumns($this->table);
        $result = DB::table($this->table)->select(DB::raw($this->table.".".$this->primary_key));

        if (g('parent_id')) {
            $table_parent = $this->table;
            $table_parent = Crocoding::parseSqlTable($table_parent)['table'];
            $result->where($table_parent.'.'.g('foreign_key'), g('parent_id'));
        }

        $this->hook_query_index($result);

        if (in_array('deleted_at', $table_columns)) {
            $result->where($this->table.'.deleted_at', null);
        }

        $alias = [];
        $join_alias_count = 0;
        $join_table_temp = [];
        $table = $this->table;
        $columns_table = $this->columns_table;
        foreach ($columns_table as $index => $coltab) {


            $join = @$coltab['join'];
            $join_where = @$coltab['join_where'];
            $join_id = @$coltab['join_id'];
            $field = @$coltab['name'];
            $join_table_temp[] = $table;

            if (! $field) {
                die('Please make sure there is key `name` in each row of col');
            }

            if (strpos($field, ' as ') !== false) {
                $field = substr($field, strpos($field, ' as ') + 4);
                $field_with = (array_key_exists('join', $coltab)) ? str_replace(",", ".", $coltab['join']) : $field;
                $result->addselect(DB::raw($coltab['name']));
                $columns_table[$index]['type_data'] = 'varchar';
                $columns_table[$index]['field'] = $field;
                $columns_table[$index]['field_raw'] = $field;
                $columns_table[$index]['field_with'] = $field_with;
                $columns_table[$index]['is_subquery'] = true;
                continue;
            }

            if (strpos($field, '.') !== false) {
                $result->addselect($field);
            } else {
                $result->addselect($table.'.'.$field);
            }

            $field_array = explode('.', $field);

            if (isset($field_array[1])) {
                $field = $field_array[1];
                $table = $field_array[0];
            } else {
                $table = $this->table;
            }

            if ($join) {

                $join_exp = explode(',', $join);

                $join_table = $join_exp[0];
                $joinTablePK = Crocoding::pk($join_table);
                $join_column = $join_exp[1];
                $join_alias = str_replace(".", "_", $join_table);

                if (in_array($join_table, $join_table_temp)) {
                    $join_alias_count += 1;
                    $join_alias = $join_table.$join_alias_count;
                }
                $join_table_temp[] = $join_table;

                $result->leftjoin($join_table.' as '.$join_alias, $join_alias.(($join_id) ? '.'.$join_id : '.'.$joinTablePK), '=', DB::raw($table.'.'.$field.(($join_where) ? ' AND '.$join_where.' ' : '')));
                $result->addselect($join_alias.'.'.$join_column.' as '.$join_alias.'_'.$join_column);

                $join_table_columns = Crocoding::getTableColumns($join_table);
                if ($join_table_columns) {
                    foreach ($join_table_columns as $jtc) {
                        $result->addselect($join_alias.'.'.$jtc.' as '.$join_alias.'_'.$jtc);
                    }
                }

                $alias[] = $join_alias;
                $columns_table[$index]['type_data'] = Crocoding::getFieldType($join_table, $join_column);
                $columns_table[$index]['field'] = $join_alias.'_'.$join_column;
                $columns_table[$index]['field_with'] = $join_alias.'.'.$join_column;
                $columns_table[$index]['field_raw'] = $join_column;


                @$join_table1 = $join_exp[2];
                @$joinTable1PK = Crocoding::pk($join_table1);
                @$join_column1 = $join_exp[3];
                @$join_alias1 = $join_table1;

                if ($join_table1 && $join_column1) {

                    if (in_array($join_table1, $join_table_temp)) {
                        $join_alias_count += 1;
                        $join_alias1 = $join_table1.$join_alias_count;
                    }

                    $join_table_temp[] = $join_table1;

                    $result->leftjoin($join_table1.' as '.$join_alias1, $join_alias1.'.'.$joinTable1PK, '=', $join_alias.'.'.$join_column);
                    $result->addselect($join_alias1.'.'.$join_column1.' as '.$join_column1.'_'.$join_alias1);
                    $alias[] = $join_alias1;
                    $columns_table[$index]['type_data'] = Crocoding::getFieldType($join_table1, $join_column1);
                    $columns_table[$index]['field'] = $join_column1.'_'.$join_alias1;
                    $columns_table[$index]['field_with'] = $join_alias1.'.'.$join_column1;
                    $columns_table[$index]['field_raw'] = $join_column1;
                }
            } else {

                $result->addselect($table.'.'.$field);
                $columns_table[$index]['type_data'] = Crocoding::getFieldType($table, $field);
                $columns_table[$index]['field'] = $field;
                $columns_table[$index]['field_raw'] = $field;
                $columns_table[$index]['field_with'] = $table.'.'.$field;
            }
            if(!isset($columns_table[$index]['visible'])){
                $columns_table[$index]['visible'] = true;
            }

            if(!isset($columns_table[$index]['str_limit'])){
                $columns_table[$index]['str_limit'] = null;
            }

            if(!isset($columns_table[$index]['nl2br'])){
                $columns_table[$index]['nl2br'] = null;
            }
            if(!isset($columns_table[$index]['callback_php'])){
                $columns_table[$index]['callback_php'] = null;
            }
        }

        if (g('q')) {
            $result->where(function ($w) use ($columns_table) {
                foreach ($columns_table as $col) {
                    if (! $col['field_with']) {
                        continue;
                    }
                    if (isset($col['is_subquery'])) {
                        continue;
                    }
                    $w->orWhere($col['field_with'], "like", "%".g("q")."%");
                }
            });
        }

        if (g('where')) {
            foreach (g('where') as $k => $v) {
                $result->where($table.'.'.$k, $v);
            }
        }

        $filter_is_orderby = false;
        if (g('filter_column')) {

            $filter_column = g('filter_column');
            $result->where(function ($w) use ($filter_column) {
                foreach ($filter_column as $key => $fc) {

                    $value = @$fc['value'];
                    $type = @$fc['type'];

                    if ($type == 'empty') {
                        $w->whereNull($key)->orWhere($key, '');
                        continue;
                    }

                    if ($value == '' || $type == '') {
                        continue;
                    }

                    if ($type == 'between') {
                        continue;
                    }

                    switch ($type) {
                        default:
                            if ($key && $type && $value) {
                                $w->where($key, $type, $value);
                            }
                            break;
                        case 'like':
                        case 'not like':
                            $value = '%'.$value.'%';
                            if ($key && $type && $value) {
                                $w->where($key, $type, $value);
                            }
                            break;
                        case 'in':
                        case 'not in':
                            if ($value) {
                                $value = explode(',', $value);
                                if ($key && $value) {
                                    $w->whereIn($key, $value);
                                }
                            }
                            break;
                    }
                }
            });

            foreach ($filter_column as $key => $fc) {
                $value = @$fc['value'];
                $type = @$fc['type'];
                $sorting = @$fc['sorting'];

                if ($sorting != '') {
                    if ($key) {
                        $result->orderby($key, $sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type == 'between') {
                    if ($key && $value) {
                        $result->whereBetween($key, $value);
                    }
                } else {
                    continue;
                }
            }
        }

        if ($filter_is_orderby == true) {
            $data['result'] = $result->paginate($limit);
        } else {
            if ($this->orderby) {
                if (is_array($this->orderby)) {
                    foreach ($this->orderby as $k => $v) {
                        if (strpos($k, '.') !== false) {
                            $orderby_table = explode(".", $k)[0];
                            $k = explode(".", $k)[1];
                        } else {
                            $orderby_table = $this->table;
                        }
                        $result->orderby($orderby_table.'.'.$k, $v);
                    }
                } else {
                    $this->orderby = explode(";", $this->orderby);
                    foreach ($this->orderby as $o) {
                        $o = explode(",", $o);
                        $k = $o[0];
                        $v = $o[1];
                        if (strpos($k, '.') !== false) {
                            $orderby_table = explode(".", $k)[0];
                        } else {
                            $orderby_table = $this->table;
                        }
                        $result->orderby($orderby_table.'.'.$k, $v);
                    }
                }
                $data['result'] = $result->paginate($limit);
            } else {
                $data['result'] = $result->orderby($this->table.'.'.$this->primary_key, 'desc')->paginate($limit);
            }
        }

        $data['columns'] = $columns_table;

        if ($this->index_return) {
            return $data;
        }

        //LISTING INDEX HTML
        $addaction = $this->data['addaction'];

        if ($this->sub_module) {
            foreach ($this->sub_module as $s) {
                $table_parent = Crocoding::parseSqlTable($this->table)['table'];
                $addaction[] = [
                    'label' => $s['label'],
                    'icon' => $s['button_icon'],
                    'url' => Crocoding::adminPath($s['path']).'?parent_table='.$table_parent.'&parent_columns='.$s['parent_columns'].'&parent_columns_alias='.$s['parent_columns_alias'].'&parent_id=['.(! isset($s['custom_parent_id']) ? "id" : $s['custom_parent_id']).']&return_url='.urlencode(Request::fullUrl()).'&foreign_key='.$s['foreign_key'].'&label='.urlencode($s['label']),
                    'color' => $s['button_color'],
                    'showIf' => $s['showIf'],
                ];
            }
        }

        $mainpath = Crocoding::mainpath();
//        $orig_mainpath = $this->data['mainpath'];
        $title_field = $this->title_field;
        $html_contents = [];
        $page = (g('page')) ? g('page') : 1;
        $number = ($page - 1) * $limit + 1;
        foreach ($data['result'] as $row) {
            $html_content = [];

            if ($this->button_bulk_action && (($this->button_delete && Crocoding::isDelete()) || $this->button_selected)) {
                $html = '<div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input checkbox" name=\'checkbox[]\' id="checkBox'.$row->{$tablePK}.'" value="'.$row->{$tablePK}.'">
                                <label class="custom-control-label" for="checkBox'.$row->{$tablePK}.'"></label>
                            </div>';
                $html_content[] = $html;
            }

            if ($this->show_numbering) {
                $html_content[] = $number.'. ';
                $number++;
            }

            foreach ($columns_table as $col) {
                if ($col['visible'] === false) {
                    continue;
                }

                $value = @$row->{$col['field']};
                $title = @$row->{$this->title_field};
                $label = $col['label'];

                if (isset($col['image'])) {
                    if ($value == '') {
                        $value = '<a class="image-popup" href="'.asset('vendor/crocoding/assets/img/avatar.jpg').'">
                                <img  src="'.asset('vendor/crocoding/assets/img/avatar.jpg').'" alt="" class="table-foto">
                            </a>';
                    } else {
                        $pic = (strpos($value, 'http://') !== false) ? $value : asset($value);
                        $value = '<a class="image-popup" href="'.$pic.'">
                                <img  src="'.$pic.'" alt="" class="table-foto">
                            </a>';
                    }
                }

                if (@$col['download']) {
                    $url = (strpos($value, 'http://') !== false) ? $value : asset($value).'?download=1';
                    if ($value) {
                        $value = "<a class='btn btn-xs btn-primary' href='$url' target='_blank' title='Download File'><i class='fa fa-download'></i> Download</a>";
                    } else {
                        $value = " - ";
                    }
                }

                if ($col['str_limit']) {
                    $value = trim(strip_tags($value));
                    $value = str_limit($value, $col['str_limit']);
                }

                if ($col['nl2br']) {
                    $value = nl2br($value);
                }

                if ($col['callback_php']) {
                    foreach ($row as $k => $v) {
                        $col['callback_php'] = str_replace("[".$k."]", $v, $col['callback_php']);
                    }
                    @eval("\$value = ".$col['callback_php'].";");
                }

                //New method for callback
                if (isset($col['callback'])) {
                    $value = call_user_func($col['callback'], $row);
                }

                $datavalue = @unserialize($value);
                if ($datavalue !== false) {
                    if ($datavalue) {
                        $prevalue = [];
                        foreach ($datavalue as $d) {
                            if ($d['label']) {
                                $prevalue[] = $d['label'];
                            }
                        }
                        if (count($prevalue)) {
                            $value = implode(", ", $prevalue);
                        }
                    }
                }

                $html_content[] = $value;
            } //end foreach columns_table

            if ($this->button_table_action):

                $button_action_style = $this->button_action_style;
                $html_content[] = "<div class='button_action' style='text-align:right'>".view('crocoding::components.action', compact('addaction', 'row', 'button_action_style', 'parent_field'))->render()."</div>";

            endif;//button_table_action

            foreach ($html_content as $i => $v) {
                $this->hook_row_index($i, $v);
                $html_content[$i] = $v;
            }

            $html_contents[] = $html_content;
        } //end foreach data[result]

        $html_contents = ['html' => $html_contents, 'data' => $data['result']];

        $data['html_contents'] = $html_contents;
        $data['module_name'] = $this->module_name;
        $setting = DB::table("cms_settings")->select('name','content')->get();
        $settings =  new StdClass();
        foreach ($setting as $s){
            $settings->{$s->name} = $s->content;
        }
        $data['setting'] = $settings;

        return view("crocoding::default.index", $data);
    }

    public function getExportData()
    {

        return redirect(Crocoding::mainpath());
    }

    public function postExportData()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(180);

        $this->limit = g('limit');
        $this->index_return = true;
        $filetype = g('fileformat');
        $filename = g('filename');
        $papersize = g('page_size');
        $paperorientation = g('page_orientation');
        $response = $this->getIndex();

        if (g('default_paper_size')) {
            DB::table('cms_settings')->where('name', 'default_paper_size')->update(['content' => $papersize]);
        }

        switch ($filetype) {
            case "pdf":
                $view = view('crocoding::export', $response)->render();
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML($view);
                $pdf->setPaper($papersize, $paperorientation);

                return $pdf->stream($filename.'.pdf');
                break;
            case 'xls':
                $export  = new ExportExcel();
                $export->setView('crocoding::export');
                $export->setData($response);
                return Excel::download($export, $filename.'.xls');
                break;
            case 'csv':
                $export  = new ExportExcel();
                $export->setView('crocoding::export');
                $export->setData($response);
                return Excel::download($export, $filename.'.csv');
                break;

        }
    }

    public function postDataQuery()
    {
        $query = g('query');
        $query = DB::select(DB::raw($query));

        return response()->json($query);
    }

    public function getDataTable()
    {
        $table = g('table');
        $label = g('label');
        $datatableWhere = urldecode(g('datatable_where'));
        $foreign_key_name = g('fk_name');
        $foreign_key_value = g('fk_value');
        if ($table && $label && $foreign_key_name && $foreign_key_value) {
            $query = DB::table($table);
            if ($datatableWhere) {
                $query->whereRaw($datatableWhere);
            }
            $query->select('id as select_value', $label.' as select_label');
            $query->where($foreign_key_name, $foreign_key_value);
            $query->orderby($label, 'asc');

            return response()->json($query->get());
        } else {
            return response()->json([]);
        }
    }

    public function getModalData()
    {
        $table = g('table');
        $where = g('where');
        $where = urldecode($where);
        $columns = g('columns');
        $columns = explode(",", $columns);

        $table = Crocoding::parseSqlTable($table)['table'];
        $tablePK = Crocoding::pk($table);
        $result = DB::table($table);

        if (g('q')) {
            $result->where(function ($where) use ($columns) {
                foreach ($columns as $c => $col) {
                    if ($c == 0) {
                        $where->where($col, 'like', '%'.g('q').'%');
                    } else {
                        $where->orWhere($col, 'like', '%'.g('q').'%');
                    }
                }
            });
        }

        if ($where) {
            $result->whereraw($where);
        }

        $result->orderby($tablePK, 'desc');

        $data['result'] = $result->paginate(6);
        $data['columns'] = $columns;

        return view('crocoding::default.type_components.datamodal.browser', $data);
    }

    public function getUpdateSingle()
    {
        $table = g('table');
        $column = g('column');
        $value = g('value');
        $id = g('id');
        $tablePK = Crocoding::pk($table);
        DB::table($table)->where($tablePK, $id)->update([$column => $value]);

        return redirect(back())->with(['message_type' => 'success', 'message' => 'Delete the data success !']);
    }

    public function getFindData()
    {
        $q = g('q');
        $id = g('id');
        $limit = g('limit') ?: 10;
        $format = g('format');

        $table1 = (g('table1')) ?: $this->table;
        $table1PK = Crocoding::pk($table1);
        $column1 = (g('column1')) ?: $this->title_field;

        @$table2 = g('table2');
        @$column2 = g('column2');

        @$table3 = g('table3');
        @$column3 = g('column3');

        $where = g('where');

        $fk = g('fk');
        $fk_value = g('fk_value');

        if ($q || $id || $table1) {
            $rows = DB::table($table1);
            $rows->select($table1.'.*');
            $rows->take($limit);

            if (Crocoding::isColumnExists($table1, 'deleted_at')) {
                $rows->where($table1.'.deleted_at', null);
            }

            if ($fk && $fk_value) {
                $rows->where($table1.'.'.$fk, $fk_value);
            }

            if ($table1 && $column1) {

                $orderby_table = $table1;
                $orderby_column = $column1;
            }

            if ($table2 && $column2) {
                $table2PK = Crocoding::pk($table2);
                $rows->join($table2, $table2.'.'.$table2PK, '=', $table1.'.'.$column1);
                $columns = Crocoding::getTableColumns($table2);
                foreach ($columns as $col) {
                    $rows->addselect($table2.".".$col." as ".$table2."_".$col);
                }
                $orderby_table = $table2;
                $orderby_column = $column2;
            }

            if ($table3 && $column3) {
                $table3PK = Crocoding::pk($table3);
                $rows->join($table3, $table3.'.'.$table3PK, '=', $table2.'.'.$column2);
                $columns = Crocoding::getTableColumns($table3);
                foreach ($columns as $col) {
                    $rows->addselect($table3.".".$col." as ".$table3."_".$col);
                }
                $orderby_table = $table3;
                $orderby_column = $column3;
            }

            if ($id) {
                $rows->where($table1.".".$table1PK, $id);
            }

            if ($where) {
                $rows->whereraw($where);
            }

            if ($format) {
                $format = str_replace('&#039;', "'", $format);
                $rows->addselect(DB::raw("CONCAT($format) as text"));
                if ($q) {
                    $rows->whereraw("CONCAT($format) like '%".$q."%'");
                }
            } else {
                $rows->addselect($orderby_table.'.'.$orderby_column.' as text');
                if ($q) {
                    $rows->where($orderby_table.'.'.$orderby_column, 'like', '%'.$q.'%');
                }
                $rows->orderBy($orderby_table.'.'.$orderby_column, 'asc');
            }

            $result = [];
            $result['items'] = $rows->get();
        } else {
            $result = [];
            $result['items'] = [];
        }

        return response()->json($result);
    }

    public function validation($id = null)
    {

        $request_all = Request::all();
        $array_input = [];
        foreach ($this->data_inputan as $di) {
            $ai = [];
            $name = $di['name'];

            if (! isset($request_all[$name])) {
                continue;
            }

            if ($di['type'] != 'upload') {
                if (@$di['required']) {
                    $ai[] = 'required';
                }
            }

            if ($di['type'] == 'upload') {
                if ($id) {
                    $row = DB::table($this->table)->where($this->primary_key, $id)->first();
                    if ($row->{$di['name']} == '') {
                        $ai[] = 'required';
                    }
                }
            }

            if (@$di['min']) {
                $ai[] = 'min:'.$di['min'];
            }
            if (@$di['max']) {
                $ai[] = 'max:'.$di['max'];
            }
            if (@$di['image']) {
                $ai[] = 'image';
            }
            if (@$di['mimes']) {
                $ai[] = 'mimes:'.$di['mimes'];
            }
            $name = $di['name'];
            if (! $name) {
                continue;
            }

            if ($di['type'] == 'money') {
                $request_all[$name] = preg_replace('/[^\d-]+/', '', $request_all[$name]);
            }

            if ($di['type'] == 'child') {
                $slug_name = str_slug($di['label'], '');
                foreach ($di['columns'] as $child_col) {
                    if (isset($child_col['validation'])) {
                        //https://laracasts.com/discuss/channels/general-discussion/array-validation-is-not-working/
                        if (strpos($child_col['validation'], 'required') !== false) {
                            $array_input[$slug_name.'-'.$child_col['name']] = 'required';

                            str_replace('required', '', $child_col['validation']);
                        }

                        $array_input[$slug_name.'-'.$child_col['name'].'.*'] = $child_col['validation'];
                    }
                }
            }

            if (@$di['validation']) {

                $exp = explode('|', $di['validation']);
                if (count($exp)) {
                    foreach ($exp as &$validationItem) {
                        if (substr($validationItem, 0, 6) == 'unique') {
                            $parseUnique = explode(',', str_replace('unique:', '', $validationItem));
                            $uniqueTable = ($parseUnique[0]) ?: $this->table;
                            $uniqueColumn = ($parseUnique[1]) ?: $name;
                            $uniqueIgnoreId = ($parseUnique[2]) ?: (($id) ?: '');

                            //Make sure table name
                            $uniqueTable = Crocoding::parseSqlTable($uniqueTable)['table'];

                            //Rebuild unique rule
                            $uniqueRebuild = [];
                            $uniqueRebuild[] = $uniqueTable;
                            $uniqueRebuild[] = $uniqueColumn;
                            if ($uniqueIgnoreId) {
                                $uniqueRebuild[] = $uniqueIgnoreId;
                            } else {
                                $uniqueRebuild[] = 'NULL';
                            }

                            //Check whether deleted_at exists or not
                            if (Crocoding::isColumnExists($uniqueTable, 'deleted_at')) {
                                $uniqueRebuild[] = Crocoding::findPrimaryKey($uniqueTable);
                                $uniqueRebuild[] = 'deleted_at';
                                $uniqueRebuild[] = 'NULL';
                            }
                            $uniqueRebuild = array_filter($uniqueRebuild);
                            $validationItem = 'unique:'.implode(',', $uniqueRebuild);
                        }
                    }
                } else {
                    $exp = [];
                }

                $validation = implode('|', $exp);

                $array_input[$name] = $validation;
            } else {
                $array_input[$name] = implode('|', $ai);
            }
        }

        $validator = Validator::make($request_all, $array_input);

        if ($validator->fails()) {
            $message = $validator->messages();
            $message_all = $message->all();

            if (Request::ajax()) {
                $res = response()->json([
                    'message' => 'Please fill out the form correctly : '.implode(', ', $message_all),
                    'message_type' => 'warning',
                ])->send();
                exit;
            } else {
                $res = redirect(back())->with("errors", $message)->with([
                    'message' => 'Please fill out the form correctly : '.implode(', ', $message_all),
                    'message_type' => 'warning',
                ])->withInput();
                $res->send();
                exit;
            }
        }
    }

    public function input_assignment($id = null)
    {

        $hide_form = (g('hide_form')) ? unserialize(g('hide_form')) : [];

        foreach ($this->data_inputan as $ro) {
            $name = $ro['name'];

            if (! $name) {
                continue;
            }

            if ($ro['exception']) {
                continue;
            }

            if ($name == 'hide_form') {
                continue;
            }

            if (count($hide_form)) {
                if (in_array($name, $hide_form)) {
                    continue;
                }
            }

            if ($ro['type'] == 'checkbox' && $ro['relationship_table']) {
                continue;
            }

            if ($ro['type'] == 'select2' && $ro['relationship_table']) {
                continue;
            }

            $inputdata = g($name);

            if ($ro['type'] == 'money') {
                $inputdata = preg_replace('/[^\d-]+/', '', $inputdata);
            }

            if ($ro['type'] == 'child') {
                continue;
            }

            if ($name) {
                if ($inputdata != '') {
                    $this->arr[$name] = $inputdata;
                } else {
                    if (Crocoding::isColumnNULL($this->table, $name) && $ro['type'] != 'upload') {
                        continue;
                    } else {
                        $this->arr[$name] = "";
                    }
                }
            }

            $password_candidate = explode(',', config('crocoding.PASSWORD_FIELDS_CANDIDATE'));
            if (in_array($name, $password_candidate)) {
                if (! empty($this->arr[$name])) {
                    $this->arr[$name] = Hash::make($this->arr[$name]);
                } else {
                    unset($this->arr[$name]);
                }
            }

            if ($ro['type'] == 'checkbox') {

                if (is_array($inputdata)) {
                    if ($ro['datatable'] != '') {
                        $table_checkbox = explode(',', $ro['datatable'])[0];
                        $field_checkbox = explode(',', $ro['datatable'])[1];
                        $table_checkbox_pk = Crocoding::pk($table_checkbox);
                        $data_checkbox = DB::table($table_checkbox)->whereIn($table_checkbox_pk, $inputdata)->pluck($field_checkbox)->toArray();
                        $this->arr[$name] = implode(";", $data_checkbox);
                    } else {
                        $this->arr[$name] = implode(";", $inputdata);
                    }
                }
            }

            //multitext colomn
            if ($ro['type'] == 'multitext') {
                $name = $ro['name'];
                $multitext = "";

                for ($i = 0; $i <= count($this->arr[$name]) - 1; $i++) {
                    $multitext .= $this->arr[$name][$i]."|";
                }
                $multitext = substr($multitext, 0, strlen($multitext) - 1);
                $this->arr[$name] = $multitext;
            }

            if ($ro['type'] == 'googlemaps') {
                if ($ro['latitude'] && $ro['longitude']) {
                    $latitude_name = $ro['latitude'];
                    $longitude_name = $ro['longitude'];
                    $this->arr[$latitude_name] = g('input-latitude-'.$name);
                    $this->arr[$longitude_name] = g('input-longitude-'.$name);
                }
            }

            if ($ro['type'] == 'select' || $ro['type'] == 'select2') {
                if ($ro['datatable']) {
                    if ($inputdata == '') {
                        $this->arr[$name] = 0;
                    }
                }
            }

            if (@$ro['type'] == 'upload') {

                $this->arr[$name] = Crocoding::uploadFile($name, $ro['encrypt'], $ro['resize_width'], $ro['resize_height'], Crocoding::myId());

                if (! $this->arr[$name]) {
                    $this->arr[$name] = g('_'.$name);
                }
            }

            if (@$ro['type'] == 'filemanager') {
                $filename = str_replace('/'.config('lfm.prefix').'/'.config('lfm.files_folder_name').'/', '', $this->arr[$name]);
                $url = 'uploads/'.$filename;
                $this->arr[$name] = $url;
            }
        }
    }

    public function getAdd()
    {
        $this->cbLoader();
        if (! Crocoding::isCreate() && $this->global_privilege == false || $this->button_add == false) {
            Crocoding::insertLog('Try add data at '.Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $page_title = 'Add '. Crocoding::getCurrentModule()->name;
        $page_menu = currentRouteAction();
        $command = 'add';
        $data['page_title'] = $page_title;
        $data['page_menu'] = $page_menu;
        $data['command'] = $command;

        return view('crocoding::default.form', $data);
    }

    public function postAddSave()
    {
        $this->cbLoader();
        if (! Crocoding::isCreate() && $this->global_privilege == false) {
            Crocoding::insertLog('Try add the data '.g($this->title_field).' data at ').Crocoding::getCurrentModule()->name;
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $this->validation();
        $this->input_assignment();

        if (Schema::hasColumn($this->table, 'created_at')) {
            $this->arr['created_at'] = date('Y-m-d H:i:s');
        }

        $this->hook_before_add($this->arr);

        $this->arr[$this->primary_key] = $id = Crocoding::newId($this->table);
        DB::table($this->table)->insert($this->arr);

        //Looping Data Input Again After Insert
        foreach ($this->data_inputan as $ro) {
            $name = $ro['name'];
            if (! $name) {
                continue;
            }

            $inputdata = g($name);

            //Insert Data Checkbox if Type Datatable
            if ($ro['type'] == 'checkbox') {
                if ($ro['relationship_table']) {
                    $datatable = explode(",", $ro['datatable'])[0];
                    $foreignKey2 = Crocoding::getForeignKey($datatable, $ro['relationship_table']);
                    $foreignKey = Crocoding::getForeignKey($this->table, $ro['relationship_table']);
                    DB::table($ro['relationship_table'])->where($foreignKey, $id)->delete();

                    if ($inputdata) {
                        $relationship_table_pk = Crocoding::pk($ro['relationship_table']);
                        foreach ($inputdata as $input_id) {
                            DB::table($ro['relationship_table'])->insert([
                                $relationship_table_pk => Crocoding::newId($ro['relationship_table']),
                                $foreignKey => $id,
                                $foreignKey2 => $input_id,
                            ]);
                        }
                    }
                }
            }

            if ($ro['type'] == 'select2') {
                if ($ro['relationship_table']) {
                    $datatable = explode(",", $ro['datatable'])[0];
                    $foreignKey2 = Crocoding::getForeignKey($datatable, $ro['relationship_table']);
                    $foreignKey = Crocoding::getForeignKey($this->table, $ro['relationship_table']);
                    DB::table($ro['relationship_table'])->where($foreignKey, $id)->delete();

                    if ($inputdata) {
                        foreach ($inputdata as $input_id) {
                            $relationship_table_pk = Crocoding::pk($ro['relationship_table']);
                            DB::table($ro['relationship_table'])->insert([
                                $relationship_table_pk => Crocoding::newId($ro['relationship_table']),
                                $foreignKey => $id,
                                $foreignKey2 => $input_id,
                            ]);
                        }
                    }
                }
            }

            if ($ro['type'] == 'child') {
                $name = str_slug($ro['label'], '');
                $columns = $ro['columns'];
                $count_input_data = count(g($name.'-'.$columns[0]['name'])) - 1;
                $child_array = [];

                for ($i = 0; $i <= $count_input_data; $i++) {
                    $fk = $ro['foreign_key'];
                    $column_data = [];
                    $column_data[$fk] = $id;
                    foreach ($columns as $col) {
                        $colname = $col['name'];
                        $column_data[$colname] = g($name.'-'.$colname)[$i];
                    }
                    $child_array[] = $column_data;
                }

                $childtable = Crocoding::parseSqlTable($ro['table'])['table'];
                DB::table($childtable)->insert($child_array);
            }
        }

        $this->hook_after_add($this->arr[$this->primary_key]);

        $this->return_url = ($this->return_url) ? $this->return_url : g('return_url');

        //insert log
        Crocoding::insertLog('Add New Data '.$this->arr[$this->title_field].' at '.Crocoding::getCurrentModule()->name);

        if ($this->return_url) {
            if (g('submit') == 'Save & Add More') {
                return Crocoding::redirect(Request::server('HTTP_REFERER'), 'The data has been added !', 'success');
            } else {
                return Crocoding::redirect($this->return_url, 'The data has been added !', 'success');
            }
        } else {
            if (g('submit') == 'Save & Add More') {
                return Crocoding::redirect(Crocoding::mainpath('add'), 'The data has been added !', 'success');
            } else {
                return Crocoding::redirect(Crocoding::mainpath(), 'The data has been added !', 'success');
            }
        }
    }

    public function getEdit($id)
    {
        $this->cbLoader();
        $row = DB::table($this->table)->where($this->primary_key, $id)->first();


        if (! Crocoding::isRead() && $this->global_privilege == false || $this->button_edit == false) {
            Crocoding::insertLog('Try edit the data '.$row->{$this->title_field}.' at '.Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $page_menu = currentRouteAction();
        $page_title = "Edit ". Crocoding::getCurrentModule()->name;
        $command = 'edit';
        session()->put('current_row_id', $id);

        return view('crocoding::default.form', compact('id', 'row', 'page_menu', 'page_title', 'command'));
    }

    public function postEditSave($id)
    {
        $this->cbLoader();
        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if (! Crocoding::isUpdate() && $this->global_privilege == false) {
            Crocoding::insertLog('Try add data '.$row->{$this->title_field}.' at '.Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $this->validation($id);
        $this->input_assignment($id);

        if (Schema::hasColumn($this->table, 'updated_at')) {
            $this->arr['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->hook_before_edit($this->arr, $id);
        DB::table($this->table)->where($this->primary_key, $id)->update($this->arr);

        //Looping Data Input Again After Insert
        foreach ($this->data_inputan as $ro) {
            $name = $ro['name'];
            if (! $name) {
                continue;
            }

            $inputdata = g($name);

            //Insert Data Checkbox if Type Datatable
            if ($ro['type'] == 'checkbox') {
                if ($ro['relationship_table']) {
                    $datatable = explode(",", $ro['datatable'])[0];

                    $foreignKey2 = Crocoding::getForeignKey($datatable, $ro['relationship_table']);
                    $foreignKey = Crocoding::getForeignKey($this->table, $ro['relationship_table']);
                    DB::table($ro['relationship_table'])->where($foreignKey, $id)->delete();

                    if ($inputdata) {
                        foreach ($inputdata as $input_id) {
                            $relationship_table_pk = Crocoding::pk($ro['relationship_table']);
                            DB::table($ro['relationship_table'])->insert([
                                $relationship_table_pk => Crocoding::newId($ro['relationship_table']),
                                $foreignKey => $id,
                                $foreignKey2 => $input_id,
                            ]);
                        }
                    }
                }
            }

            if ($ro['type'] == 'select2') {
                if ($ro['relationship_table']) {
                    $datatable = explode(",", $ro['datatable'])[0];

                    $foreignKey2 = Crocoding::getForeignKey($datatable, $ro['relationship_table']);
                    $foreignKey = Crocoding::getForeignKey($this->table, $ro['relationship_table']);
                    DB::table($ro['relationship_table'])->where($foreignKey, $id)->delete();

                    if ($inputdata) {
                        foreach ($inputdata as $input_id) {
                            $relationship_table_pk = Crocoding::pk($ro['relationship_table']);
                            DB::table($ro['relationship_table'])->insert([
                                $relationship_table_pk => Crocoding::newId($ro['relationship_table']),
                                $foreignKey => $id,
                                $foreignKey2 => $input_id,
                            ]);
                        }
                    }
                }
            }

            if ($ro['type'] == 'child') {
                $name = str_slug($ro['label'], '');
                $columns = $ro['columns'];
                $count_input_data = count(g($name.'-'.$columns[0]['name'])) - 1;
                $child_array = [];
                $childtable = Crocoding::parseSqlTable($ro['table'])['table'];
                $fk = $ro['foreign_key'];

                DB::table($childtable)->where($fk, $id)->delete();
                $lastId = Crocoding::newId($childtable);
                $childtablePK = Crocoding::pk($childtable);

                for ($i = 0; $i <= $count_input_data; $i++) {

                    $column_data = [];
                    $column_data[$childtablePK] = $lastId;
                    $column_data[$fk] = $id;
                    foreach ($columns as $col) {
                        $colname = $col['name'];
                        $column_data[$colname] = g($name.'-'.$colname)[$i];
                    }
                    $child_array[] = $column_data;

                    $lastId++;
                }

                $child_array = array_reverse($child_array);

                DB::table($childtable)->insert($child_array);
            }
        }

        $this->hook_after_edit($id);

        $this->return_url = ($this->return_url) ? $this->return_url : g('return_url');

        //insert log
        $old_values = json_decode(json_encode($row), true);
        Crocoding::insertLog('Update data '.$this->arr[$this->title_field].' at '.Crocoding::getCurrentModule()->name, LogsController::displayDiff($old_values, $this->arr));

        if ($this->return_url) {
            return Crocoding::redirect($this->return_url, 'The data has been updated !', 'success');
        } else {
            if (g('submit') == 'Save & Add More') {
                return Crocoding::redirect(Crocoding::mainpath('add'), 'The data has been updated !', 'success');
            } else {
                return Crocoding::redirect(Crocoding::mainpath(), 'The data has been updated !', 'success');
            }
        }
    }

    public function getDelete($id)
    {
        $this->cbLoader();
        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if (! Crocoding::isDelete() && $this->global_privilege == false || $this->button_delete == false) {
            Crocoding::insertLog('Try delete the '.$row->{$this->title_field}.' data at '.Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        //insert log
        Crocoding::insertLog('Delete data '.$row->{$this->title_field}.' at '.Crocoding::getCurrentModule()->name);

        $this->hook_before_delete($id);

        if (Crocoding::isColumnExists($this->table, 'deleted_at')) {
            DB::table($this->table)->where($this->primary_key, $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
        } else {
            DB::table($this->table)->where($this->primary_key, $id)->delete();
        }

        $this->hook_after_delete($id);

        $url = g('return_url') ?: Crocoding::referer();

        return Crocoding::redirect($url, 'Delete the data success !', 'success');
    }

    public function getDetail($id)
    {
        $this->cbLoader();
        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if (! Crocoding::isRead() && $this->global_privilege == false || $this->button_detail == false) {
            Crocoding::insertLog('Try view the data '.$row->{$this->title_field}.' at '.Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $module = Crocoding::getCurrentModule();

        $page_menu = currentRouteAction();
        $page_title ='Detail '.$row->{$this->title_field};
        $command = 'detail';

        session()->put('current_row_id', $id);

        return view('crocoding::default.form', compact('row', 'page_menu', 'page_title', 'command', 'id'));
    }

    public function getImportData()
    {
        $this->cbLoader();
        $data['page_menu'] = currentRouteAction();
        $data['page_title'] = 'Import Data '.$module->name;

        if (g('file') && ! g('import')) {
            $file = base64_decode(g('file'));
            $file = storage_path('app/'.$file);
            $collection = new ImportCollection();
            Excel::import($collection, $file);
            $rows = $collection->getCollection();

            session()->put('total_data_import', count($rows));

            $data_import_column = [];
            foreach ($rows as $value) {
                $a = [];
                foreach ($value as $k => $v) {
                    $a[] = $k;
                }
                if (count($a)) {
                    $data_import_column = $a;
                }
                break;
            }

            $table_columns = DB::getSchemaBuilder()->getColumnListing($this->table);

            $data['table_columns'] = $table_columns;
            $data['data_import_column'] = $data_import_column;
        }

        return view('crocoding::import', $data);
    }

    public function postDoneImport()
    {
        $this->cbLoader();
        $data['page_menu'] = currentRouteAction();
        $data['page_title'] = trans('crocoding.import_page_title', ['module' => $module->name]);
        session()->put('select_column', g('select_column'));

        return view('crocoding::import', $data);
    }

    public function postDoImportChunk()
    {
        $this->cbLoader();
        $file_md5 = md5(g('file'));

        if (g('file') && g('resume') == 1) {
            $total = session()->get('total_data_import');
            $prog = intval(Cache::get('success_'.$file_md5)) / $total * 100;
            $prog = round($prog, 2);
            if ($prog >= 100) {
                Cache::forget('success_'.$file_md5);
            }

            return response()->json(['progress' => $prog, 'last_error' => Cache::get('error_'.$file_md5)]);
        }

        $select_column = session()->get('select_column');
        $select_column = array_filter($select_column);
        $table_columns = DB::getSchemaBuilder()->getColumnListing($this->table);

        $file = base64_decode(g('file'));
        $file = storage_path('app/'.$file);

        $rows = Excel::load($file, function ($reader) {
        })->get();

        $has_created_at = false;
        if (Crocoding::isColumnExists($this->table, 'created_at')) {
            $has_created_at = true;
        }

        $data_import_column = [];
        foreach ($rows as $value) {
            $a = [];
            foreach ($select_column as $sk => $s) {
                $colname = $table_columns[$sk];

                if (Crocoding::isForeignKey($colname)) {

                    //Skip if value is empty
                    if ($value->$s == '') {
                        continue;
                    }

                    if (intval($value->$s)) {
                        $a[$colname] = $value->$s;
                    } else {
                        $relation_table = Crocoding::getTableForeignKey($colname);
                        $relation_moduls = DB::table('cms_moduls')->where('table_name', $relation_table)->first();

                        $relation_class = __NAMESPACE__.'\\'.$relation_moduls->controller;
                        if (! class_exists($relation_class)) {
                            $relation_class = '\App\Http\Controllers\\'.$relation_moduls->controller;
                        }
                        $relation_class = new $relation_class;
                        $relation_class->cbLoader();

                        $title_field = $relation_class->title_field;

                        $relation_insert_data = [];
                        $relation_insert_data[$title_field] = $value->$s;

                        if (Crocoding::isColumnExists($relation_table, 'created_at')) {
                            $relation_insert_data['created_at'] = date('Y-m-d H:i:s');
                        }

                        try {
                            $relation_exists = DB::table($relation_table)->where($title_field, $value->$s)->first();
                            if ($relation_exists) {
                                $relation_primary_key = $relation_class->primary_key;
                                $relation_id = $relation_exists->$relation_primary_key;
                            } else {
                                $relation_id = DB::table($relation_table)->insertGetId($relation_insert_data);
                            }

                            $a[$colname] = $relation_id;
                        } catch (\Exception $e) {
                            exit($e);
                        }
                    } //END IS INT

                } else {
                    $a[$colname] = $value->$s;
                }
            }

            $has_title_field = true;
            foreach ($a as $k => $v) {
                if ($k == $this->title_field && $v == '') {
                    $has_title_field = false;
                    break;
                }
            }

            if ($has_title_field == false) {
                continue;
            }

            try {

                if ($has_created_at) {
                    $a['created_at'] = date('Y-m-d H:i:s');
                }

                DB::table($this->table)->insert($a);
                Cache::increment('success_'.$file_md5);
            } catch (\Exception $e) {
                $e = (string) $e;
                Cache::put('error_'.$file_md5, $e, 500);
            }
        }

        return response()->json(['status' => true]);
    }

    public function postDoUploadImportData()
    {
        return '<h1>Please make your own import function</h1>';
        $this->cbLoader();
        if (Request::hasFile('userfile')) {
            $file = Request::file('userfile');
            $ext = $file->getClientOriginalExtension();

            $validator = Validator::make([
                'extension' => $ext,
            ], [
                'extension' => 'in:xls,xlsx,csv',
            ]);

            if ($validator->fails()) {
                $message = $validator->errors()->all();

                return redirect(back())->with(['message' => implode('<br/>', $message), 'message_type' => 'warning']);
            }

            //Create Directory Monthly
            $filePath = 'uploads/'.Crocoding::myId().'/'.date('Y-m');
            Storage::makeDirectory($filePath);

            //Move file to storage
            $filename = md5(str_random(5)).'.'.$ext;
            $url_filename = '';


            if($filename && $ext) {
//                Storage::makeDirectory($path);
                $destinationPath = public_path($filePath);
                if(app('request')->file($file)->move($destinationPath, $filename)){
                    $url_filename = $filePath.'/'.$filename;
                }
            }


            $url = Crocoding::mainpath('import-data').'?file='.base64_encode($url_filename);

            return redirect($url);
        } else {
            return redirect(back());
        }
    }

    public function postActionSelected()
    {
        $this->cbLoader();
        $id_selected = g('checkbox');
        $button_name = g('button_name');

        if (! $id_selected) {
            return Crocoding::redirect($_SERVER['HTTP_REFERER'], 'Please select at least one data!', 'warning');
        }

        if ($button_name == 'delete') {
            if (! Crocoding::isDelete()) {
                Crocoding::insertLog('Try delete selected at '.Crocoding::getCurrentModule()->name);
                return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
            }

            $this->hook_before_delete($id_selected);
            $tablePK = Crocoding::pk($this->table);
            if (Crocoding::isColumnExists($this->table, 'deleted_at')) {

                DB::table($this->table)->whereIn($tablePK, $id_selected)->update(['deleted_at' => date('Y-m-d H:i:s')]);
            } else {
                DB::table($this->table)->whereIn($tablePK, $id_selected)->delete();
            }
            Crocoding::insertLog('Delete data '.implode(',', $id_selected).' at '. Crocoding::getCurrentModule()->name);

            $this->hook_after_delete($id_selected);

            $message = 'Delete selected success !';

            return redirect(back())->with(['message_type' => 'success', 'message' => $message]);
        }

        $action = str_replace(['-', '_'], ' ', $button_name);
        $action = ucwords($action);
        $type = 'success';
        $message = 'You have '.$action.' successfully !';

        if ($this->actionButtonSelected($id_selected, $button_name) === false) {
            $message = ! empty($this->alert['message']) ? $this->alert['message'] : 'Error';
            $type = ! empty($this->alert['type']) ? $this->alert['type'] : 'danger';
        }

        return redirect(back())->with(['message_type' => $type, 'message' => $message]);
    }

    public function getDeleteImage()
    {
        $this->cbLoader();
        $id = g('id');
        $column = g('column');

        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        if (! Crocoding::isDelete() && $this->global_privilege == false) {
            Crocoding::insertLog( 'Try delete the image of '.$row->{$this->title_field}.' data at '.Crocoding::getCurrentModule()->name);
            return Crocoding::redirect(Crocoding::adminPath(), 'Sorry you do not have privilege to access this area !');
        }

        $row = DB::table($this->table)->where($this->primary_key, $id)->first();

        $file = str_replace('uploads/', '', $row->{$column});
        if (Storage::exists($file)) {
            Storage::delete($file);
        }

        DB::table($this->table)->where($this->primary_key, $id)->update([$column => null]);

        Crocoding::insertLog( 'Try delete the image of '.$row->{$this->title_field}.' data at '.Crocoding::getCurrentModule()->name);

        return Crocoding::redirect(Request::server('HTTP_REFERER'), 'Delete the data success !', 'success');
    }

    public function postUploadSummernote()
    {
        $this->cbLoader();
        $name = 'userfile';
        if ($file = Crocoding::uploadFile($name, true)) {
            echo asset($file);
        }
    }

    public function postUploadFile()
    {
        $this->cbLoader();
        $name = 'userfile';
        if ($file = Crocoding::uploadFile($name, true)) {
            echo asset($file);
        }
    }

    public function actionButtonSelected($id_selected, $button_name)
    {
    }

    public function hook_query_index(&$query)
    {
    }

    public function hook_row_index($index, &$value)
    {
    }

    public function hook_before_add(&$arr)
    {
    }

    public function hook_after_add($id)
    {
    }

    public function hook_before_edit(&$arr, $id)
    {
    }

    public function hook_after_edit($id)
    {
    }

    public function hook_before_delete($id)
    {
    }

    public function hook_after_delete($id)
    {
    }

}

