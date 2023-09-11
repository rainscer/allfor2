@extends('layout.default')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default register">
				<div class="panel-body">
						<h3>{{ trans('user.registration') }}</h3>
					<form class="form-horizontal" role="form" method="POST" action="{{ url('auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('user.name') }}</label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="name" value="{{ old('name') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('user.last_name') }}</label>
							<div class="col-md-8">
								<input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('user.loginEmail') }}</label>
							<div class="col-md-8">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('user.registrationPassword') }}</label>
							<div class="col-md-8">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('user.registrationPasswordConfirm') }}</label>
							<div class="col-md-8">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group centered">
								<button type="submit" class="btn">
									{{ trans('user.registration') }}
								</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
