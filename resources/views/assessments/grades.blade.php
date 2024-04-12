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
<body>
<h4 class="h3 text-center">{{'Form '.$form->number.$form->name.' Marksheet'}}</h4>
<table class="table table-bordered table-sm">
    @php(error_reporting(0))
    <thead>
    <tr>
        <th style="font-weight: bold ">#</th>
        <th  style="font-weight: bold ">Full Name</th>
        @foreach ($subjects as $subject)
            <th style="font-weight: bold ">{{$subject->short_code}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach ($users as  $index=> $user)
        <tr  class="borders">
            <td style="padding: 8px;">{{$index+1}}</td>
            <td style="padding: 8px;">{{$user->name}}</td>
            @foreach ($subjects as $subject)
                <td style="padding: 8px;">
                @foreach ($user->grades as $grade)
                    @if ($grade->allocation->subject_id === $subject->id)
                        @if (request()->c == 'Score')
                           {{$grade->score}}
                        @else
                            {{$grade->grade}}
                        @endif
                    @endif
                @endforeach
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>

