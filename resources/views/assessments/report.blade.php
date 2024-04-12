@extends('layouts.dashboard')

@section('bread')
    @component('components.bread')
        @slot('title')
            Report
        @endslot

        @slot('icon')
            user-graduate
        @endslot
    @endcomponent
@stop

@section('content')
    <div class="col-md-8 col-md-offset-2 visible-lg visible-sm visible-md">
        <div class="panel">
            <div class="panel-body">
                <a href="{{route('files.show',['report','u'=>$user->username,'r'=>$term->id,'f'=>$user->form[0]->id])}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-cloud-download-alt"></i> Download</a>
                <br>
                <div class="col-md-6">
                    <div class="mail-sender">
                        <div class="media">
                            <a href="javascript:void(0)" class="pull-left"> <img class="media-object" alt="{{$user->avatar}}" src="/img/{{$user->avatar}}"> </a>
                            <h5>
                                {{$user->name}}
                            </h5>
                            <small class="text-muted">Form:  @foreach ($user->form as $form)
                                    {{$form->number.$form->name}}
                                @endforeach</small>
                            <br>
                            <small class="text-muted">Gender: {{$user->form[0]->users->gender}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 visible-md visible-lg">
                    <div class="mail-sender">
                        <div class="media">
                            <a href="javascript:void(0)" class="pull-left"> <img alt="" src="/img/{{$user->form[0]->users->avatar}}" class="media-object"> </a>
                            <h5>
                                {{$user->form[0]->users->fname.' '.$user->form[0]->users->lname}}
                            </h5>
                            <small class="text-muted">Email: {{$user->form[0]->users->email}}</small>
                            <br>
                            <small class="text-muted">Phone: {{$user->form[0]->users->phone}}</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th></th>
                               @if ($type->type == 'mock')
                                   <th>P1</th>
                                   <th>P2</th>
                                   <th>P3</th>
                               @else
                                   <th>C1</th>
                                   <th>C2</th>
                                   <th>ET</th>
                               @endif
                                <th>SCORE</th>
                                <th>GRADE</th>
                                <th>POS</th>
                                <th>REMARK</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($grade as $gr)
                                <tr>
                                    <td>{{$gr->subject->name}}</td>
                                    @if ($type->type == 'mock')
                                        <th>{{$gr->scores['P1']}}</th>
                                        <th>{{$gr->scores['P2']}}</th>
                                        <th>{{$gr->scores['P3']}}</th>
                                    @else
                                        <th>{{$gr->scores['first']}}</th>
                                        <th>{{$gr->scores['second']}}</th>
                                        <th>{{$gr->scores['final']}}</th>
                                    @endif
                                    <td>{{$gr->score}}</td>
                                    <td>{{$gr->grading?$gr->grading->grade:''}}</td>
                                    <td>{{$gr->position}}</td>
                                    <td>{{$gr->grading?$gr->grading->remark:''}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-4"><strong>Pos: </strong>{{$score->position}}</div>
                        <div class="col-md-4 col-sm-4 col-xs-4"><strong>Agg: </strong>{{$score->aggregate}}</div>
                        <div class="col-md-4 col-sm-4 col-xs-4"><strong>Remark: </strong>{{$score->aggregate<=36?'Pass':'Fail'}}</div>

                    </div>


                </div>

            </div>
        </div>
    </div>
        <div class="col-md-12 visible-xs">
            <div class="panel panel-body">
                <a href="{{route('files.show',[$user->username,'r'=>$term->id,'f'=>$user->form[0]->id])}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-cloud-download-alt"></i> Download</a>
                <br>
                <div class="mail-sender visible-xs">
                    <div class="media">
                        <a href="javascript:void(0)" class="pull-left"> <img class="media-object" alt="{{$user->avatar}}" src="/img/{{$user->avatar}}"> </a>
                        <h5>
                            {{$user->name}}
                        </h5>
                        <small class="text-muted">Form:  @foreach ($user->form as $form)
                                {{$form->number.$form->name}}
                            @endforeach</small>
                        <br>
                        <small class="text-muted">Gender: {{$user->form[0]->users->gender}}</small>
                    </div>
                </div>
            </div>
        </div>
    <div class="col-md-12 visible-xs">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Assessments</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th></th>
                            @if ($type->type == 'mock')
                                <th>P1</th>
                                <th>P2</th>
                                <th>P3</th>
                            @else
                                <th>C1</th>
                                <th>C2</th>
                                <th>ET</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($grade as $gr)
                            <tr>
                                <td>{{$gr->subject->short_code}}</td>
                                @if ($type->type == 'mock')
                                    <th>{{$gr->scores['P1']}}</th>
                                    <th>{{$gr->scores['P2']}}</th>
                                    <th>{{$gr->scores['P3']}}</th>
                                @else
                                    <th>{{$gr->scores['first']}}</th>
                                    <th>{{$gr->scores['second']}}</th>
                                    <th>{{$gr->scores['final']}}</th>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-mint">
            <div class="panel-heading">
                <h3 class="panel-title">Aggregates</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>SCORE</th>
                            <th>AGG</th>
                            <th>POS</th>
                            <th>REM</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($grade as $gr)
                            <tr>
                                <td>{{$gr->subject->short_code}}</td>
                                <td>{{$gr->score}}</td>
                                <td>{{$gr->grading?$gr->grading->grade:''}}</td>
                                <td>{{$gr->position}}</td>
                                <td>{{$gr->grading?$gr->grading->remark:''}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Scores</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-4"><strong>Pos: </strong>{{$score->position}}</div>
                    <div class="col-md-4 col-sm-4 col-xs-4"><strong>Agg: </strong>{{$score->aggregate}}</div>
                    <div class="col-md-4 col-sm-4 col-xs-4"><strong>Remark: </strong>{{$score->aggregate<=36?'Pass':'Fail'}}</div>

                </div>
            </div>
        </div>
    </div>

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-body">
                <h3 class="text-center">
                    <small>Comments on Performance</small>
                </h3>
                @foreach ($comments as $comment)
                    <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img style="width: 64px;" class="media-object" alt="{{$comment->user->avatar}}" src="/img/{{$comment->user->avatar}}">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">{{$comment->user->fname.' '.$comment->user->lname}} <small>· {{$comment->created_at->diffForHumans()}}</small></h4>
                        {{$comment->body}}
                    </div>
                </div>
                    <hr>
                @endforeach


                <form action="{{route('reports.store',['t'=>$term->id])}}" method="post" class="form">
                    {{csrf_field()}}
                    <div class="form-group label-floating">
                        <label class="control-label"> Comment on performance of {{$user->name}}...</label>
                        <textarea name="body" class="form-control" rows="5"></textarea>
                    </div>
                    @if ($errors->has('body'))
                        <span class="help-block">
                            <span class="text-danger"><strong>{{ $errors->first('body') }}</strong></span>
                        </span>
                    @endif
                    <input type="hidden" name="post" value="{{$user->username}}">

                    <div class="form-group pull-right">
                        <button type="submit" class="btn btn-success">Comment</button>
                    </div>
                </form>
            </div>
        </div>
@stop

