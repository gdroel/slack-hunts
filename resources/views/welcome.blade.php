<html>
	<head>
		<title>Slack Hunts</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css">
		<link href='http://fonts.googleapis.com/css?family=Lato:400,700,900' rel='stylesheet' type='text/css'>
	</head>
	<body>
		
		<div class="container">


			<div class="col-md-6 col-md-offset-3">
			<h1 class="white header">Slack Hunts</h1>
			<p class="white header-text">When you upvote a product on Product Hunt, a Slack bot alerts your team members</p>
				<p class="white success">{{ Session::get('success') }}</p>
			@if(Session::get('userID') == "")
	
			<a class="white btn btn-warning bigger-text" href="https://api.producthunt.com/v1/oauth/authorize?client_id=fb42db6b01792f3e48c39ca9c658db4f42d7cbbc72afc296cf60d5076ac0b8de&redirect_uri=http%3A%2F%2Flocalhost:8000%2Fcallback&response_type=code&scope=public+private
			">Log in with Product Hunt</a>
			@endif

			@if(Session::get('userID') != "")
			<div class="container-fluid">
			<h2>Now Set Up a Slack Webhook</h2>
			<hr>
			<ol>
				<li><p>Go to <a target="_blank" href="https://my.slack.com/services/new/incoming-webhook/">https://my.slack.com/services/new/incoming-webhook/</a> to set up a webhook.</p>
				<li><p>Choose a channel that you want the Product Hunt bot to post to.</p></li>
				<li><p>Copy and Paste the Webhook URL that Slack gives you into the box below</p></li>
			</ol>

			<form method="post" action="{{ URL::to('login') }}">
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			<input class="form-control" type="text" name="slack_url">
			<input type="hidden" name="phid" value="{{ Session::get('userID') }}">
			<br>
			<button class="btn btn-warning" type="submit" value="Submit">Submit</button>
			</form>
			</div>
			@endif

			</div>
		
	
	</body>

</html>
