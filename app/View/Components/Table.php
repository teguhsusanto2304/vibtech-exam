<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Table extends Component
{
    public $items;
    public $columns;
    public $actions;

    /**
     * Create a new component instance.
     */
    public function __construct($items, $columns, $actions = [])
    {
        $this->items = $items;
        $this->columns = $columns;
        $this->actions = $actions;
    }

    /**
     * Get the view / contents.
     */
    public function render()
    {
        return view('components.table');
    }
}
