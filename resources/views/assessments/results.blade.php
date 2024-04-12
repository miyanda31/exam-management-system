@extends('layouts.dashboard')

@section('bread')
    @component('components.bread')
        @slot('title')
            Class List
        @endslot

        @slot('icon')
            users-cog
        @endslot
    @endcomponent
@stop
@section('content')
    <div class="col-md-8 col-md-offset-2 visible-lg visible-sm visible-md">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Form {{$allocation->number.' '.$allocation->name}} Term {{$term->number}} Assessment</h3>
            </div>
            <div class="panel-body">
                <div class="mail-sender">
                    <div class="media">
                        <a href="#" class="pull-left"> <img alt="" src="/img/{{$allocation->users->avatar}}" class="media-object"> </a> <span class="media-meta pull-right">Candidature: {{$students}}</span>
                        <h5>
                            {{$allocation->users->fname.' '.$allocation->users->lname}}
                        </h5>
                        <small class="text-muted">Phone: {{$allocation->users->phone}}</small>
                    </div>
                </div>
                @if ($grades->count()>0)
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-center">{{ucfirst(request()->assessment)}} Continuous Assessment</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>CA1</th>
                                                <th>CA2</th>
                                                <th>CA3</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($grades as $grade)
                                                <tr>
                                                    <td>{{$grade->user->fname.' '.$grade->user->lname}}</td>
                                                    @foreach ($grade->scores as $index => $score)
                                                        <td>{{$score}}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                @else

                        <div style="padding: 15%" class="media">
                            <span class="pull-left"><img src="/img/marker.png" alt=""></span>
                            <div class="media-body">
                                <div class="h4">No Records Found</div>
                                <p>No records matched any of your wards in the selected assessment. Please be patient as teachers upload data
                                </p>
                            </div>
                        </div>
                @endif

            </div>
        </div>
    </div>
    @forelse ($grades as $grade)
        <div class="col-xs-12 visible-xs">
            <div class="userWidget-1">
                <div class="avatar bg-info">
                    <img src="/img/{{$grade->user->avatar}}" alt="avatar">
                    <div class="name osLight">{{$grade->user->fname.' '.$grade->user->lname}}</div>
                </div>
                <div class="title"> Student </div>
                <div class="address"> {{$grade->user->email}}</div>
                <ul class="fullstats">
                    <li> <span>{{$grade->form->number.$grade->form->name}}</span>Form </li>
                    <li> <span>{{$grade->subject->name}}</span>Subject </li>
                    <li> <span>{{$grade->term->number}}</span>Term </li>
                </ul>
                <div class="clearfix"> </div>
            </div>

            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-layer-group"> </i> Continuous Assessment</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <tbody>
                        @foreach ($grade->scores as $index => $score)
                            <tr>
                                <th>{{ucfirst($index)}}</th>
                                <td>{{$score}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @empty
        <div class="panel panel-body col-xs-12 visible-xs">
            <div style="padding: 15%" class="media">
                <span class="pull-left"><img src="/img/marker.png" alt=""></span>
                <div class="media-body">
                    <div class="h4">No Records Found</div>
                    <p>No records matched any of your wards in the selected assessment. Please be patient as teachers upload data
                    </p>
                </div>
            </div>
        </div>
    @endforelse

@stop

