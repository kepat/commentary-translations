@extends('layouts.main')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1>Users</h1>
        </div>
    </div>

    @include('elements.message')

    <div class="row">
        <div class="col-lg-2 col-md-6">
            <div class="panel panel-green">
                <a href="{{ route('users.create') }}">
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
                    User List
                </div>

                <div class="panel-body">
                    <div class="panel-body-body">

                        <div class="row row-margin">
                            <div class="col-sm-6">
                                Show
                                <select id="record_size" class="input-sm body-white">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                                entries
                            </div>

                            <div class="col-sm-6">
                                <div class="float-right">
                                    Search:
                                    <input id="record_search" type="search" class="border-solid input-sm"
                                           placeholder="Search" aria-controls="users_table">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-hover table-margin">
                                    <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Full Name</th>
                                        <th>Driver/Customer</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="table-body">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div id="record_details" class="col-sm-6 display-entries-margin">
                            </div>
                            <div class="col-sm-6">
                                <div id="record_navigation" class="float-right">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

@stop

@section('script')
    @include('users.script-index')
@stop