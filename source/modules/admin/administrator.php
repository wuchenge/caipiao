<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('admin', 'admin', 0);
class administrator extends admin {
	private $db;

	public function __construct() {
		parent :: __construct(1);
		$this -> db = base :: load_model('admin_model');
	}

	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this -> db -> listinfo('', 'id DESC', $page, 15);
		$pages = $this -> db -> pages;
		base :: load_sys_class('format', '', 0);
		$issuperarr = array(0 => '信息管理员', 1 => '超级管理');
		include $this -> admin_tpl('administrator_list');
	}

	public function add() {
		$super = $this -> get_userinfo('issuper'); //操作者身份
		if (isset($_POST['dosubmit'])) {
			$username = isset($_POST['username']) && trim($_POST['username']) ? safe_replace(trim($_POST['username'])) : showmessage('请输入用户名！', HTTP_REFERER);
			$password = isset($_POST['password']) && trim($_POST['password']) ? trim($_POST['password']) : showmessage('请输入密码！', HTTP_REFERER);
			$mobile = isset($_POST['mobile']) && trim($_POST['mobile']) ? safe_replace(trim($_POST['mobile'])) : '';
			$google_secret = $_POST['google_secret'];
			$issuper = 0;
			if ($super == 1) {
				$issuper = intval($_POST['issuper']);
			}
			if ($this -> db -> get_one(array('username' => $username))) {
				showmessage('用户已存在！', HTTP_REFERER);
			} else {
				if (strlen($username) > 20 || strlen($username) < 3) {
					showmessage('用户名为3-20位之间！', HTTP_REFERER);
				}
				if (strlen($password) > 20 || strlen($password) < 6) {
					showmessage('密码为6-20位之间！', HTTP_REFERER);
				}
				list($password, $encrypt) = creat_password($password);
				if ($this -> db -> insert(array('username' => $username, 'mobile' => $mobile, 'password' => $password, 'encrypt' => $encrypt, 'issuper' => $issuper, 'google_secret'=>$google_secret))) {
					showmessage('管理员添加成功！', 'c=administrator&a=init');
				} else {
					showmessage('操作失败！', HTTP_REFERER);
				}
			}
		}
		
		 $ga=new PHPGangsta_GoogleAuthenticator();
         $google_secret = $ga->createSecret();
     
         //生成引导绑定的二维码图片
         $qrcode = $ga->getQRCodeGoogleUrl('uuuho.com', $google_secret, 'googleverify');
		
		include $this -> admin_tpl('administrator_add');
	}

	public function del() {
		$id = intval($_GET['id']);
		if (!$id) {
			echo json_encode(array('run' => 'no', 'msg' => '参数错误！'));
			exit();
		}
		$r = $this -> db -> get_one(array('id' => $id));
		if ($r) {
			if ($r['issuper'] == 1) { // 操作的对象是超级管理身份
				$super = $this -> get_userinfo('issuper'); //操作者身份
				if ($super != 1) { // 操作者不是超级管理员
					echo json_encode(array('run' => 'no', 'msg' => '权限不足！'));
					exit();
				}
				$super_num = $this -> db -> count(array('issuper' => 1));
				if ($super_num <= 1) { // 唯一的超级管理员
					echo json_encode(array('run' => 'no', 'msg' => '至少需要保留一个超级管理员！'));
					exit();
				}
			}
			if ($this -> db -> delete(array('id' => $id))) {
				echo json_encode(array('run' => 'yes', 'msg' => '删除成功！', 'id' => 'list_' . $id));
				exit();
			} else {
				echo json_encode(array('run' => 'no', 'msg' => '删除失败！'));
				exit();
			}
		} else {
			echo json_encode(array('run' => 'no', 'msg' => '未找到对应数据！'));
			exit();
		}
	}

	public function edit() {
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : showmessage('参数错误！', HTTP_REFERER);
		$data = $this -> db -> get_one(array('id' => $id));
		if ($data) {
			$super = $this -> get_userinfo('issuper'); //操作者身份
			if (isset($_POST['dosubmit'])) {
				$issuper = 0;
				if ($super == 1) {
					$issuper = intval($_POST['issuper']);
				}
				if ($data['issuper'] == 1) { // 操作的对象是超级管理身份
					if ($super != 1) { // 操作者不是超级管理员
						showmessage('权限不足！', HTTP_REFERER);
					}
					$super_num = $this -> db -> count(array('issuper' => 1));
					if ($super_num <= 1 && $issuper != 1) { // 唯一的超级管理员
						showmessage('至少需要保留一个超级管理员！', HTTP_REFERER);
					}
				}
				$pwd = isset($_POST['password']) && trim($_POST['password']) ? trim($_POST['password']) : '';
				$update = array('issuper' => $issuper);
				if ($pwd) {
					if (strlen($pwd) > 20 || strlen($pwd) < 6) {
						showmessage('密码为6-20位之间', HTTP_REFERER);
					}
					list($password, $encrypt) = creat_password($pwd);
					$update['password'] = $password;
					$update['encrypt'] = $encrypt;
				}
				$mobile = isset($_POST['mobile']) && trim($_POST['mobile']) ? safe_replace(trim($_POST['mobile'])) : '';
				$update['mobile'] = $mobile;
				if ($this -> db -> update($update, array('id' => $id))) {
					showmessage('修改成功！', 'c=administrator&a=init');
				} else {
					showmessage('修改失败！', HTTP_REFERER);
				}
			}
			$checked[$data['issuper']] = 'checked="checked"';
			include $this -> admin_tpl('administrator_edit');
		} else {
			showmessage('未找到对应数据！', HTTP_REFERER);
		}
	}

	public function ajax_username() {
		$username = isset($_POST['param']) && trim($_POST['param']) ? safe_replace(trim($_POST['param'])) : '';
		if (!$username || $this -> db -> get_one(array('username' => $username))) {
			$msg['info'] = '已存在该用户名！';
			$msg['status'] = 'n';
		} else {
			$msg['info'] = '用户名可以使用！';
			$msg['status'] = 'y';
		}
		echo json_encode($msg);
	}
}

