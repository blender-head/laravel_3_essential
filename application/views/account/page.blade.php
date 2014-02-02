@layout('template')

@section('title')
	Dayle's Webpage!
@endsection

@section('navigation')
	@parent
	<li><a href="#">About</a></li>
@endsection

@section('content')
	<h1>Welcome!</h1>
	<p>Welcome to Dayle's web page!</p>
@endsection