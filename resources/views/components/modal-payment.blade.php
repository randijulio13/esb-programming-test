<div class="modal fade" tabindex="-1" id="modalPayment" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-body" id="formUpdate" action="{{ route('invoice.update',['invoice'=>$invoice->id]) }}">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-12 mb-2">
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
                                <td class="fs-5 fw-bold">Total</td>
                                <td class="fw-bold fs-5 text-end">£{{ number_format($invoice->total,2,'.',',') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label for="payments">Payment Amount</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">£</span>
                            <input type="number" class="form-control" placeholder="Payment Amount" name="payments" id="payments" aria-label="Username" aria-describedby="basic-addon1" max="{{ $invoice->total }}" value="{{ $invoice->total }}">
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="formUpdate" id="submitButton" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#modalPayment').on('shown.bs.modal', function() {
            $('#payments').focus()
        })

        $('#payments').on('input', function() {
            let input = $(this).val()
            if (!input) {
                $('#submitButton').addClass('disabled').attr('disabled', true)
            } else {
                $('#submitButton').removeClass('disabled').removeAttr('disabled', true)
            }
        })

        $('#formUpdate').on('submit', async function(e) {
            e.preventDefault()
            let url = $(this).attr('action')
            let data = $(this).serialize()
            try {
                let res = await $.ajax({
                    url,
                    data,
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                });
                $('#modalPayment').modal('hide')
                await Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: res.message,
                    timer: 3000,
                    showConfirmButton: false
                })
                window.location.reload()
            } catch (err) {
                if (err.status == 422) {
                    return Swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        text: 'Please fill out the form',
                        timer: 2000,
                        showConfirmButton: false
                    })
                }
                return Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong',
                })
            }
        })
    })
</script>
@endpush