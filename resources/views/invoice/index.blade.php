@extends('layouts.app')

@section('title', 'Invoice')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="my-4">Invoice</h4>
                <button class="btn btn-primary" id="addInvoice">Add Invoice</button>
            </div>
            <div class="card">

                <div class="card-body">
                    <x-invoice-table />
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-add-invoice />
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $("#addInvoice").on('click', function() {
            $('#modalInvoice').modal('show')
        })
    }, false);
</script>
@endpush