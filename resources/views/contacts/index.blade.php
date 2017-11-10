@extends('layouts.app')

@section('content')
    @include('partials.modals._createContactModal')
    @include('partials.modals._updateContactModal')
    @include('partials.modals._deleteContactModal')

    <div class="container">

        @if(session('successMessage'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-dismissable alert-success">
                        <span class="glyphicon glyphicon-ok"></span> {{ session('successMessage') }}
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <button
                            type="button" 
                            class="btn btn-primary btn-sm pull-right"
                            data-toggle="modal"
                            data-target="#createContactModal">
                            Create Contact
                        </button>
                        <h2>Contact List</h2>
                    </div>
                    <div class="panel-body">
                        
                        <div class="no-contacts text-center {{ ($contacts->isEmpty() ? '' : 'hidden') }}">
                            <h3>You have no contacts!</h3>
                            <button
                                type="button" 
                                class="btn btn-primary btn-lg"
                                data-toggle="modal"
                                data-target="#createContactModal">
                                Create Contact
                            </button>
                        </div>
                        
                        <div class="contacts-container {{ ($contacts->isEmpty() ? 'hidden' : '') }}">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                              <span class="glyphicon glyphicon-search"></span>
                                            </span>
                                            <input
                                                type="text"
                                                id="searchBox"
                                                class="form-control input-lg"
                                                placeholder="Search by Surname, Email or Phone"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th data-no-filter>Name</th>
                                            <th>Surname</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Custom Fields</th>
                                            <th></th>
                                        </tr>
                                        <tbody>
                                            @foreach($contacts as $contact)
                                                <tr contactRow="{{ $contact->id }}">
                                                    <td name>{{ $contact->name }}</td>
                                                    <td surname>{{ $contact->surname }}</td>
                                                    <td email>{{ $contact->email }}</td>
                                                    <td phone>{{ $contact->phone }}</td>
                                                    <td customFields>
                                                        @foreach($contact->customFields as $customField)
                                                            {{ $loop->last ? $customField->value : $customField->value.',' }}
                                                        @endforeach
                                                    </td>
                                                    <td class="text-right">
                                                        <button
                                                            type="button" 
                                                            editContact="{{ $contact->id }}"
                                                            class="btn btn-warning btn-xs">
                                                            Edit
                                                        </button>
                                                        <form
                                                            method="POST" 
                                                            action="{{ route('contacts.destroy', [$contact->id]) }}"
                                                            style="display: inline;"">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}
                                                            <button
                                                                type="submit"
                                                                class="btn btn-danger btn-xs"
                                                                delete-contact-button="{{ $contact->id }}"
                                                                data-toggle="modal"
                                                                data-target="#deleteContactModal">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        
                    </div><!-- /.panel-body -->
                </div>
            </div>
        </div>
    </div>
@endsection