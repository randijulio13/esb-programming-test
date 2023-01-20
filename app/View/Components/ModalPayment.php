<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ModalPayment extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

     public $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal-payment');
    }
}
