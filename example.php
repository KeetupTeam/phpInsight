<?php
include 'sentiment.class.php';

function getTimeline($count, $username) {
  	$url = 'http://api.twitter.com/1/statuses/user_timeline.json?screen_name='.$username.'&count='.$count;
  	$twitterci = curl_init($url); 
	curl_setopt($twitterci, CURLOPT_RETURNTRANSFER, TRUE); 
	$twitterinput = curl_exec($twitterci); 
	curl_close($twitterci);

	return json_decode($twitterinput); 
}

$username = (empty($_GET['username'])) ? 'keetup' : $_GET['username'];

$tw = getTimeline(100, $username);

$examples = array();

if (!empty($tw)) {
	foreach($tw as $t) {
		$examples[] = $t->text;
	}
}

$sentiment = new Sentiment();


?>
<html>
	<head>
		<style>

			body {
				font-family:Arial,sans-serif;
			}
			blockquote {
				border:1px solid #e8e8e8;
				background:white;
				padding:10px;
			}

			div.example {
				border-left:2px solid black;
				margin:10px;
				padding:20px;
				background:#f4f4f4;
			}

			.neu {
				color:gray;
			}
			.pos {
				color:green;
			}
			.neg {
				color:red;
			}
			
		</style>
	</head>

	<body>
		<h1>phpInsight Example</h1>

		<?php
		foreach ($examples as $key => $example) {

			echo '<div class="example">';
			echo "<h2>Example $key</h2>";
			echo "<blockquote>$example</blockquote>";

			echo "Scores: <br />";
			$scores = $sentiment->score($example);

			echo "<ul>";
			foreach ($scores as $class => $score) {
				$string = "$class -- <i>$score</i>";
				if ($class == $sentiment->categorise($example)) {
					$string = "<b class=\"$class\">$string</b>";
				}
				echo "<ol>$string</ol>";
			}
			echo "</ul>";
			echo '</div>';
		}
		?>

	</body>
</html>