@extends('layouts.main')
@section('title','Feedbacks | '. config("app.name"))
@section('feedbacks','kt-menu__item--open')

@section('content')
    @include('layouts.partials.breadcrumbs', [
        'breadTitle' => __("Feedbacks"),
        'crumbs' => [
            [
                'name' => __("Feedbacks"),
                'url' => '/feedbacks'
            ],
        ]
    ])

    <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        @include('layouts.partials.flash-message')
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand flaticon2-list-2"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        {{ __('Feedbacks') }}
                    </h3>
                </div>
                @if (!auth()->user()->hasRole('Principal'))
                    <div class="kt-portlet__head-toolbar">
                        <div class="kt-portlet__head-wrapper">
                            <div class="kt-portlet__head-actions">
                                
                                <a href="/feedbacks/create" class="btn btn-brand btn-elevate btn-icon-sm">
                                    <i class="la la-plus"></i>
                                    {{ __('Add') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="kt-portlet__body">
                <table id="newaTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if (auth()->user()->hasRole('Principal'))<th>{{ __('Feedback by') }}</th>@endif
                            <th>{{ __("Title") }}</th>
                            <th>{{ __("Description") }}</th>
                            <th>{{ __("Image") }}</th>
                            @if (!auth()->user()->hasRole('Principal')) <th>{{ __("Actions") }}</th>@endif
                        </tr>
                    </thead>
                    <tbody>                        
                        @foreach($feedbacks as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                @if (auth()->user()->hasRole('Principal'))<th>{{ $item->user['name'] }} ({{ __($item->user['role']['title']) }})</th>@endif
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->description }}</td>
                                @if ($item->image == null)
                                    <td><p>N/A</p></td>
                                @else
                                    <td>
                                        <a target="_blank" href="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/feedbacks/'.$item->image) }}"><img src="{{ Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/feedbacks/'.$item->image) }}" class="img-thumbnail"></a>
                                    </td>
                                @endif
                                @if (!auth()->user()->hasRole('Principal'))
                                    <td>
                                        <div class="btn-group">
                                            {{-- <a class="btn btn-sm btn-primary btn-icon btn-icon-md" title="View feedback" href="{{ url('/feedbacks/' . $item->id) }}"><i class="fa fa-lg fa-eye"></i></a>&nbsp; --}}
                                            <a class="btn btn-sm btn-info btn-icon btn-icon-md" href="{{ url('/feedbacks/' . $item->id . '/edit') }}" title="Edit feedback"><i class="fa fa-lg fa-edit"></i></a>&nbsp;
                                            <form method="POST" action="{{ url('/feedbacks' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-sm btn-danger btn-icon btn-icon-md" title="Delete feedback" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash" ></i> </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ global_asset('assets/plugins/custom/datatables/datatables.bundle.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ global_asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script type="text/javascript">
        $(document).ready( function () {
            $('#newaTable').DataTable({
                "oLanguage": {

                "sSearch": "{{ __('Search') }}"

                }
            });
        } );
    </script>
@endpush