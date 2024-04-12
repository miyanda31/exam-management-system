@extends('layouts.main')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    <h3></h3>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="pull-left">
                                        <small class="text-dark">Form Teacher:

                                        </small>
                                    </h4>
                                    <h4 class="fa-pull-right"><small>Candidature:
                                            <br>
                                            Term:
                                        </small>
                                    </h4>
                                </div>

                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-center">End of Term Results </h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Agg</th>
                                                <th>Class Ps</th>
                                                <th>Stream Ps</th>
                                                <th>Score</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>
                                                    </td>
                                                    <td>{{$user->gender}}</td>
                                                    <td>{{$user->score->aggregate}}</td>
                                                    <td>{{$user->score->class_position}}</td>
                                                    <td>{{$user->score->position}}</td>
                                                    <td>{{$user->score->score}}</td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                        <div class="text-center">{{$users->links()}}</div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop
