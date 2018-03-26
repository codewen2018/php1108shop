<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $mobile 手机号
 * @property int $login_time 登录时间
 * @property int $login_ip IP地址
 */
class User extends \yii\db\ActiveRecord
{
    public $password;//密码
    public $rePassword;//确认密码
    public $checkCode;//验证码
    public $captcha;//短信验证码
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password','rePassword','mobile', 'email'], 'required'],
            [['mobile'],'match','pattern'=>'/(13|14|15|17|18|19)[0-9]{9}/','message'=>'请输入正确的手机'],
        ['rePassword','compare','compareAttribute' => 'password'],
            [['checkCode'],'captcha','captchaAction' => 'user/code'],//验证码
            [['captcha'],'validateCaptcha']//自定义规则

        ];
    }
    public function validateCaptcha($attribute, $params)
    {
       /* if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }*/
        //1。通过手机号取出之前发送出去的code
        $codeOld=\Yii::$app->session->get("tel_".$this->mobile);
       // var_dump($this->captcha);
     //   exit($codeOld);

        //2.判断输入8code是否正确
        if ($this->captcha!=$codeOld){

            $this->addError($attribute, '验证码错误');
        }


    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'mobile' => '手机号',
            'login_time' => '登录时间',
            'login_ip' => 'IP地址',
        ];
    }
}
