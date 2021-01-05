@extends('layouts.main')
@section('title','Guardians | '. config("app.name"))
@section('guardians','kt-menu__item--open')

@section('content')
@include('layouts.partials.breadcrumbs', [
        'breadTitle' => 'Guardians',
        'crumbs' => [
            [
                'name' => 'Guardians',
                'url' => '/guardians'
            ],
        ]
    ])
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
    

    <!--Begin::Dashboard 3-->

    <!--Begin::Row-->
    <div class="row">
       
        <div class="col-xl-12 col-lg-12 order-lg-2 order-xl-1">

            <!--begin:: Widgets/Blog-->
            <div class="kt-portlet kt-portlet--height-fluid kt-widget19">
                <div class="kt-portlet__body">
                    <h1>Guardians Here</h1>
                </div> 
        </div>
     
    </div>

    <!--End::Row-->

    <!--End::Dashboard 3-->
</div>
@endsection