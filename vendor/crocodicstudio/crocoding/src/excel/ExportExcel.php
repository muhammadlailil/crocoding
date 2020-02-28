<?php
namespace crocodicstudio\crocoding\excel;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportExcel implements FromView
{


    private $view;
    private $data;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function setData($data)
    {
        $this->data = $data;
    }
    /**
     * @return View
     */
    public function view(): View
    {
        return view($this->view,$this->data);
    }
}
