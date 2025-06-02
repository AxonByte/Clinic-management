@extends('layouts.app')
@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add Medicine</h4>
                            </div>
                            <a class="btn btn-primary" href="{{ route('admin.medicine.index')}}">Medicine List</a>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.medicine.store') }}" method="POST">
                                    @csrf
                               <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1">NAME <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1">GENERIC NAME <span class="text-danger">*</span></label>
                                        <input type="text" name="generic_name" class="form-control" required>
                                    </div>

                                </div>
                               <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1">CATEGORY <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-control" required>
                                            <option value="">Select Category</option>
                                            @foreach($category as $categories)
                                                <option value="{{ $categories->id }}">{{ $categories->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1">COMPANY</label>
                                        <input type="text" name="company" class="form-control" required>
                                    </div>
                                </div>
                               <div class="row mb-3">
                                     <div class="col-md-6">
                                        <label class="text-secondary mb-1">PURCHASE PRICE <span class="text-danger">*</span></label>
                                        <input type="text" name="purchase_price" class="form-control" required>
                                    </div>
                                     <div class="col-md-6">
                                        <label class="text-secondary mb-1">SALE PRICE <span class="text-danger">*</span></label>
                                        <input type="text" name="sale_price" class="form-control" required>
                                    </div>
                                    
                                </div>

                               <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1">EFFECTS</label>
                                        <input type="text" name="effects" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1">STORE BOX</label>
                                        <input type="text" name="store_box" class="form-control" required>
                                    </div>
                                </div>
                               <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1">QUANTITY <span class="text-danger">*</span></label>
                                        <input type="text" name="quantity" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-secondary mb-1">EXPIRY DATE <span class="text-danger">*</span></label>
                                        <input type="date" name="expiry_date" class="form-control" required>
                                    </div>
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
    
@endsection
