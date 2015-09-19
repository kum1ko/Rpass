<?php

function startTime() {
	$timeArray = explode(' ', microtime());
	$timeString = $timeArray[1] + $timeArray[0];
	return $timeString;
}
$pagestartime = startTime();
header('Content-Type:text/html;charset=GBK');
header('X-Powered-By: kaguya');
echo '<title>rPass 1.1.6</title>';
$resultStyle = 'font-family:Georgia;font-size:17px;word-break:break-all;';
// 配置
$method = @$_GET['method'];
$methodArray = explode('/', $method);
// var_dump($methodArray);

$pass_length = 16; // 长度
$pass_array = []; // 构成
$pass_if_repeat = 0; // 是否重复
$pass_struct = null; // 自定义结构
$pass_atleast_ = 0; // 必出一个_
$pass_num = 1; // 密码个数
$pass_chinese = 0; // 中文
//
function CONV($string) {
	$resultArray = str_replace("&", "&amp;", $string);
	$resultArray = str_replace("<", "&lt;", $resultArray);
	$resultArray = str_replace(">", "&gt;", $resultArray);
	if (get_magic_quotes_gpc()) {
		$resultArray = str_replace("\\\"", "&quot;", $resultArray);
		$resultArray = str_replace("\\''", "&#039;", $resultArray);
	} else {
		$resultArray = str_replace("\"", "&quot;", $resultArray);
		$resultArray = str_replace("'", "&#039;", $resultArray);
	}
	return $resultArray;
}

