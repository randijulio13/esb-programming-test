<div class="table-responsive">
    <table class="table">
        <tr>
            <th>#</th>
            <th>InvoiceID</th>
            <th>Subject</th>
            <th>Issue Date</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        @foreach ($invoices as $i => $invoice)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $invoice->id }}</td>
            <td>{{ $invoice->subject }}</td>
            <td>{{ $invoice->issue_date }}</td>
            <td>{{ $invoice->due_date }}</td>
            <td>
                @if($invoice->is_paid)
                <span class="badge bg-success">PAID</span>
                @else
                <span class="badge bg-warning">NOT PAID</span>
                @endif
            </td>
            <td>
                <a href="{{ route('invoice.detail',['invoice' => $invoice->id]) }}" class="btn btn-sm btn-primary">Detail</a>
            </td>
        </tr>
        @endforeach
    </table>
    {{ $invoices->links() }}
</div>