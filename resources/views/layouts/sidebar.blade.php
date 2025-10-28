@if (Auth::user()->hasRole('admin'))
    @include('layouts.partials.sidebar-admin')
@elseif (Auth::user()->hasRole('petugas_pst'))
    @include('layouts.partials.sidebar-pst')
@elseif (Auth::user()->hasRole('pengolah_data'))
    @include('layouts.partials.sidebar-pengolah')
@else
    <p>Tidak ada sidebar untuk role ini</p>
@endif
