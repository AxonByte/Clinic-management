@extends('layouts.app')

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="card-title mb-0">Create New Role</h4>
          <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm">Back to Roles</a>
        </div>
        <div class="card-body">

          @if ($errors->any())
            <div class="alert alert-danger mb-3">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="name" class="form-label">Role Name</label>
              <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
			  
            </div>

            <h5 class="mt-4 mb-3">Assign Permissions</h5>

            @foreach($permissions->groupBy('module.name') as $moduleName => $modulePermissions)
              <div class="card mb-3 shadow-none border">
                <div class="card-header bg-light fw-semibold">
                  {{ $moduleName }}
                </div>
                <div class="card-body">
                  <div class="row">
                    @foreach($modulePermissions as $permission)
                      <div class="col-md-4">
                        <div class="form-check mb-2">
                          <input class="form-check-input" type="checkbox" 
                                 name="permissions[]" value="{{ $permission->id }}" 
                                 id="perm_{{ $permission->id }}" 
                                 {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}>
                          <label class="form-check-label" for="perm_{{ $permission->id }}">
                            {{ $permission->name }}
                          </label>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            @endforeach

            <button type="submit" class="btn btn-primary">Create Role</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
