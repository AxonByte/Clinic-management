@extends('layouts.app')

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="card-title mb-0">Roles</h4>
          <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">+ Add Role</a>
        </div>

        @if(session('success'))
          <div class="alert alert-success m-3">
            {{ session('success') }}
          </div>
        @endif

        <div class="card-body">
          @if($roles->count())
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Role Name</th>
                 
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($roles as $index => $role)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $role->name }}</td>
               
                    <td>
                      <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-warning">Edit</a>

                      <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="d-inline-block" 
                            onsubmit="return confirm('Are you sure you want to delete this role?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @else
            <p>No roles found.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
