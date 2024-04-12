<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>School Reports</title>
    <style>
        @page  {
            margin: 30px;
            padding: 30px;
        }
        html {
            -webkit-text-size-adjust: none; /* Prevent font scaling in landscape */
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            -moz-font-smoothing: antialiased;
        }

        body {
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            width: 100%;
            min-height: 100%;
            color: #031e23;
            width: 100%;
            height: 100%;
            background: #ffffff;
        }

        img {
            border: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
            padding: 0;
            font-weight: 700;
            color: #202342;
            font-family: 'Inter', sans-serif;
        }
        .h1{
            font-size: 40px;
            font-weight: 700;
            line-height: 1.3;
            font-family: 'Inter', sans-serif;
            color: #1b3133;
        }
        .h2{
            font-size: 28px;
            font-weight: 700;
            line-height: 1.35;
            font-family: 'Inter', sans-serif;
            color: #1b3133;
        }
        .h3{
            font-size: 24px;
            font-weight: 700;
            line-height: 1.5;
            color: #1b3133;
            font-family: 'Inter', sans-serif;
        }

        img {
            border: 0;
            vertical-align: top;
            max-width: 100%;
            height: auto;
        }
        p {
            margin: 0 0 15px 0;
            padding: 0;
        }
        .table thead th{
            font-weight: 600;
            font-size: 15px;
            border-bottom: 0;
            padding-left: 1rem;
        }
        .table td, .table th{
            vertical-align: middle;
        }
        .table td{
            font-size: 16px;
            font-weight: 400;
            padding: 1rem
        }
        .table-striped tbody tr:nth-of-type(odd){background: #eaeef2;}


        table {
            border-collapse: collapse;
        }


        th {
            text-align: inherit;
        }


        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            /*border: 1px solid #212529;*/
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #212529;
        }

        .table tbody + tbody {
            border-top: 2px solid #212529;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid #212529;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #212529;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .table-borderless th,
        .table-borderless td,
        .table-borderless thead th,
        .table-borderless tbody + tbody {
            border: 0;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }


        .text-uppercase {
            text-transform: uppercase !important;
        }


    </style>
</head>
<body style="padding: 0; margin: 0">

@php(error_reporting(0))
@foreach ($users as $user)
    @php($performance  = \App\Models\Grade::whereHas('allocation',function ($s)use($term){
    $s->whereTermId($term->id)->where('subject_id', '<>',10);
})->whereUserId($user->id)->where('score','>',$fail->max)->take(5) ->orderByDesc('score')->get())

<h3 class="text-center h3">SCHOOL PROGRESS REPORT</h3>
<table class="table table-sm table-borderless">
    <tr >
        <td  style="width: 33.3%" >
            <table style="padding: 0; margin: 0" class="table-borderless table table-sm">
                <tr>
                    <td>
                        <b>Name: </b>{{$user->name}}
                    </td>
                </tr>
                <tr><td><span style="font-weight: bold">ID: </span> {{$user->student_id}}</td></tr>
                <tr><td><span style="font-weight: bold">Form:</span> {{$form->number}}{{$form->name}}</td></tr>
            </table>
        </td>
        <td class="text-center"  style="width: 33.3%">
                <img style="width: 20%" src="img/<?php echo $school->logo??'graduation.png' ?>" alt="">
            <br>
            <br>
            <small><i>{{$school->motto}}</i></small>
        </td>
        <td  style="width: 33.3%;">
            <table class="table-borderless table table-sm text-right">
                    <tr>
                        <td >{{$school->name}}</td>
                    </tr>
                    <tr>
                        <td >{{$school->address}}</td>
                    </tr>
                    <tr>
                        <td>{{$school->phone}}</td>
                    </tr>
                    <tr>
                        <td>{{$school->email}}</td>
                    </tr>

            </table>
        </td>
    </tr>
</table>

<table class="table-borderless table table-sm">
    <tr>
        <td>
            <span style="font-weight: bold">Enrollment:</span> {{$form->enrollment}}
        </td>
        <td class="text-right">
            <span style="font-weight: bold">Term:</span> {{$term->number}} &nbsp; <span style="font-weight: bold">Year:</span> {{date('Y')}}
        </td>
    </tr>
</table>

<table  class="table text-uppercase table-sm table-bordered">

    <tr style="font-family: 'Times New Roman'">
        <th style="padding:2px;"></th>
        @if($form->number < 4)
            <th style="padding:6px;" >CA</th>
            <th style="padding:6px;">EOT</th>
        @else
            <th style="padding:6px;">P1</th>
            <th style="padding:6px;">P2</th>
            <th style="padding:6px;">P3</th>
        @endif
        <th style="padding:6px;">SCORE</th>
        <th style="padding:6px;">AGG</th>
        <th style="padding:6px;">POS</th>
        <th style="padding:6px;">REMARK</th>
    </tr>
    <tbody>
    @foreach ($user->grades as $gr)
        <tr>
            <th style="padding:6px;">{{$gr->allocation->subject->name}}</th>
            @if ($form->number>3)
                <td style="text-align: center; padding:6px;" >{{array_key_exists('P1',$gr->scores)?$gr->scores['P1']:''}}</td>
                <td style="text-align: center; padding:6px;">{{array_key_exists('P2',$gr->scores)?$gr->scores['P2']:''}}</td>
                <td style="text-align: center;padding:6px;">{{array_key_exists('P3',$gr->scores)?$gr->scores['P3']:''}}</td>
            @else
                <td  style="padding:6px; text-align: center">{{array_key_exists('first',$gr->scores)?$gr->scores['first']:''}}</td>
                <td  style="padding:6px; text-align: center">{{array_key_exists('final',$gr->scores)?$gr->scores['final']:''}}</td>
            @endif
            <td style=" text-align: center">{{$gr->score}}</td>
            <td style="text-align: center">{{$gr->grading?$gr->grading->grade:''}}</td>
            <td style="text-align: center">{{$gr->position}}</td>
            <td>{{$gr->grading?$gr->grading->remark:''}}</td>
        </tr>
    @endforeach
    </tbody>

</table>

<table class="table table-sm table-borderless">
    <tr>
        <td>
            <span style="font-weight: bold">Score: </span> @if($user->score) {{$user->score->score}} @endif
        </td>
        <td>
            <span style="font-weight: bold">S/Passed: </span> @if($user->score) {{$user->score->passed}} @endif
        </td>
        <td>
            <span style="font-weight: bold">Agg:  </span>@if($user->score){{$user->score->aggregate}} @endif
        </td>
        <td>
            <span style="font-weight: bold">Class Position:</span> @if($user->score){{$user->score->class_position}}@endif
        </td>
        <td>
            <span style="font-weight: bold">Position:</span> @if($user->score){{$user->score->position}}@endif
        </td>

    </tr>
</table>
<p style="padding: 5px">
    <span style="font-weight: bold">Remarks:</span>
    {{$user->remark}}
</p>
<p style="padding: 5px"><b>Effort & Behavior: </b> __________________________________________________________________________________
</p>
<p style="padding: 5px"> H/Teacher Sign:____________________________________  C/Teacher Sign: _____________________________________</p>

<table class="table table-sm table-borderless">
    <tbody>
    <tr>
        <td style="width: 50%">
            <table class="table table-sm table-borderless">
                <tr>
                    <th colspan="3">Grading System</th>
                </tr>
                @foreach($grading as $sys)
                    <tr>
                        <td style="padding: 0; margin: 0">
                            {{$sys->min}}-{{$sys->max}}
                        </td>
                        <td style="padding: 0; margin: 0">{{$sys->grade}} </td>
                        <td style="padding: 0; margin: 0">{{$sys->remark}}</td>
                    </tr>
                @endforeach
            </table>
        </td>
        <td style="width: 50%;">
            <table class="table table-sm table-borderless">
                <tr >
                    <td><span  style="font-weight: bold">Next Term Opens:</span> @if($nextTerm) {{$nextTerm->opening}} @endif</td>
                </tr>
                <tr>
                    <td><span  style="font-weight: bold">School Fees:</span> K10,000.00</td>
                </tr>
                <tr>
                    <td><span  style="font-weight: bold">Uniform:</span> Blue and Red</td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>

    <div style="page-break-after: always"></div>
@endforeach

</body>
</html>

