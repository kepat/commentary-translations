<div class="row">
    <div class="col-lg-12">
        @if (isset($user))
            <h1 class="page-header">{{ $user->last_name . ', ' . $user->first_name }}</h1>
            <input name="edit" id="edit" type="hidden" value="true">
        @else
            <h1 class="page-header">New User</h1>
            <input name="edit" id="edit" type="hidden" value="false">
        @endif
    </div>
</div>

@include('elements.message')

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-edit"></i>
                Users Details
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('first_name') !!}
                            {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'Enter First Name']) !!}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('last_name') !!}
                            {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Last Name']) !!}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('username') !!}
                            {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'Enter Username']) !!}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            {!! Form::label('email') !!}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'email@example.com']) !!}
                            </div>
                        </div>
                    </div>
                    @if (! isset($user))
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! Form::label('password') !!}
                                {!! Form::password('password', ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! Form::label('password_confirmation') !!}
                                {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    @endif
                    <div class="col-lg-12">
                        <div class="form-group">
                            {!! Form::label('role') !!}
                            @if (isset($user))
                                {!! Form::select('role_id', $roles, $user->role->id, ['class' => 'form-control', 'id' => 'role']) !!}
                            @else
                                {!! Form::select('role_id', $roles, null, ['class' => 'form-control', 'id' => 'role']) !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-12" id="driver-panel">
                        <div class="form-group">
                            {!! Form::label('driver code') !!}
                            <div class="input-group">
                                @if (isset($user) && isset($user->driver->id))
                                    {!! Form::text('driver_code', $user->driver->code, ['class' => 'form-control', 'id' => 'driver_code', 'placeholder' => 'Enter Driver Code', 'disabled' => 'disabled']) !!}
                                @else
                                    {!! Form::text('driver_code', null, ['class' => 'form-control', 'id' => 'driver_code', 'placeholder' => 'Enter Driver Code', 'disabled' => 'disabled']) !!}
                                @endif
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" data-toggle="modal"
                                            data-target="#table_modal"
                                            data-model="driver_table" data-title="Driver List">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12" id="customer-panel">
                        <div class="form-group">
                            {!! Form::label('customer code') !!}
                            <div class="input-group">
                                @if (isset($user) && isset($user->customer->id))
                                    {!! Form::text('customer_code', $user->customer->code, ['class' => 'form-control', 'id' => 'customer_code', 'placeholder' => 'Enter Customer Code', 'disabled' => 'disabled']) !!}
                                @else
                                    {!! Form::text('customer_code', null, ['class' => 'form-control', 'id' => 'customer_code', 'placeholder' => 'Enter Customer Code', 'disabled' => 'disabled']) !!}
                                @endif
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" data-toggle="modal"
                                            data-target="#table_modal"
                                            data-model="customer_table" data-title="Customer List">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
@include('elements.modal-table')