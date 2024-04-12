@extends('layouts.dashboard')

@section('bread')
    @component('components.bread')
        @slot('title')
            Records
        @endslot

        @slot('icon')
            pencil-alt
        @endslot
    @endcomponent
@stop

@section('content')
    @if ($terms->count()>0)
        <div id="terms" class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Available School Records</h3>
                    </div>

                </div>
            </div>
            @foreach ($terms as $term)

                <a onclick="loadAwards({{$term->term_id}},{{$term->form}})" id="term" href="javascript:void(0)" class="col-md-3 col-sm-6 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-9 col-sm-9 col-xs-10">
                                    <h3 class="mar-no"> <span class="counter">Class {{$term->form->number.$term->form->name}}</span></h3>
                                    <p class="mar-ver-5"> Term {{$term->term->number}}</p>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-2"> <i class="fa fa-layer-group fa-3x text-info"></i> </div>
                            </div>
                            <div class="progress progress-striped progress-sm">
                                <div style="width:100%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar"> <span class="sr-only">60% Complete</span> </div>
                            </div>
                            <p> Awards for Term {{$term->term->number}} - {{$term->term->calendar->academic}} </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div id="awards" class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <button id="restore" data-toggle="dropdown" class="dropdown-toggle btn btn-info">
                                <i class="fa fa-long-arrow-alt-left fa-lg"></i>
                            </button>
                            Top Ten Awards
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Agg</th>
                                </tr>
                                </thead>
                                <tbody id="scores">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-mint">
                <div class="panel-heading ui-sortable-handle">
                    <h3 class="panel-title">No Records</h3>
                </div>
                <div class="panel-body">
                    <div class="media" style="padding: 15%">
                        <div class="media-left">
                            <a href="#">
                                <img width="64px" class="media-object"  alt="64x64" src="/img/marker.png">
                            </a>
                        </div>
                        <div class="media-body">
                            <p>There are no recent records of school reports in the database. This could be because you have migrated to a new term or the database is being uodated</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



@stop
@section('footer')

    <script src="{{asset('js/server.js')}}"></script>
@stop
