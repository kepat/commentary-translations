@extends('layouts.main')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1>Posts</h1>
        </div>
    </div>

    @include('elements.message')

    <div class="row">
        <div class="col-lg-2 col-md-6">
            <div class="panel panel-green">
                <a href="">
                    <div class="panel-heading">
                        <span class="pull-left">Create</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Post List
                </div>

                <div class="panel-body">
                    
                    <div class="table-responsive dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Languages</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            @foreach($posts as $post) 

                                <tr>
                                    <td>{{ $post->title }}</td>
                                    <td>
                                        @foreach($post->translations as $translation)
                                            - {{ $translation->language }}
                                        @endforeach
                                        -
                                    </td>
                                    <td>
                                         <a href="posts/{{$post->id}}" class="button-margin">
                                            <button type="button" class="btn btn-default"><span class="pull-left btn-icon"><i class="fa fa-file"></i></span>View</button>
                                        </a>
                                    </td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

@stop

@section('script')

<script>

    $(document).ready(function() {
        $('#dataTables').DataTable({
                responsive: true
        });
    });

</script>

@stop