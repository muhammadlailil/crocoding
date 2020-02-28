<?php
namespace crocodicstudio\crocoding\excel;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportCollection implements ToCollection
{
    private $collection;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
       $this->collection = $collection;
    }

    public function getCollection(){
        return $this->collection;
    }
}
