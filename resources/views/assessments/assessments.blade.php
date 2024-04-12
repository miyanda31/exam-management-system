@extends('layouts.dashboard')

@section('content')
    <h3 class="text-center h3">Form {{$allocation->number.' '.$allocation->name}} Term {{$term->number}} Assessment</h3>

    <div class="col-md-8 col-md-offset-2 visible-lg visible-sm visible-md">
        <div class="panel">
            <div class="panel-heading">
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
                @if ($grades)
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-center">{{ucfirst(request()->assessment)}} Continuous Assessment</h4>
                                    <div>
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                @foreach ($grades->scores as $index => $score)
                                                    <th>{{ucfirst($index)}}</th>
                                                @endforeach
                                                <th>Grade</th>
                                                <th>POS</th>
                                                <th>Remark</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{$grades->user->fname.' '.$grades->user->lname}}</td>
                                                @foreach ($grades->scores as $index => $score)
                                                    <td>{{$score}}</td>
                                                @endforeach
                                                <td>{{$grades->grading->grade}}</td>
                                                <td>{{$grades->position}}</td>
                                                <td>{{$grades->grading->remark}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                @endif



            </div>
        </div>
    </div>
    @if ($grades)
    <div class="col-xs-12 visible-xs">
        <div class="userWidget-1">
            <div class="avatar bg-info">
                <img src="/img/{{$grades->user->avatar}}" alt="avatar">
                <div class="name osLight">{{$grades->user->fname.' '.$grades->user->lname}}</div>
            </div>
            <div class="title"> Student </div>
            <div class="address"> {{$grades->user->email}}</div>
            <ul class="fullstats">
                <li> <span>{{$grades->form->number.$grades->form->name}}</span>Form </li>
                <li> <span>{{$grades->subject->name}}</span>Subject </li>
                <li> <span>{{$grades->term->number}}</span>Term </li>
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
                    @foreach ($grades->scores as $index => $score)
                        <tr>
                            <th>{{ucfirst($index)}}</th>
                            <td>{{$score}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th>Score</th>
                        <td>{{$grades->score}}</td>
                    </tr>
                    <tr>
                        <th>Aggregate</th>
                        <td>{{$grades->grading->grade}}</td>
                    </tr>
                    <tr>
                        <th>Position</th>
                        <td>{{$grades->position}}</td>
                    </tr>
                    <tr>
                        <th>Remark</th>
                        <td>{{$grades->grading->remark}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
    @if (!$grades)
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-body">
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object"  src="/img/marker.png">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">No Results</h4>
                            Records have not been updated for this subject yet. Please be patient as teachers make changes to the database
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif
@stop

