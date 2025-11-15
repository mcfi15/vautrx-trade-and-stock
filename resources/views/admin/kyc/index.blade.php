@extends('admin.layouts.app')

@section('title', 'KYC Submissions')

@section('content')

<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Pending KYC</h1>

    <div class="bg-white shadow rounded-lg p-4">
        <table class="min-w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-2 px-3">User</th>
                    <th class="py-2 px-3">Email</th>
                    <th class="py-2 px-3">Status</th>
                    <th class="py-2 px-3">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                <tr class="border-b">
                    <td class="py-2 px-3">{{ $user->name }}</td>
                    <td class="py-2 px-3">{{ $user->email }}</td>
                    <td class="py-2 px-3">
                        <span class="px-2 py-1 rounded bg-yellow-200 text-yellow-800">
                            Pending
                        </span>
                    </td>
                    <td class="py-2 px-3">
                        <a href="{{ url('admin/kyc/show', $user) }}" 
                           class="text-blue-600 hover:underline">
                           View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 text-center text-gray-500">No KYC submissions yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

