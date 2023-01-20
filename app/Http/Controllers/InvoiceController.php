<?php

namespace App\Http\Controllers;

use App\DataTables\InvoiceDataTable;
use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('invoice.index');
    }

    public function detail(Invoice $invoice)
    {
        return view('invoice.detail', compact('invoice'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        $request->validated();

        $invoiceItems = $this->_getInvoiceItems($request);
        [$subtotal, $tax, $total] = $this->_getInvoiceTotal($request, $invoiceItems);

        $invoice = Invoice::create([
            'subject' => $request->subject,
            'customer_id' => $request->for,
            'issue_date' => $request->issue_date,
            'due_date' => $request->due_date,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'is_paid' => 0
        ]);

        $invoice->items()->createMany($invoiceItems);
        return response()->json([
            'message' => 'Invoice created'
        ], 201);
    }

    public function _getInvoiceTotal(StoreInvoiceRequest $request, array $invoiceItems)
    {
        $items = Item::whereIn('id', $request->items_id)->get()->toArray();
        $subtotal = 0;
        foreach ($items as $i =>  $item) {
            $key = array_search($item['id'], array_column($invoiceItems, 'item_id'));
            $itemAmount = $invoiceItems[$key]['quantity'] * $item['price'];
            $subtotal = $subtotal + $itemAmount;
        }
        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax;

        return [$subtotal, $tax, $total];
    }

    public function _getInvoiceItems(StoreInvoiceRequest $request)
    {
        $invoiceItems = [];
        foreach ($request->quantity as $i => $q) {
            $invoiceItems[] = [
                'item_id' => $request->items_id[$i],
                'quantity' => $q
            ];
        }
        return $invoiceItems;
    }

    public function delete(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json([
            'message' => 'Invoice deleted',
            'redirect' => route('invoice.index')
        ], 200);
    }

    public function update(Invoice $invoice, Request $request)
    {
        $request->validate([
            'payments' => 'required'
        ]);

        $invoice->update([
            'is_paid' => 1,
            'payments' => $request->payments
        ]);

        return response()->json([
            'message' => 'Invoice paid successfully'
        ], 200);
    }

    public function getInvoices()
    {
        $invoices = Invoice::get()->toArray();
        return response()->json([
            'data' => $invoices
        ], 200);
    }

    public function getInvoiceById($invoice)
    {
        $invoice = Invoice::with('items.detailItem')->find($invoice)->toArray();
        if (!$invoice) {
            return response()->json([
                'message' => 'Invoice not found'
            ], 200);
        }
        $invoice['items'] = array_map(function ($item) {
            return [
                'name' => $item['detail_item']['name'],
                'type' => $item['detail_item']['type'],
                'price' => $item['detail_item']['price'],
                'quantity' => $item['quantity']
            ];
        }, $invoice['items']);
        return response()->json([
            'data' => $invoice
        ], 200);
    }
}
