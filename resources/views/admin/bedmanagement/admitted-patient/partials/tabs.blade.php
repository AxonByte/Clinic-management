<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $active == 'checkin' ? 'active' : '' }}"
           href="{{ route('admin.bedmanagement.admition.edit', $admission->id) }}">Check In</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active == 'dailyprogress' ? 'active' : '' }}"
           href="{{ route('admin.bedmanagement.admission.dailyprogress', $admission->id) }}">Daily Progress</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active == 'medicines' ? 'active' : '' }}"
           href="{{ route('admin.bedmanagement.admission.medicines', $admission->id) }}">Medicines</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active == 'service' ? 'active' : '' }}"
           href="{{ route('admin.bedmanagement.admission.service', $admission->id) }}">Services</a>
    </li>
    {{-- <li class="nav-item">
        <a class="nav-link {{ $active == 'diagnostictest' ? 'active' : '' }}"
           href="{{ route('admin.bedmanagement.admission.diagnostictest', $admission->id) }}">Diagnostic Test</a>
    </li> --}}
    {{-- <li class="nav-item">
        <a class="nav-link {{ $active == 'billsummary' ? 'active' : '' }}"
           href="{{ route('admin.bedmanagement.admission.billsummary', $admission->id) }}">Bill Summary</a>
    </li> --}}
    <li class="nav-item">
        <a class="nav-link {{ $active == 'discharge' ? 'active' : '' }}"
           href="{{ route('admin.bedmanagement.admission.discharge', $admission->id) }}">Discharge</a>
    </li>
    <!-- Add other tabs similarly -->
</ul>
