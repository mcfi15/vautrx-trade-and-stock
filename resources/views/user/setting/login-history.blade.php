@extends('layouts.app')

@section('title', 'User Center')

@section('content')

<section class="generic">
  <!-- Page container -->
  <div class="container">
    <div class="row mt-3 mb-3">
      <div class="col-12 col-md-6 order-2 order-md-1">
        <div class="page-title-content d-flex align-items-start mt-2">
          <span>
            Welcome, <span>{{ auth()->user()->name }}</span>! <br />
          </span>
        </div>
      </div>

      <div class="col-12 col-md-6 order-1 order-md-2 float-right">
        <ul class="text-right breadcrumbs list-unstyle">
          <li>
            <a class="btn btn-warning btn-sm" href="/">Home</a>
          </li>
          <li>
            <a href="{{ url('user-center') }}" class="btn btn-warning btn-sm">User</a>
          </li>
          <li class="btn btn-warning btn-sm active">Login History</li>
        </ul>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12">

            <div class="">
              <div class="card">
                <div class="card-header">
                  <span class="card-title">Login History</span>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table last-login-table table-striped table-hover" id="investLog_content">
                      <thead>
                        <tr>
                          <th>Operating Time</th>
                          <th>Action Type</th>
                          <th>Action Remark</th>
                          <th>IP Address</th>
                          <th>Location</th>
                          <th>Status</th>
                        </tr>
                      </thead>

                      <tbody>
                        @forelse ($loginHistories as $log)
                          <tr>
                            {{-- Operating Time --}}
                            <td>{{ $log->login_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</td>

                            {{-- Action Type --}}
                            <td>Login</td>

                            {{-- Action Remark --}}
                            <td>
                                @if($log->user_agent)
                                    {{ $log->device_info ?? 'Login' }}
                                @else
                                    Login
                                @endif
                            </td>

                            {{-- IP --}}
                            <td>{{ $log->ip_address }}</td>

                            {{-- Formatted Location from accessor --}}
                            <td>{{ $log->formatted_location }}</td>

                            {{-- Status --}}
                            <td>
                                @if($log->success)
                                  <font class="green">Normal</font>
                                @else
                                  <font class="red">Failed - {{ $log->failure_reason ?? 'Unknown' }}</font>
                                @endif
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="6" class="text-center">No login history found.</td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="pages">
                      {{ $loginHistories->links('pagination::bootstrap-4') }}
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <!-- END Main Content -->

          </div>
        </div>
      </div>
    </div>
  </div>
</section>



@endsection