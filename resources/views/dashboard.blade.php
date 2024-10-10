@extends('layouts.interventionLayout')

@section('content')
    <div class="container">
        <form action="{{ route('interventions.showByCode') }}" method="GET">
            <div class="form-group">
                <label for="code">Intervention Code</label>
                <input type="text" id="code" name="code" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
@endsection

