@extends('layouts.app')

@section('content')
<div class="container-fluid content-inner mt-3 py-0">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-9">
            <div id="printableArea">
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <h4 class="mb-3 d-flex justify-content-between align-items-center">
                            <strong>Rx Prescription :(Id: {{ $prescription->id }})</strong> <span></span>
                            <span class="badge bg-danger">INVOICE</span>
                        </h4>

                        <hr>
                        <h5 class="text-primary">{{ $prescription->doctor->name ?? 'N/A' }}</h5>

                        <!-- Patient Details -->
                        <div class="row mt-3 mb-4">
                            <div class="col-md-6">
                                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($prescription->date)->format('d-m-Y') }}</p>
                                <p><strong>Patient:</strong> {{ $prescription->patient->name ?? 'N/A' }}</p>
                                <p><strong>Patient ID:</strong> {{ $prescription->patient->id ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Prescription Id:</strong> {{ $prescription->id }}</p>
                                <p><strong>Age:</strong> {{ $prescription->patient->age ?? '-' }} Years</p>
                                <p><strong>Gender:</strong> {{ ucfirst($prescription->patient->gender ?? '-') }}</p>
                            </div>
                        </div>

                        <hr>

                        <!-- History / Notes / Advice and Medicines -->
                        <div class="row">
                            <!-- Left: History -->
                            <div class="col-md-6">
                                <h6 class="fw-bold">History</h6>
                                <p class="text-muted">{{ $prescription->history }}</p>

                                <h6 class="fw-bold">Note</h6>
                                <p class="text-muted">{{ $prescription->notes }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Advice</h6>
                                <p class="text-muted">{{ $prescription->advice }}</p>
                            </div>

                        
                        </div> <!-- /.row -->

                        <div class="row">
                            <!-- Right: Medicines -->
                            <div class="col">
                                <h6 class="fw-bold">℞ Medicine</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Medicine</th>
                                                <th>Instruction</th>
                                                <th>Frequency</th>
                                                <th>Days</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($prescription->medicines as $medicine)
                                                <tr>
                                                    <td>{{ $medicine->name }} - {{ $medicine->pivot->dosage }}</td>
                                                    <td>{{ $medicine->pivot->instructions }}</td>
                                                    <td>{{ $medicine->pivot->frequency }}</td>
                                                    <td>{{ $medicine->pivot->days }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No medicine added.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-0"><strong>{{ $prescription->doctor->name ?? 'Doctor' }}</strong></p>
                                    <small>Signature</small>
                                </div>
                                <div class="text-end">
                                    <p class="mb-0"><strong>Hospital</strong></p>
                                    <small>Ka/5, Bashundhara R/A Gate<br>+8801777024443</small>
                                </div>
                            </div>

                            <div style="height: 1px;color:gray;"></div>
                            <hr class="my-3">

                            <p class="text-center small text-muted">
                                This prescription is valid for 30 days from the date of issue. Please consult your doctor for any changes or concerns.
                            </p>
                            <p class="text-center small text-muted mb-0">© {{ now()->year }} Hospital. All rights reserved.</p>

                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div>
        </div>

        <!-- Sidebar Buttons -->
        <div class="col-md-3">
            <div class="card shadow-sm p-3 rounded">
                <button class="btn btn-primary mb-3" onclick="printPrescription()">🖨️ Print</button>
                {{-- <a href="" class="btn btn-warning w-100 mb-2 text-white">Download</a> --}}
                <a href="{{ route('admin.prescription.index')}}" class="btn btn-info w-100 mb-2 text-white">All Prescription</a>
                <a href="{{ route('admin.prescription.create')}}" class="btn btn-success w-100">+ Add Prescription</a>
            </div>
        </div>
    </div>
</div>
<script>
function printPrescription() {
    var printContents = document.getElementById('printableArea').innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();

    document.body.innerHTML = originalContents;
    location.reload(); // To restore JS functionality (if needed)
}
</script>

@endsection
