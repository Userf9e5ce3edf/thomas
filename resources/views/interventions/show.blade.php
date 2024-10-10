@extends('layouts.interventionLayout')

@section('content')
    <div class="container">
        <h1>Intervention Details</h1>
        <div class="card mb-3">
            <div class="card-header">{{ $intervention->title }}</div>

            <div class="card-body">
                <p><strong>Description:</strong> {{ $intervention->description }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($intervention->intervention_date)->format('d-m-Y H:i') }}</p>
                <p><strong>Code:</strong> {{ $intervention->code }}</p>
                <p><strong>User:</strong> {{ $intervention->user ? $intervention->user->name : 'N/A' }}</p>
            </div>
        </div>

        <h2>Steps</h2>
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Duration</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            @foreach($intervention->steps as $step)
                <tr>
                    <td>{{ $step->name }}</td>
                    <td>{{ $step->description }}</td>
                    <td>{{ $step->duration }} minutes</td>
                    <td>
                        @guest
                        @if($step->status == 0)
                            <span class="text-red-500">Not Started</span>
                        @elseif($step->status == 1)
                            <span class="text-blue-500">In Progress</span>
                        @else
                            <span class="text-green-500">Work Completed</span>
                        @endif
                        @endguest
                        @auth
                            @if(Auth::user()->is_admin)
                                <select onchange="updateStatus({{ $step->id }}, this.value); setColor(this);" style="color: {{ $step->status == 0 ? 'red' : ($step->status == 1 ? 'blue' : 'green') }};">
                                    <option value="0" {{ $step->status == 0 ? 'selected' : '' }} style="color: red;">Not Started</option>
                                    <option value="1" {{ $step->status == 1 ? 'selected' : '' }} style="color: blue;">In Progress</option>
                                    <option value="2" {{ $step->status == 2 ? 'selected' : '' }} style="color: green;">Work Completed</option>
                                </select>
                            @endif
                        @endauth
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function updateStatus(stepId, status) {
            fetch(`/steps/${stepId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
                .then(response => response.text())
                .then(text => {
                    return JSON.parse(text);
                })
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
        }

        function setColor(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            selectElement.style.color = selectedOption.style.color;
        }

        document.querySelectorAll('select').forEach(select => {
            setColor(select);
        });
    </script>
@endsection