// echo $pass_length;
$arrayNum = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
$arrayABC = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
$arrayabc = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
// $array_ = ['_', '-'];
$array_ = ['_'];
$arraySpecial = ['-', '!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '.', '/', ':', ';', '<', '=', '>', '?', '@', '[', '\\', ']', '^', '`', '{', '|', '}', '~'];
function arrayNumGet() {
	# code...
	global $arrayNum;
	return $arrayNum[mt_rand(0, count($arrayNum) - 1)];
}
function arrayUPGet() {
	# code...
	global $arrayABC;
	return $arrayABC[mt_rand(0, count($arrayABC) - 1)];
}
function arrayLOGet() {
	# code...
	global $arrayabc;
	return $arrayabc[mt_rand(0, count($arrayabc) - 1)];
}
function array_Get() {
	# code...
	global $array_;
	return $array_[mt_rand(0, count($array_) - 1)];
}
function arraySpecialGet() {
	# code...
	global $arraySpecial;
	return $arraySpecial[mt_rand(0, count($arraySpecial) - 1)];
}

function randomReplace($string, $str, $num) {
	# code...
	// $random_box = mt_rand(0, strlen($string) - 1);
	// $string = substr_replace($string, $str, $random_box, 1);

	//
	$matches = preg_match_all('/' . $str . '/', $string, $matches);
	if ($matches >= $num) {
		return $string;
	}
	for ($index = 0; $index < $num - $matches; $index++) {
		# code...

		$random_box = mt_rand(0, strlen($string) - 1);
		// var_dump();
		while (true) {
			# code...
			$random_box = mt_rand(0, strlen($string) - 1);
			if (substr($string, $random_box, 1) != $str) {
				$string = substr_replace($string, $str, $random_box, 1);
				break;
			}
		}
	}
	return $string;
}

// echo randomReplace('151241415', '_', 2);
// $a = '';
// for ($i = 0; $i < 26; $i++) {
// 	$a .= '\'' . chr(123 + $i) . '\',';
// }

// print_r($a);

/////////////
// STEP_寻找长度 & 自定义结构
foreach ($methodArray as $key => $value) {
	# code...
	if ($value == 'api') {
		header('Content-Type:text/html;charset=UTF-8');
		echo '
<p style="font-size: 12px;">API</p>
<p style="font-size: 12px;">长度:/{int}</p>
<p style="font-size: 12px;">个数:/x{int}</p>
<p style="font-size: 12px;">强度:/{number|normal|strong|extreme}（默认normal）</p>
<p style="font-size: 12px;">无重复:/{norepeat}</p>
<p style="font-size: 12px;">自定义形式:/{D|U|L|S|-|_}</p>
<p style="font-size: 12px;">语言:/{chinese}</p>

<p style="font-size: 12px;">* * * * * *</p>
<p style="font-size: 12px;">* 各选项不分顺序。</p>
<p style="font-size: 12px;">* 无重复模式长度不能无限长，取决于密码构成。</p>
<p style="font-size: 12px;">* 密码个数不能超过100，否则以100计。</p>
<p style="font-size: 12px;">* 当强度为strong及以上时，每8位密码必将随机产生一个下划线。</p>
<p style="font-size: 12px;">* 当启用自定义形式时，长度，强度，无重复选项将会失效。</p>
<p style="font-size: 12px;">* 当启用语言选项时，某些选项失效。</p>
<p style="font-size: 12px;">* 自定义Patterns说明：D数字 U大写字母 L小写字母 S混合。'

// Generation Based on Patterns

		;
		exit();
	}
	if (is_numeric($value)) {
		$pass_length = (int) $value;
		if ($pass_length > 10000) {
			$pass_length = 10000;
		}
	}
	if (substr($value, 0, 1) == 'x') {
		$pass_num = (int) (substr($value, 1));
		if ($pass_num > 1000) {
			$pass_num = 100;
		}
	}
	if ($value == 'chinese') {
		$pass_chinese = 1;
	}
	// 紧邻模式
	// if ($value == 'n-a-n') {
	// 	$pass_chinese = 1;
	// }
	if (preg_match('/D|U|L|S/', $value)) {
		// $pass_struct = [];
		// $ar = explode('-', $value);
		// foreach ($ar as $key => $value) {
		// 	# code...
		// 	array_push($pass_struct, $ar[$key]);
		// }
		$pass_struct = $value;
	}
}
echo '<a href="api" style="font-size:12px;">api<a/>';
// echo $pass_num;
// var_dump($pass_struct);
// STEP_寻找密码构成
if (in_array('number', $methodArray)) {
	$pass_array = array_merge($pass_array, $arrayNum);
} else if (in_array('normal', $methodArray)) {
	$pass_array = array_merge($pass_array, $arrayNum, $arrayABC, $arrayabc);
} else if (in_array('strong', $methodArray)) {
	$pass_array = array_merge($pass_array, $arrayNum, $arrayABC, $arrayabc, $array_);
	$pass_atleast_ = 1;
} else if (in_array('extreme', $methodArray)) {
	$pass_array = array_merge($pass_array, $arrayNum, $arrayABC, $arrayabc, $array_, $arraySpecial);
	$pass_atleast_ = 1;
} else {
	$pass_array = array_merge($pass_array, $arrayNum, $arrayABC, $arrayabc);
}

// var_dump($pass_array);
// STEP_是否重复
if (in_array('norepeat', $methodArray)) {
	$pass_if_repeat = 1;
	$pass_length > count($pass_array) ? ($pass_length = count($pass_array)) : $pass_length;
} else {
	$pass_if_repeat = 0;
}

////////////////////////////////
// 长度
////////////////////////////////
////////////////////////////////
// 模式
// $_MD = @$_GET['m'];
// $_MD = (string) $_MD;
// if (!isset($_GET['m'])) {
// 	// 未设置
// 	$_MD = 'n';
// 	$_MDTEXT = '默认';
// } else {
// 	$_MDTEXT = '不重复';
// }
////////////////////////////////
// 随机范围
// $maxLength = (int) (126 - 33 + 1);
// $randomArray = [];
// for ($i = 0; $i < $maxLength; $i++) {
// 	array_push($randomArray, chr(33 + $i));
// }

// var_dump($randomArray);
// if ($length > $maxLength) {
// 	exit('超过最大长度' . $maxLength);
// }
// 是否启用了自定义模式
// var_dump($pass_struct);
if ($pass_struct != null) {
	$pass_length = strlen($pass_struct);
}
echo '<p style="font-family: monospace;">Length:' . $pass_length . ',Num:' . $pass_num . '</p>';
if ($pass_chinese == 1) {
	function unicode_decode($name) {

		// 转换编码，将Unicode编码转换成可以浏览的utf-8编码
		$pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
		preg_match_all($pattern, $name, $matches);
		if (!empty($matches)) {
			$name = '';
			for ($j = 0; $j < count($matches[0]); $j++) {
				$str = $matches[0][$j];
				if (strpos($str, '\\u') === 0) {
					$code = base_convert(substr($str, 2, 2), 16, 10);
					$code2 = base_convert(substr($str, 4), 16, 10);
					$c = chr($code) . chr($code2);
					// $c = iconv('ISO-8859-1', 'GBK', $c);
					$name .= $c;
				} else {
					$name .= $str;
				}
			}
		}
		return $name;
	}
	for ($x = 0; $x < $pass_num; $x++) {
		$content = '';
		$str = "";
		for ($i = 0; $i < $pass_length; $i++) {
			// echo "&#x" . dechex(rand(176, 215) << 8 | rand(161, 254)) . ";";
			$str .= "\\u" . dechex(rand(176, 215) << 8 | rand(161, 254));
		}
		$content .= unicode_decode($str);
		// echo '<p style="'.$resultStyle.'">' . $content . '</p>';
		echo '<p style="' . $resultStyle . '">' . $content . '</p>';
	}
} else if ($pass_struct != null) {
	// var_dump($pass_struct);
	for ($x = 0; $x < $pass_num; $x++) {

		$resultString = '';
		# code...
		// $pass_struct_each = $value;
		for ($x = 0; $x < $pass_num; $x++) {
			$resultString = '';
			foreach (str_split($pass_struct) as $key => $value) {
				# code...
				switch ($value) {
					case 'L': // abcdefghijklmnopqrstuvwxyz
						$resultString .= arrayLOGet();
						break;
					case 'U': // ABCDEFGHIJKLMNOPQRSTUVWXYZ
						$resultString .= arrayUPGet();
						break;
					case '-':
						$resultString .= '-';
						break;
					case 'D': // 0123456789
						$resultString .= arrayNumGet();
						break;
					case '_': // _
						$resultString .= '_';
						break;
					case 'S': // @#$%^&*()
						$resultString .= arraySpecialGet();
						break;
					default:
						$resultString .= (String) $value;
						break;

						// arraySpecialGet
				}
			}
			// echo '<p>11</p>';
			echo '<p style="' . $resultStyle . '">' . $resultString . '</p>';
		}
	}
} else {
	// $underline_num = 1;
	$underline_num = (int) ($pass_length / 8);
	switch ($pass_if_repeat) {
		case 1:
			// $len = strlen($str) - 1;
			for ($x = 0; $x < $pass_num; $x++) {
				$resultArray = [];
				$pass_array_copy = $pass_array;
				for ($i = 0; $i < $pass_length; $i++) {
					// 取11次
					$num = mt_rand(0, count($pass_array_copy) - 1);
					array_push($resultArray, $pass_array_copy[$num]);
					array_splice($pass_array_copy, $num, 1);
				}

				// var_dump($resultArray);
				if ($pass_atleast_ == 0) {
					$resultString = implode('', $resultArray);
				} else {
					$resultString = randomReplace(implode('', $resultArray), '_', $underline_num);
				}
				$resultString = CONV($resultString);
				// $resultArray = randomReplace(CONV($resultArray), '_', $underline_num);
				echo '<p style="' . $resultStyle . '">' . $resultString . '</p>';
			}

			break;
		case 0:
			for ($x = 0; $x < $pass_num; $x++) {
				$resultArray = [];
				$pass_array_copy = $pass_array;
				for ($i = 0; $i < $pass_length; $i++) {
					$num = mt_rand(0, count($pass_array_copy) - 1);
					array_push($resultArray, $pass_array_copy[$num]);
				}
				// $resultArray = CONV($resultArray);
				if ($pass_atleast_ == 0) {
					$resultString = implode('', $resultArray);
				} else {
					$resultString = randomReplace(implode('', $resultArray), '_', $underline_num);
				}
				$resultString = CONV($resultString);
				echo '<p style="' . $resultStyle . '">' . $resultString . '</p>';

			}
			break;
	}

	// echo '<p style="'.$resultStyle.'"><i>(MD5)</i> ' . md5(implode('', $resultArray)) . '</p>';
	// echo '<p style="'.$resultStyle.'"><i>(LENGTH)</i> ' . 16 . '</p>';
}

// $resultJSONArray = [];
// $resultJSONArray['r'] = implode('', $resultArray);

// if (isset($_GET['t'])) {
// 	header('Content-type:text/javascript;charset=UTF-8');
// 	if ($_GET['t'] == 'j') {
// 		$res = json_encode($resultJSONArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
// 		echo $res;
// 	} else {
// 		$callback = @$_GET['callback'];
// 		echo $callback . '(' . json_encode($resultJSONArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ')';
// 	}

// } else {
//\Yp*6?v).Fy`/M'lnT+(e}NwBrWd98PtKmGD;[_C%2@>:x{kV4X$H!17=uhj#aIU3
// echo '<title>长度[' . $length . '] 模式[' . $_MDTEXT . ']</title>';

// }
?>
<?php
// $pageendtime = microtime();
// $starttime = explode(" ", $pagestartime);
// $endtime = explode(" ", $pageendtime);
// $totaltime = $endtime[0] - $starttime[0] + $endtime[1] - $starttime[1];
// $timecost = sprintf("%s", $totaltime);

echo '<p style="font-size: 12px;">' . round((startTime() - $pagestartime), 4) . 's</p>';

// var_dump($timeString);
// BY ARIA
?>
