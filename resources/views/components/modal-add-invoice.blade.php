<div class="modal fade" tabindex="-1" id="modalInvoice" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-body" id="formInvoice" action="{{ route('invoice.store') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-6 mb-2">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject">
                    </div>
                    <div class="col-lg-6 mb-2">
                        <label for="for">For</label>
                        <select name="for" id="for" class="form-select">
                            <option value="" disabled selected>-- SELECT CUSTOMER --</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-2">
                        <label for="issueDate">Issue Date</label>
                        <input type="date" class="form-control" name="issue_date" id="issueDate">
                    </div>
                    <div class="col-lg-6 mb-2">
                        <label for="dueDate">Due Date</label>
                        <input type="date" class="form-control" name="due_date" id="dueDate">
                    </div>
                </div>
                <hr />
                <h4>Items</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Item Type</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItems"></tbody>
                    </table>
                    <button id="addItem" class="btn btn-primary" type="button">Add Item</button>
                </div>
                <div class="d-flex justify-content-end">
                    <table class="table-borderless table-sm">
                        <tr>
                            <td>Subtotal</td>
                            <th id="subtotal">£0</th>
                        </tr>
                        <tr>
                            <td>Tax(10%)</td>
                            <th id="tax">£0</th>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <th id="total">£0</th>
                        </tr>
                    </table>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="btnReset">Reset</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="formInvoice" id="submitButton" class="btn btn-primary disabled" disabled>Submit</button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let items = []
        let itemOpt = '<option value="" disabled selected>-- SELECT ITEM --</option>';
        let subtotal = 0;
        let tax = 0;
        let total = 0;

        async function getItems() {
            let res = await $.get('/api/item')
            items = res.data
            $.each(res.data, function(index, item) {
                itemOpt += `<option value="${item.id}">${item.name}</option>`
            })
        }
        getItems()

        function validateForm() {
            let validate = 0
            $('#formInvoice').find('input').each(function(index) {
                if (!$(this).val()) {
                    validate++
                }
            })
            if (total == 0) {
                validate++
            }
            if (validate > 0) {
                return $('#submitButton').attr('disabled', true).addClass('disabled')
            }
            return $('#submitButton').removeAttr('disabled').removeClass('disabled')
        }

        function getTotal() {
            subtotal = 0;
            tax = 0;
            total = 0;
            $('.quantity').each(function(index, el) {
                let id = $(this).parents('tr').find('.item-id').val()
                let item = items.find(item => item.id == id)
                let qty = $(this).val()
                let amount = qty * item.price
                subtotal = subtotal + amount
            })

            tax = subtotal * 10 / 100;
            total = subtotal + tax;
            $('#subtotal').html('£' + subtotal)
            $('#tax').html('£' + tax)
            $('#total').html('£' + total)
        }

        function cleanForm() {
            $('#invoiceItems').html('');
            $('#formInvoice')[0].reset();
            getTotal()
        }

        $('#btnReset').on('click', function(e) {
            e.preventDefault()
            cleanForm()
        })

        $('#addItem').on('click', function() {
            $('#invoiceItems').append(`<tr class="py-1">
                <td>
                    <select class="form-select item-id" name="items[]">
                        ${itemOpt}
                    </select>
                </td>
                <td class="text-center item-type"></td>
                <td></td>
                <td class="item-price"></td>
                <td class="amount"></td>
                <td>
                    <button class="btn btn btn-danger btn-delete-item">Delete</button>
                </td>
            </tr>`)
        })

        $(document).on('change', '.item-id', function() {
            let id = $(this).val()
            let item = items.find(item => item.id == id)
            $(this).parents('tr').html(`
                <td><input type="hidden" name="items_id[]" class="item-id" value="${item.id}">${item.name}</td>
                <td class="text-center item-type">${item.type}</td>
                <td>
                    <input class="quantity form-control" type="number" value="1" name="quantity[]">
                </td>
                <td class="item-price">£${item.price}</td>
                <td class="amount">£${item.price}</td>
                <td>
                    <button class="btn btn-danger btn-delete-item">Delete</button>
                </td>
            `)
            getTotal()
            validateForm()
        })

        $(document).on('click', '.btn-delete-item', function(e) {
            e.preventDefault()
            $(this).parents('tr').remove()
            getTotal()
        })

        $(document).on('input', '.quantity', function() {
            let id = $(this).parents('tr').find('.item-id').val()
            let item = items.find(item => item.id == id)
            let amount = $(this).val() * item.price
            $(this).parents('tr').find('.amount').html('£' + amount)
            getTotal()
        })

        $('#formInvoice').on('submit', async function(e) {
            e.preventDefault()
            let data = $(this).serialize()
            let url = $(this).attr('url')
            try {
                Swal.showLoading()
                let res = await $.post(url, data)
                $('#modalInvoice').modal('hide')
                await Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Invoice created successfully',
                    timer: 2000,
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

        $(document).on('input', function() {
            validateForm()
        })

        $('#modalInvoice').on('shown.bs.modal', function() {
            $('#subject').focus()
        })
    })
</script>
@endpush