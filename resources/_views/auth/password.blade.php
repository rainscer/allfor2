@extends('layout.default')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="panel panel-default register">
				<div class="panel-body">
					@if (session('status'))
						<div class="alert alert-success">
							{{ session('status') }}
						</div>
					@endif
						<h3>{{ trans('user.passwordReset') }}</h3>
					<form class="form-horizontal" role="form" method="POST" action="{{ url('password/email') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">{{ trans('user.loginEmail') }}</label>
							<div class="col-md-8">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group centered">
								<button type="submit" class="btn">
									{{ trans('user.getLinkForReset') }}
								</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
