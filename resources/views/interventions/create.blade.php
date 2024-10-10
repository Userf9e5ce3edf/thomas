@extends('layouts.interventionLayout')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Intervention') }}</div>

                    <div class="card-body">
                        <form id="intervention-form" method="POST" action="{{ route('interventions.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="title">{{ __('Title') }}</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autofocus>

                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">{{ __('Description') }}</label>
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" required>{{ old('description') }}</textarea>

                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="date">{{ __('Date') }}</label>
                                <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="intervention_date" value="{{ old('intervention_date') }}" required>

                                @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{ __('Steps') }}</label>
                                <button class="btn btn-outline-secondary add-step-btn mb-3" type="button">+</button>
                                <div id="steps-container" style="display: none;">

                                </div>
                            </div>

                            <!-- Hidden input to store the formatted date -->
                            <input type="hidden" id="formatted-date" name="formatted_date">

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let stepIndex = 0;

            document.querySelector('.add-step-btn').addEventListener('click', function () {
                const stepsContainer = document.getElementById('steps-container');
                if (stepsContainer.style.display === 'none') {
                    stepsContainer.style.display = 'block';
                } else {
                    addStep(stepsContainer, stepIndex);
                    stepIndex++;
                }
            });

            function addStep(container, index) {
                const newStep = document.createElement('div');
                newStep.classList.add('step-block', 'mb-3');
                newStep.innerHTML = `
                <input type="text" name="steps[${index}][name]" class="form-control mb-2" placeholder="Step name" required>
                <textarea name="steps[${index}][description]" class="form-control mb-2" placeholder="Step description" required></textarea>
                <input type="number" name="steps[${index}][duration]" class="form-control mb-2" placeholder="Duration in minutes" required>
                <button class="btn btn-outline-secondary remove-step-btn" type="button">-</button>
            `;
                container.appendChild(newStep);

                newStep.querySelector('.remove-step-btn').addEventListener('click', function () {
                    container.removeChild(newStep);
                    if (container.children.length === 0) {
                        container.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endsection
