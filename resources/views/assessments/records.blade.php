@extends('layouts.dashboard')

@section('bread')
    @component('components.bread')
        @slot('title')
            Wards
        @endslot

        @slot('icon')
            user
        @endslot
    @endcomponent
@stop
@section('content')
    <div class="row">
        <div class="col-md-3 visible-md visible-sm visible-lg">
            <div class="panel widget">
                <div class="widget-header bg-primary">
                </div>
                <div class="widget-body text-center">
                    <img alt="Profile Picture" class="widget-img img-border-light" src="/img/{{$ward->avatar}}"">
                    <h4 class="mar-no">{{$ward->fname.' '.$ward->lname}}</h4>
                    <p class="text-muted mar-btm">Form:  @foreach ($ward->form as $form)
                            {{$form->number.$form->name}}
                        @endforeach</p>
                </div>
                <!--===================================================-->
            </div>
        </div>
        <div class="col-md-9">
            @forelse ($terms as $term)
                <a href="{{route('reports.show',[$ward->username,'t'=>$term->term->id])}}" class="col-md-4 col-sm-6 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-9 col-sm-9 col-xs-10">
                                    <h3 class="mar-no"> <span class="counter">Term {{$term->term->number}}</span></h3>
                                    <p class="mar-ver-5">{{$term->term->calendar->academic}} </p>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-2"> <i class="fa fa-layer-group fa-2x text-info"></i> </div>
                            </div>
                            <div class="progress progress-striped progress-sm">
                                <div style="width: 100%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar"></div>
                            </div>
                            <p>School Report</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="panel panel-body">
                    <div class="media" style="padding: 15%;">
                        <div class="media-left">
                            <a href="#">
                                <img style="width: 64px;" class="media-object" alt="error" src="/img/marker.png">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">No records found</h4>
                            No records were found for this ward. Please be patient as teachers upload the data. You will be notified when results are submitted. Thank You
                        </div>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
@stop
