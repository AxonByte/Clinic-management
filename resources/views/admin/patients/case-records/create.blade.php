@extends('layouts.app')
@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add Cases</h4>
                            </div>
                            <a class="btn btn-primary" href="{{ route('admin.patient.cases.index')}}">Cases List</a>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.patient.cases.store') }}" method="POST">
                                    @csrf
                               <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="fw-bold text-secondary mb-1">Date</label>
                                        <input type="date" name="date" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="fw-bold text-secondary mb-1">Patient</label>
                                        <select name="patient_id" class="form-control" required>
                                            <option value="">Select patient</option>
                                            @foreach($patients as $patient)
                                                <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                               <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="fw-bold text-secondary mb-1">Title</label>
                                        <input type="text" name="title" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="fw-bold text-secondary mb-1">Symptoms</label>
                                        <select name="symptom_id" class="form-control">
                                            <option value="">Select Symptoms</option>
                                            @foreach($symptoms as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                               <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="fw-bold text-secondary mb-1">Diagnosis</label>
                                        <select name="diagnosis_id" class="form-control">
                                            <option value="">Select Diagnosis</option>
                                            @foreach($diagnoses as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->disease_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="fw-bold text-secondary mb-1">Advice</label>
                                        <select name="advice_id" class="form-control">
                                            <option value="">Select Advice</option>
                                            @foreach($advices as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                               <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="fw-bold text-secondary mb-1">Treatment</label>
                                        <select name="treatment_id" class="form-control">
                                            <option value="">Select Treatment</option>
                                            @foreach($treatments as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        
                                    </div>
                                </div>


                                <div class="mb-3">
                                <label class="fw-bold text-secondary mb-1">History</label>
                                <textarea name="history" class="form-control" id="doctorDesc"></textarea>
                                </div>

                             <div class="row">
                                    <div class="text-end" style="margin-right: 10px;">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                            </div>
                            </form>
                        </div>
            </div>
        </div>
    </div>
    </div>
    </div>
     <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
    let departmentEditor;
    ClassicEditor
        .create(document.querySelector('#doctorDesc'))
        .then(editor => {
            departmentEditor = editor;
            editor.ui.view.editable.element.style.height = '300px';
        })
        .catch(error => {
            console.error('CKEditor initialization error:', error);
        });
</script>
@endsection
