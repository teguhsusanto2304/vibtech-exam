<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Table extends Component
{
    public $items;
    public $columns;
    public $actions;
    public $badgeFields;
    public $searchParams;

    /**
     * Create a new component instance.
     */
    public function __construct($items, $columns, $actions = [], $badgeFields = [], $searchParams = [])
    {
        $this->items = $items;
        $this->columns = $columns;
        $this->actions = $actions;
        $this->badgeFields = $badgeFields ?? [];
        $this->searchParams = $searchParams ?? [];
    }

    /**
     * Get the view / contents.
     */
    public function render()
    {
        return view('components.table');
    }
}
