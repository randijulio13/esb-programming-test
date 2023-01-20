@extends('layouts.app')

@section('title', 'Invoice')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="my-4">
                <div class="d-flex align-items-center">
                    <div>
                        <h4>Detail Invoice</h4>
                        <a href="{{ route('invoice.index') }}" class="btn btn-primary btn-sm">Kembali</a>
                    </div>
                    <div class="ms-auto">
                        @if(!$invoice->is_paid)
                        <button class="btn btn-success" id="addPayment">Add Payment</button>
                        @endif
                        <a href="{{ route('invoice.delete',['invoice' => $invoice->id]) }}" class="btn btn-danger" id="deleteInvoice">Delete Invoice</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body postition-relative">
                    @if($invoice->is_paid)
                    <div class="stamp">PAID</div>
                    @endif
                    <div class="row mb-4">
                        <div class="col-lg-7">
                            <h2 class="fw-bold mb-5">INVOICE</h2>

                            <table class="table-borderless table table-sm">
                                <tr>
                                    <td width="100px">Invoice ID</td>
                                    <td class="fw-bold">{{ $invoice->id }}</td>
                                </tr>
                                <tr>
                                    <td>Issue Date</td>
                                    <td class="fw-bold">{{ $invoice->issueDate('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Due Date</td>
                                    <td class="fw-bold">{{ $invoice->dueDate('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Subject</td>
                                    <td class="fw-bold">{{ $invoice->subject }}</td>
                                </tr>
                            </table>

                        </div>
                        <div class="col-lg-5">
                            <table class="table-borderless table table-sm">
                                <tr>
                                    <td width="100px">From</td>
                                    <td>
                                        <span class="fw-bold">Discovery Designs</span><br>
                                        <span>42 St Vincent Place Glasglow G1 2ER</span><br>
                                        <span>Scottland</span>
                                    </td>
                                </tr>
                            </table>
                            <table class="table-borderless table table-sm">
                                <tr>
                                    <td width="100px">For</td>
                                    <td>
                                        <span class="fw-bold">{{ $invoice->customer->name }}</span><br>
                                        <span>{{ $invoice->customer->address }}</span><br>
                                        <span>{{ $invoice->customer->state }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item Type</th>
                                            <th width="45%">Description</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoice->items as $item)
                                        <tr>
                                            <td>{{ $item->detailItem->type }}</td>
                                            <td>{{ $item->detailItem->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>£{{ number_format($item->detailItem->price,2,'.',',') }}</td>
                                            <td class="fw-bold">£{{ number_format($item->quantity * $item->detailItem->price,2,'.',',') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12 ms-auto">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="fw-bold text-end">£{{ number_format($invoice->subtotal,2,'.',',') }}</td>
                                </tr>
                                <tr>
                                    <td>Tax</td>
                                    <td class="fw-bold text-end">£{{ number_format($invoice->tax,2,'.',',') }}</td>
                                </tr>
                                <tr>
                                    <td>Payments</td>
                                    <td class="fw-bold text-end">£{{ number_format($invoice->payments,2,'.',',') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold fs-4">Amount Due</td>
                                    <td class="fw-bold fs-4 text-end">£{{ number_format($invoice->amount_due,2,'.',',') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-payment :invoice="$invoice" />
@endsection

@push('style')
<style>
    .stamp {
        color: green;
        font-weight: bold;
        font-size: 50px;
        font-family: Arial;
        position: absolute;
        top: 10vh;
        left: 18vw;
        opacity: 0.5;
        transform: rotate(-20deg);
        border-color: green;
        border-style: solid;
        border-width: 5px;
        padding: 0 20px;
    }
</style>
@endpush

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        $('#addPayment').on('click', function(e) {
            e.preventDefault()
            $('#modalPayment').modal('show')
        })

        $("#deleteInvoice").on('click', async function(e) {
            e.preventDefault()
            let url = $(this).attr('href')
            try {

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        let res = await $.ajax(url, {
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'DELETE',
                        })
                        await Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Invoice Deleted Successfully',
                            timer: 3000,
                            showConfirmButton: false
                        })
                        window.location.href = res.redirect
                    }
                })
            } catch (err) {
                return Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong',
                })
            }
        })
    }, false);
</script>
@endpush