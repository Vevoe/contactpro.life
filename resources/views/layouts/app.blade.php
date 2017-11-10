<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ContactsPro</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ route('contacts.index') }}">
                        <i><strong>Contacts</strong>Pro</i>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
    <script
        src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script id="customFieldTemplate" type="text/x-custom-template">
        <div class="form-group">
          <div class="input-group">
            <input type="text" name="customFields[custom_<%- fieldName %>]" class="form-control" value="<%- value %>">
            <span class="input-group-btn">
              <button type="button" add-custom-field class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></button>
              <button type="button" remove-custom-field class="btn btn-danger"><span class="glyphicon glyphicon-minus"></span></button>
            </span>
          </div>
        </div>
    </script>
    <script id="newRowTemplate" type="text/x-custom-template">
        <tr contactRow="<%- id %>">
            <td name><%- name %></td>
            <td surname><%- surname %></td>
            <td email><%- email %></td>
            <td phone><%- phone %></td>
            <td customFields><%- customFields %></td>
            <td class="text-right">
                <button type="button" editContact="<%- id %>" class="btn btn-warning btn-xs">Edit</button>
                <form
                    method="POST" 
                    action="/contacts/<%- id %>"
                    style="display: inline;"">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button
                        type="submit"
                        class="btn btn-danger btn-xs"
                        delete-contact-button="<%- id %>"
                        data-toggle="modal"
                        data-target="#deleteContactModal">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    </script> 
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
