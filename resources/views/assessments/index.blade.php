@extends('layouts.dashboard')

@section('bread')
    @component('components.bread')
        @slot('title')
            Assessments
        @endslot

        @slot('icon')
            book
        @endslot
    @endcomponent
@stop


@section('content')
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Continuous Assessments</h3>
            </div>
            <div class="panel-body">
                @if($terms->count()==0)
                <div style="padding: 15%;" class="media">
                    <div class="media-left">
                        <a href="#">
                            <img style="width: 64px;" class="media-object"  alt="error" src="/img/marker.png">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">No Recent Assessments</h4>
                        Assessments have not yet been approved by your teachers. When a teacher
                        uploads any assessment, a notification will be sent to you. If no results appear
                        here, please consult your teacher so that he may rectify your problem
                    </div>
                </div>
                @endif

                @foreach ($terms as $results)
                    <a href="{{Auth::user()->type=='Student'?route('assessments.show',['subject'=>strtolower($results->subject->name),'t'=>$results->term->id,'f'=>$results->form->id]):route('assessments.index',['s'=>strtolower($results->subject_id),'t'=>$results->term_id,'f'=>$results->form_id])}}" class="col-md-4 col-sm-6 col-xs-12">
                        <div class="panel panel-colorful panel-mint">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-9 col-sm-9 col-xs-10">
                                        <h3 class="mar-no"> <span class="counter">{{$results->subject->name}}</span></h3>
                                        <p class="mar-ver-5"> {{$results->form->number.$results->form->name}}</p>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-2"> <i class="fa fa-layer-group fa-3x text-white"></i> </div>
                                </div>
                                <div class="progress progress-striped progress-sm">
                                    <div style="width: 100%;" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar"> <span class="sr-only">60% Complete</span> </div>
                                </div>
                                <p> Term {{$results->term->number}} {{$results->term->calendar->academic}}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
                 <div style="display: block !important;" class="row">
                     <div class="text-center col-md-12x">
                         {{$terms->links()}}
                     </div>
                 </div>

            </div>
        </div>
    </div>
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item active">Choose Year</li>

                @foreach ($years as $year)
                    <li class="list-group-item text-sm"><a href="{{route('assessments.index',['y'=>$year])}}">{{$year}}</a></li>
                @endforeach
            </ul>
        </div>
@stop