class PHPGangsta_GoogleAuthenticator
{
    protected $_codeLength = 6;

    /**
     * Create new secret.
     * 16 characters, randomly chosen from the allowed base32 characters.
     *
     * @param int $secretLength
     *
     * @return string
     */
    public function createSecret($secretLength = 16)
    {
        $validChars = $this->_getBase32LookupTable();

        // Valid secret lengths are 80 to 640 bits
        if ($secretLength < 16 || $secretLength > 128) {
            throw new Exception('Bad secret length');
        }
        $secret = '';
        $rnd = false;
        if (function_exists('random_bytes')) {
            $rnd = random_bytes($secretLength);
        } elseif (function_exists('mcrypt_create_iv')) {
            $rnd = mcrypt_create_iv($secretLength, MCRYPT_DEV_URANDOM);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $rnd = openssl_random_pseudo_bytes($secretLength, $cryptoStrong);
            if (!$cryptoStrong) {
                $rnd = false;
            }
        }
        if ($rnd !== false) {
            for ($i = 0; $i < $secretLength; ++$i) {
                $secret .= $validChars[ord($rnd[$i]) & 31];
            }
        } else {
            throw new Exception('No source of secure random');
        }

        return $secret;
    }

    /**
     * Calculate the code, with given secret and point in time.
     *
     * @param string   $secret
     * @param int|null $timeSlice
     *
     * @return string
     */
    public function getCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }

        $secretkey = $this->_base32Decode($secret);

        // Pack time into binary string
        $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
        // Hash it with users secret key
        $hm = hash_hmac('SHA1', $time, $secretkey, true);
        // Use last nipple of result as index/offset
        $offset = ord(substr($hm, -1)) & 0x0F;
        // grab 4 bytes of the result
        $hashpart = substr($hm, $offset, 4);

        // Unpak binary value
        $value = unpack('N', $hashpart);
        $value = $value[1];
        // Only 32 bits
        $value = $value & 0x7FFFFFFF;

        $modulo = pow(10, $this->_codeLength);

        return str_pad($value % $modulo, $this->_codeLength, '0', STR_PAD_LEFT);
    }

    /**
     * Get QR-Code URL for image, from google charts.
     *
     * @param string $name
     * @param string $secret
     * @param string $title
     * @param array  $params
     *
     * @return string
     */
    public function getQRCodeGoogleUrl($name, $secret, $title = null, $params = array())
    {
        $width = !empty($params['width']) && (int) $params['width'] > 0 ? (int) $params['width'] : 200;
        $height = !empty($params['height']) && (int) $params['height'] > 0 ? (int) $params['height'] : 200;
        $level = !empty($params['level']) && array_search($params['level'], array('L', 'M', 'Q', 'H')) !== false ? $params['level'] : 'M';

        $urlencoded = urlencode('otpauth://totp/'.$name.'?secret='.$secret.'');
        if (isset($title)) {
            $urlencoded .= urlencode('&issuer='.urlencode($title));
        }

        return "https://api.qrserver.com/v1/create-qr-code/?data=$urlencoded&size=${width}x${height}&ecc=$level";
    }

    /**
     * Check if the code is correct. This will accept codes starting from $discrepancy*30sec ago to $discrepancy*30sec from now.
     *
     * @param string   $secret
     * @param string   $code
     * @param int      $discrepancy      This is the allowed time drift in 30 second units (8 means 4 minutes before or after)
     * @param int|null $currentTimeSlice time slice if we want use other that time()
     *
     * @return bool
     */
    public function verifyCode($secret, $code, $discrepancy = 1, $currentTimeSlice = null)
    {
        if ($currentTimeSlice === null) {
            $currentTimeSlice = floor(time() / 30);
        }

        if (strlen($code) != 6) {
            return false;
        }

        for ($i = -$discrepancy; $i <= $discrepancy; ++$i) {
            $calculatedCode = $this->getCode($secret, $currentTimeSlice + $i);
            if ($this->timingSafeEquals($calculatedCode, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set the code length, should be >=6.
     *
     * @param int $length
     *
     * @return PHPGangsta_GoogleAuthenticator
     */
    public function setCodeLength($length)
    {
        $this->_codeLength = $length;

        return $this;
    }

    /**
     * Helper class to decode base32.
     *
     * @param $secret
     *
     * @return bool|string
     */
    protected function _base32Decode($secret)
    {
        if (empty($secret)) {
            return '';
        }

        $base32chars = $this->_getBase32LookupTable();
        $base32charsFlipped = array_flip($base32chars);

        $paddingCharCount = substr_count($secret, $base32chars[32]);
        $allowedValues = array(6, 4, 3, 1, 0);
        if (!in_array($paddingCharCount, $allowedValues)) {
            return false;
        }
        for ($i = 0; $i < 4; ++$i) {
            if ($paddingCharCount == $allowedValues[$i] &&
                substr($secret, -($allowedValues[$i])) != str_repeat($base32chars[32], $allowedValues[$i])) {
                return false;
            }
        }
        $secret = str_replace('=', '', $secret);
        $secret = str_split($secret);
        $binaryString = '';
        for ($i = 0; $i < count($secret); $i = $i + 8) {
            $x = '';
            if (!in_array($secret[$i], $base32chars)) {
                return false;
            }
            for ($j = 0; $j < 8; ++$j) {
                $x .= str_pad(base_convert(@$base32charsFlipped[@$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
            }
            $eightBits = str_split($x, 8);
            for ($z = 0; $z < count($eightBits); ++$z) {
                $binaryString .= (($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48) ? $y : '';
            }
        }

        return $binaryString;
    }

    /**
     * Get array with all 32 characters for decoding from/encoding to base32.
     *
     * @return array
     */
    protected function _getBase32LookupTable()
    {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
            'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
            'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31
            '=',  // padding char
        );
    }

    /**
     * A timing safe equals comparison
     * more info here: http://blog.ircmaxell.com/2014/11/its-all-about-time.html.
     *
     * @param string $safeString The internal (safe) value to be checked
     * @param string $userString The user submitted (unsafe) value
     *
     * @return bool True if the two strings are identical
     */
    private function timingSafeEquals($safeString, $userString)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($safeString, $userString);
        }
        $safeLen = strlen($safeString);
        $userLen = strlen($userString);

        if ($userLen != $safeLen) {
            return false;
        }

        $result = 0;

        for ($i = 0; $i < $userLen; ++$i) {
            $result |= (ord($safeString[$i]) ^ ord($userString[$i]));
        }

        // They are only identical strings if $result is exactly 0...
        return $result === 0;
    }
}
?>