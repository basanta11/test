@extends('layouts.main')

@section('content')
    <div class="card card-success card-outline">
        <div class="card-body box-profile">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>{{ __("ID") }}</th><td>{{ $feedback->id }}</td>
                        </tr>
                        <tr><th> {{ __("Title") }} </th><td> {{ $feedback->title }} </td></tr><tr><th> {{ __("Description") }} </th><td> {{ $feedback->description }} </td></tr><tr><th> {{ __("Image") }} </th><td> {{ $feedback->image }} </td></tr>
                    </tbody>
                </table>
            </div>

            <a href="{{ url('/feedbacks/' . $feedback->id . '/edit') }}" class="btn btn-primary btn-block"><b>{{ __('WocAdmin.edit')}}</b></a>
        </div>
    </div>
@endsection
