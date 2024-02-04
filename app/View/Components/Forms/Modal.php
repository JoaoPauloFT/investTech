<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Modal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $title;
    public $description;
    public $route;
    public $titleImage;
    public $width;
    public $height;
    public $idModal;
    public $textButtonConfirm;
    public $idItem;

    public function __construct($title, $description, $route, $textButtonConfirm, $titleImage = '', $width = 0, $height = 0, $idModal = 'modalForm', $idItem = '')
    {
        $this->title = $title;
        $this->description = $description;
        $this->route = $route;
        $this->textButtonConfirm = $textButtonConfirm;
        $this->titleImage = $titleImage;
        $this->width = $width;
        $this->height = $height;
        $this->idModal = $idModal;
        $this->idItem = $idItem;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.modal');
    }
}
