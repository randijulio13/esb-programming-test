<?php

namespace App\View\Components;

use App\Models\Customer;
use Illuminate\View\Component;

class ModalAddInvoice extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $customers = Customer::get();
        return view('components.modal-add-invoice', compact('customers'));
    }
}
