@extends('layouts.main')

@section('content')
    <div class="section ">
        <div class="container">
            <div class="row">
                <div class="col-md-offset-2 col-md-7">
                    <h3 class="text-center"><small>Class List For End of Term Exams</small></h3>
                    <form  class="form form-newsletter col-md-6 fa-pull-right" method="" action="">

                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search name"></div>

                        <button type="button" class="btn btn-info btn-sm btn-round" name="button">
                            <i class="fa fa-search"></i>
                        </button>

                    </form>


                    <div class="row">
                        <div class="col-md-12">
                            @if($classes->count()>0)
                                <div  class="table-responsive">
                                    <table class="table table-striped table-sm">
                                        <thead>
                                        <tr>
                                            <th>Class</th>
                                            <th>Term</th>
                                            <th >Year</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($classes as $results)
                                            <tr>
                                                <td><a href="{{route('reports.show',['form'=>$results->form->id,'t'=>$results->term->id])}}">{{$results->form->number.$results->form->name}}</a></td>
                                                <td>{{$results->term->number}}</td>
                                                <td>{{$results->term->calendar->academic}}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                    <div class="text-center">{{$classes->links()}}</div>
                                </div>
                            @else
                                <h3 class="text-center"><small>No results have been upload for this selection</small></h3>
                                <img src="{{asset('img/404.png')}}" class="img-responsive">
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
