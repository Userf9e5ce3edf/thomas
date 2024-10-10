@extends('layouts.interventionLayout')

@section('content')
    <div class="container">
        <h1>Interventions</h1>
        <a href="{{ route('interventions.create') }}" class="btn btn-primary">Create New Intervention</a>
        <table class="table mt-3">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Code</th>
                <th>Intervention Date</th>
                <th>Steps Count</th>
                <th>Total Duration</th>
                <th>User Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($interventions as $intervention)
                <tr id="intervention-{{ $intervention->id }}">
                    <td>{{ $intervention->id }}</td>
                    <td>{{ $intervention->title }}</td>
                    <td>{{ $intervention->code }}</td>
                    <td>{{ \Carbon\Carbon::parse($intervention->intervention_date)->format('d-m-Y H:i') }}</td>
                    <td>{{ $intervention->steps_count }}</td>
                    <td>{{ $intervention->total_duration }}</td>
                    <td>{{ $intervention->user_name }}</td>
                    <td>
                        <a href="{{ route('interventions.edit', $intervention->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('interventions.destroy', $intervention->id) }}" method="POST" class="delete-form" data-id="{{ $intervention->id }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const interventionId = form.getAttribute('data-id');
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (response.ok) {
                                document.getElementById(`intervention-${interventionId}`).remove();
                            } else {
                                alert('Failed to delete the intervention.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while deleting the intervention.');
                        });
                });
            });
        });
    </script>
@endsection
