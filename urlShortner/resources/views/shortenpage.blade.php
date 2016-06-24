@extends('layout')

@section('header')
	<link rel="stylesheet" href="css/style.css" >
@stop


@section('container')
    @if (Session::has('id'))
		<div class="container">
			<h3> WELCOME {{ session('name') }}</h3>
			<h1 class="title"> Shorten a URL </h1>
			{{ Form::open(['url'=>'/make'])}}
			<input type="url" name="url" placeholder="Enter a Url" autocomplete="off">
			<input type="submit" value="SHORTEN">			
			{{ Form::close()}}
			<a href="/logout">Logout</a>
			<hr />
			<table style="width:100%">
				<tr>
					<th> WEBSITE </th>
					<th> SHORTEN URL </th>
					<th> ACTION </th>
					<th> REDIRECTS </th>
				</tr>
                @for($i=0;$i<$count;$i++)
                <tr>
                	<td>{{ $usersLink[$i]->url }}</td>
					<td><a href="{{ URL::route('web', [$usersLink[$i]->hash])}}">{{env('URL_PATH')}}/{{ $usersLink[$i]->hash }}</a></td>
					<td><form action="{{ URL::route('state', [$usersLink[$i]->hash])}}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						@if ($usersLink[$i]->action == 1)
  							<input type="submit" name="action" value="disable"></input>
  						@else
  							<input type="submit" name="action" value="enable"></input>
						@endif
						</form>
					</td>
					<td>{{ $usersLink[$i]->redirect }}</td>
				</tr>
				@endfor
			</table>		
		</div>
	@else
		redirect('/');
	@endif
@stop
	