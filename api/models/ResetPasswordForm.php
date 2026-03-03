<?php


namespace api\models;

use yii\base\Model;

class ResetPasswordForm extends Model
{
    public $token;
    public $password;
    public $confirm_password;

    protected $user;

    public function rules()
    {
        return [
            [['token', 'password', 'confirm_password'], 'required'],
            [['token'], 'string'],
            [['password'], 'compare', 'compareAttribute' => 'confirm_password'],
            [['password', 'confirm_password'], 'string', 'min' => 6],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            $this->addError('user', $this->getFirstErrors());
            return false;
        }

        $this->user = User::findByPasswordResetToken($this->token);

        if (!$this->user) {
            $this->addError('token', 'Wrong password reset token.');
            return false;
        }

        $this->user->setPassword($this->password);
        $this->user->password_reset_token = null;

        if (!$this->user->save()) {
            $this->addError('user', $this->user->getFirstErrors());
            return false;
        }

        return true;
    }
}