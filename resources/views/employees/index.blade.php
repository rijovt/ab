@extends('layouts.app', ['page' => __('Employees'), 'pageSlug' => 'employees', 'section' => 'production'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">{{ __('Employees') }}</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary">{{ __('Create Employee') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')

                    <div class="">
                        <table class="table tablesorter " id="">
                            <thead class=" text-primary">
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Job Title') }}</th>
                                <th scope="col">{{ __('Created By') }}</th>
                                <th scope="col">{{ __('Creation Date') }}</th>
                                <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->designation }}</td>
                                        <td>{{ $employee->user->name }}</td>
                                        <td>{{ $employee->created_at ? $employee->created_at->format('d/m/Y H:i'):'' }}</td>
                                        <td class="text-right">
                                            @if (auth()->user()->username == 'admin')
                                                <div class="dropdown">
                                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="tim-icons icon-settings-gear-63"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                        @if (auth()->user()->username == 'admin')
                                                            <form action="{{ route('employees.destroy', $employee) }}" method="post">
                                                                @csrf
                                                                @method('delete')

                                                                <a class="dropdown-item" href="{{ route('employees.edit', $employee) }}">{{ __('Edit') }}</a>
                                                                <button type="button" class="dropdown-item" onclick="confirm('{{ __('Are you sure you want to delete this employee?') }}') ? this.parentElement.submit() : ''">
                                                                            {{ __('Delete') }}
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a class="dropdown-item" href="{{ route('profile.edit', $employee->id) }}">{{ __('Edit') }}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        {{ $employees->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
