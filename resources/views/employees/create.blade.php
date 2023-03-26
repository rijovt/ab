@extends('layouts.app', ['page' => __('Employee Management'), 'pageSlug' => 'employees', 'section' => 'production'])

@section('content')
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0"></h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('employees.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('employees.store') }}" autocomplete="off">
                            @csrf

                            <h6 class="heading-small text-muted mb-4">{{ __('employee information') }}</h6>
                            <div class="pl-lg-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required autofocus>
                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>
                                <div class="form-group{{ $errors->has('designation') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" for="input-designation">{{ __('Job Title') }}</label>
                                    <input type="designation" name="designation" id="input-designation" class="form-control form-control-alternative{{ $errors->has('designation') ? ' is-invalid' : '' }}" placeholder="{{ __('Job Title') }}" value="{{ old('designation') }}" required>
                                    @include('alerts.feedback', ['field' => 'designation'])
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
