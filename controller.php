<?php
	require_once('../config.php');

	/* Redirector */
	function redirectTo($page) {
		header("Location: $page");
		die();
	}

	/* Question Related */
	function insertQuestion($arr) {
		global $conn;

		$query = "INSERT INTO question (name, email, topic, content, vote, date_created, is_delete)
				  VALUES ";

		$name = mysqli_real_escape_string($conn, $arr['name']);
		$email = mysqli_real_escape_string($conn, $arr['email']);
		$topic = mysqli_real_escape_string($conn, $arr['topic']);
		$content = mysqli_real_escape_string($conn, $arr['content']);
		$vote = 0;
		$date_created = date("Y-m-d H:i:s");
		$is_delete = 0;

		$query .= "('$name', '$email', '$topic', '$content', '$vote', '$date_created', '$is_delete')";

		if (mysqli_query($conn, $query)) {
		    echo "Berhasil membuat Question baru!";
		} else {
		    echo "Error: " . $query . "<br>" . mysqli_error($conn);
		}
	}

	function getListOfQuestionLimited($start) {
		global $conn;
		
		$start *= 5;

		$query = "SELECT id, name, topic, vote, is_delete FROM question ORDER BY id DESC LIMIT ". $start .", 5";
		$result = mysqli_query($conn, $query);

		$ret = array();		

		if (mysqli_num_rows($result) > 0) {
		    while($row = mysqli_fetch_assoc($result)) {
				$question = array();
        		$question["id"] = $row['id'];
        		$question["name"] = $row['name'];
				$question["topic"] = $row["topic"];
				$question["vote"] = $row["vote"];
				$question["is_delete"] = $row["is_delete"];
		    	array_push($ret, $question);
		    }
		}

		return $ret;				
	}

	function getQuestionbyId($id) {
		global $conn;

		$query = "SELECT * FROM question WHERE id = ". $id;
		$result = mysqli_query($conn, $query);

		$row = mysqli_fetch_assoc($result);
		$question = array();
		$question["id"] = $row['id'];
		$question["name"] = $row['name'];
		$question["email"] = $row["email"];
		$question["content"] = $row["content"];
		$question["topic"] = $row["topic"];
		$question["vote"] = $row["vote"];
		$question["date_created"] = $row["date_created"];
		$question["date_edited"] = $row["date_edited"];
		$question["is_delete"] = $row["is_delete"];

		return $question;				
	}

	function getAnswerCount($id_q) {
		global $conn;
		
		$query = "SELECT COUNT(*) as total FROM answer WHERE id_q = ". $id_q;
		$result = mysqli_query($conn, $query);

		$ret = "";
		$row = mysqli_fetch_assoc($result);
		
		$ret = $row["total"];

		return $ret;			
	}

	/* Answers Related */
	function insertAnswer($arr) {
		global $conn;

		$query = "INSERT INTO answer (name, email, content, id_q, vote, date_created)
				  VALUES ";

		$name = mysqli_real_escape_string($conn, $arr['name']);
		$email = mysqli_real_escape_string($conn, $arr['email']);
		$content = mysqli_real_escape_string($conn, $arr['content']);
		$id_q = mysqli_real_escape_string($conn, $arr['id_q']);
		$vote = 0;
		$date_created = date("Y-m-d H:i:s");

		$query .= "('$name', '$email', '$content', '$id_q', '$vote', '$date_created')";

		if (mysqli_query($conn, $query)) {
		    echo "Berhasil membuat Answer!";
		} else {
		    echo "Error: " . $query . "<br>" . mysqli_error($conn);
		}
	}

	function getAnswerbyQId($id_q) {
		global $conn;

		$query = "SELECT * FROM answer WHERE id_q = ". $id_q;
		$result = mysqli_query($conn, $query);

		$ret = array();		

		if (mysqli_num_rows($result) > 0) {
		    while($row = mysqli_fetch_assoc($result)) {
				$answer = array();
				$answer["id"] = $row['id'];
				$answer["name"] = $row['name'];
				$answer["email"] = $row["email"];
				$answer["content"] = $row["content"];
				$answer["vote"] = $row["vote"];
				$answer["date_created"] = $row["date_created"];
		    	array_push($ret, $answer);
		    }
		}

		return $ret;		
	}

	/* SEO Optimization Functions */
	function replace_dashes($string) {
	    $string = str_replace(" ", "-", $string);
	    return $string;
	}

	function delete_punctuation($string) {
		$punctuation = array(",", ".", "/", "?", "!", "-", "+", "=", "_", "%", "$", "#", "@", "*", "&", "(", ")", "<", ">", "\\", "[", "]", "{", "}", ";", ":", "\'", "\"");
		$string = str_replace($punctuation, "", $string);
	    return $string;		
	}

	function get_clean_string($string) {
		return replace_dashes(delete_punctuation($string));
	}

?>