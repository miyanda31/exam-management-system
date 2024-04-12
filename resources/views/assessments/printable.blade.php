<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dashboard | School Report</title>
    <link href="{{public_path('/css/print.css')}}" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
        }
    </style>
</head>
<body>
    <table class="table table-borderless">
        <tr>
            <th colspan="10" class="panel-title text-center text-uppercase">School report card</th>
        </tr>
        <tr>
            <th class="col-2">
                <img style="width: 48px;" class="img-responsive pull-right" alt="{{$school->about['logo']}}" src="{{public_path('img/'.$school->about['logo'])}}">
            </th>
            <th class="col-md-10 text-center">
                <h4 class="panel-title text-uppercase">{{$school->about['name']}}</h4>
                <p><b><small><em>{{$school->about['motto']}}</em></small></b></p>
                <p>{{$school->about['address']}} <br>
                    <span class="text-left">
                                <strong>Phone: </strong> {{$school->about['phone']}} <br>
                                <strong> {{$school->about['email']}}</strong> <br>
                            </span>
                </p>
            </th>


        </tr>
    </table>
    <hr>
    <table class="table table-borderless">
        <tr >
            <td class="col-md-4">
                <p class="text-muted">
                    <strong>Name: </strong>{{$user->name}}<br>
                    <strong>Form:</strong>  {{$form->number.$form->name}}
                    <br>
                    <strong>Gender: </strong>{{$user->gender}}
                </p>
            </td>
            <td class="col-md-4">
                <p class="text-muted"><strong>Attendance:</strong> {{100 - round((\App\Attendance::whereUserId($user->id)->whereTermId($term->id)->whereMetaKey('attendance')->count()/\Carbon\Carbon::parse($term->opening)->diffInDaysFiltered(function (\Carbon\Carbon $date){return $date->isWeekday();},$term->closing))*100,0)}}%
                    <br>
                    <strong>Term:</strong> {{$term->number}} <br>
                    <strong>Academic Year:</strong> {{$term->calendar->academic}}
                </p>
            </td>
            <td class="col-md-4">

                <p class="text-muted"><strong>Class Teacher:</strong>  {{substr($form->users->fname,0,1).'. '.$form->users->lname}}
                    <br>
                    <strong>Enrollment: {{$form->enrollment}}</strong> <br>
                </p>
            </td>
        </tr>
    </table>

    <table class="table table-bordered">
        <thead>
        <tr class="borders">
            <th style="padding: 8px;"></th>
            @if ($type->type == 'mock')
                <th style="padding: 8px;">P1</th>
                <th>P2</th>
                <th>P3</th>
            @else
                <th style="padding: 8px;">C1</th>
                <th>C2</th>
                <th>ET</th>
            @endif
            <th style="padding: 8px;">SCORE</th>
            <th>GRADE</th>
            <th>POS</th>
            <th>REMARK</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($grades as $gr)
            <tr class="borders">
                <th style="padding: 8px;">{{$gr->subject->name}}</th>
                @if ($type->type == 'mock')
                    <td>{{$gr->scores['P1']}}</td>
                    <td>{{$gr->scores['P2']}}</td>
                    <td>{{$gr->scores['P3']}}</td>
                @else
                    <td>{{$gr->scores['first']}}</td>
                    <td>{{$gr->scores['second']}}</td>
                    <td>{{$gr->scores['final']}}</td>
                @endif
                <td>{{$gr->score}}</td>
                <td>{{$gr->grading?$gr->grading->grade:''}}</td>
                <td>{{$gr->position}}</td>
                <td>{{$gr->grading?$gr->grading->remark:''}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
    <table class="table-borderless table">
        <tr>
            <th class="col-md-4 col-sm-4 col-xs-4"><strong>Pos: </strong>{{$score->position}}</th>
            <th class="col-md-4 col-sm-4 col-xs-4"><strong>Agg: </strong>{{$score->aggregate}}</th>
            <th class="col-md-4 col-sm-4 col-xs-4"><strong>Remark: </strong>{{$score->aggregate<=36?'Pass':'Fail'}}</th>
        </tr>

    </table>
    <hr>
    <table class="table-borderless table">
        <thead>
        <tr>
            <th class="col-md-6">
                <h4>H/T Remarks:</h4>
                @php($comment = \App\Comment::wherePostId($user->id)->whereOrigin($term->calendar->academic.'/'.$term->number)->whereHas('headteacher')->first())
                <p>{{$comment?$comment->body:''}}</p>
            </th>
            <th class="col-md-6">
                <h4><strong>C/T Remarks:</strong></h4>
                @php($comment = \App\Comment::wherePostId($user->id)->whereOrigin($term->calendar->academic.'/'.$term->number)->whereHas('classteacher')->first())

                <p>
                    {{$comment?$comment->body:''}}
                </p>
            </th>
        </tr>
        </thead>

    </table>
    <table class="table-borderless table">
        <thead>
        <tr>
            <th colspan="2">Grading System</th>
            <th>Payments</th>
        </tr>
        <tr>
            <td>
                @foreach (\App\Grading::orderBy('grade')->get() as $index =>$grade)
                    @if ($index<4)
                        {{$grade->min.' - '.$grade->max}} &nbsp;&nbsp;{{$grade->grade}} &nbsp;{{$grade->remark}}
                        <br>
                    @endif

                @endforeach
            </td>
            <td>
                @foreach (\App\Grading::orderBy('grade')->get() as $index =>$grade)
                    @if ($index>=4)
                        {{$grade->min.' - '.$grade->max}} &nbsp;&nbsp;{{$grade->grade}} &nbsp;{{$grade->remark}}
                        <br>
                    @endif

                @endforeach
            </td>
            <td>
                @foreach (\App\Payment::whereHas('form',function ($q) use($form){$q->whereId($form->id);})->get() as $fee)
                    <strong>{{$fee->name}}</strong>: K{{number_format($fee->package->sum('amount'),2,'.',',')}} <br>
                @endforeach
            </td>
        </tr>
        </thead>
    </table>
</body>
</html>
