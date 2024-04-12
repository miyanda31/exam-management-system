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
    @if ($wards->count()>0)
        <div >
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Select A Ward</h3>
                    </div>
                </div>
            </div>

            @foreach ($wards as $ward)
                <a href="{{route('reports.show',[$ward->username,'r'=>1])}}" class="col-lg-3 col-sm-12 col-md-6 col-xs-12 eq-box-sm">
                    <div class="panel text-center">
                        <div class="panel-body bg-primary">
                            <img alt="Avatar" class="img-lg img-circle img-border mar-ver" src="/img/{{$ward->avatar}}">
                            <h4>{{$ward->name}}</h4>
                            <p>Form {{$ward->form[0]->number.' '.$ward->form[0]->name}}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-mint">
                <div class="panel-heading ui-sortable-handle">
                    <h3 class="panel-title">No wards</h3>
                </div>
                <div class="panel-body">
                    <div class="media" style="padding: 15%">
                        <div class="media-left">
                            <a href="#">
                                <img width="64px" class="media-object"  alt="64x64" src="/img/marker.png">
                            </a>
                        </div>
                        <div class="media-body">
                            <p>No wards have been assigned to you yet. Please be patient as the administrators update the database</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


@stop
