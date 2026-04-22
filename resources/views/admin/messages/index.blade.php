@extends('layouts.admin')

@section('content')
<h2 class="mb-3">Contact Messages</h2>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Status</th>
            <th width="180">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($messages as $message)
            <tr>
                <td>{{ $message->id }}</td>
                <td>{{ $message->name }}</td>
                <td>{{ $message->email }}</td>
                <td>{{ $message->subject }}</td>
                <td>{{ $message->is_read ? 'Read' : 'Unread' }}</td>
                <td>
                    <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-info btn-sm">View</a>
                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this message?')" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection