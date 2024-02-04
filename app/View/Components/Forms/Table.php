<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Table extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $dropdownFilter;

    public function __construct($dropdownFilter)
    {
        //
        $this->dropdownFilter = $dropdownFilter;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.table');
    }
}
