<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class DeleteButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $id;
    public $route;
    public $title;

    public function __construct($id, $route, $title)
    {
        $this->id = $id;
        $this->route = $route;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.delete-button');
    }
}
