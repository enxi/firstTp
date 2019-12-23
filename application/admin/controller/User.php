<?php
namespace app\admin\controller;
use think\Controller;
//mail引入
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class User extends Controller
{	
	//注册
    public function register() {
    	if(request()->isPost()) {
    		$data = input('post.');

            //判断tel是否重复(电话号码不可重复)
            $telR = model("User")->get(['tel'=>$data['tel']]);

            if($telR) {
                $result = [
                    'status' => 300,
                    'message' => '该电话号码已经被注册'
                ];
                return $result;
            }
            
            //获取data校验 admin/validate/User.php
            $validate = validate('User');
            if (!$validate->scene('register')->check($data)) {
               $result = [
                    'status' => 300,
                    'message' => $validate->getError()
                ];
                return $result;
            }

            //验证码校验
            if(captcha_check($data['verifyCode']) !== true) {
                $result = [
                    'status' => 300,
                    'message' => '验证码出错'
                ];
                return $result;
            }

    		$data['code'] = mt_rand(100,10000);
    		$data['psd'] = md5($data['psd'].$data['code']);
    		$data['create_time'] = time();
    		$res = model("User")->allowField(true)->add($data);//对应User表

    		if($res) {
                //发送邮件通知注册成功（测试将email写自己的qq邮箱）
                $mres = $this->sendMail($data['email'], "恭喜注册成功", $data['name'].'注册成功！');
                if($mres) {
        			$result = [
    			        'status' => 200,
    			        'message' => 'success'
    			    ];
                }
    		}else {
				$result = [
			        'status' => 300,
			        'message' => '注册失败'
			    ];
    		}
    		return json($result);
    	}else {
    		// 查看thinkphp版本2.0
    		//echo THINK_VERSION;
    		return $this->fetch();
    	}
    }

    //上传图片
    public function upload () {
        $file = request()->file('file');
        $info = $file->move('upload');
        if($info && $info->getPathName()) {
            $result = [
                'msg' => 'success',
                'data' => '/'.$info->getPathName()
            ];
        }else {
            $result = [
                'msg' => 'error'
            ];
        }
        return json($result);
    }

    //登录 
    public function login () {
        if(request()->isPost()) {
            $data = input("post.");

            //获取data校验 admin/validate/User.php
            $validate = validate('User');
            if (!$validate->scene('login')->check($data)) {
               $result = [
                    'status' => 300,
                    'message' => $validate->getError()
                ];
                return $result;
            }

            $user = model("User")->get(['tel'=>$data['tel']]);
            if(!$user) {
                $result = [
                    'status' => 300,
                    'message' => '该用户没有注册，请先进行注册！'
                ];
                return $result;
            }
            if(md5($data['psd'].$user->code) != $user->psd) {
                $result = [
                    'status' => 300,
                    'message' => '密码出错！'
                ];
                return $result;
            }
            
            //最后登录时间
            $lastTime = model("User")->upDateById(['last_login_time' => time()], ['id' => $user['id']]);
            //存入session
            $s = session("currentUser", $user, 'admin');

            $result = [
                'status' => 200,
                'message' => '登录成功！'
            ];
            return $result;
        }else { 
            //已登录的情况，跳转到首页
            if(session("currentUser", '', 'admin')) {
                $this->redirect('/admin/index/index');
            }

            //展示登陆页面
            return $this->fetch();
        }
    }

    //退出登陆
    public function logout() {
        session(null, 'admin');
        return [
            'status' => 200,
            'message' => '退出成功！'
        ];
    }
/**
 * 发送邮件方法
 * @param string $to：接收者邮箱地址
 * @param string $title：邮件的标题
 * @param string $content：邮件内容
 * @return boolean  true:发送成功 false:发送失败
 */
    function sendMail($to,$title,$content){
        //实例化PHPMailer核心类
        $mail = new PHPMailer(true);
        //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        try{
            $mail->SMTPDebug = 0; 
            //使用smtp鉴权方式发送邮件
            $mail->isSMTP();
            //smtp需要鉴权 这个必须是true
            $mail->SMTPAuth=true;
            //链接qq域名邮箱的服务器地址
            $mail->Host = 'smtp.126.com';
            //设置使用ssl加密方式登录鉴权
            $mail->SMTPSecure = 'ssl';
            //设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
            $mail->Port = 465;
            //设置smtp的helo消息头 这个可有可无 内容任意
            $mail->Helo = '';
            //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
            $mail->Hostname = 'L';
            //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
            $mail->CharSet = 'UTF-8';
            //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
            $mail->FromName = '美丽的蛋宝';
            //smtp登录的账号 这里填入字符串格式的qq号即可
            $mail->Username ='enxinet_123@126.com';
            //smtp登录的密码 使用生成的授权码 你的最新的授权码
            $mail->Password = '123kuaile';
            //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
            $mail->From = 'enxinet_123@126.com';
            //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
            $mail->isHTML(true);
            //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
            $mail->addAddress($to);
            //添加多个收件人 则多次调用方法即可
            // $mail->addAddress('xxx@qq.com','lsgo在线通知');
            //添加该邮件的主题
            $mail->Subject = $title;
            //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
            $mail->Body = $content;

            //为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
            $mail->addAttachment('.\upload\1.docx','注册成功.docx');
            //同样该方法可以多次调用 上传多个附件
            // $mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');

            $status = $mail->send();

            //简单的判断与提示信息
            if($status) {
                return true;
            }else{
                return false;
            }
        }catch(phpmailerException $e) {
            return false;
        }
    }

    /*Request使用*/
    /*	upload方法中测试
	    方式一：（没生效）
			use think\Request;
		    $request = new Request();
		    $file = $request->file('file');
		方式二：通过依赖注入的方式，同方式一（生效）
			use think\Request;
			public function upload (Request $request) {
	    	$file = $request->file('file');
	    方式三：继承了Controller类，可以使用该类中的方法和属性(request属性)，$this->request->file('file')
	    	$file = $this->request->file('file');
	    方式四：助手函数
	    	$file = request()->file('file');
    */
}
