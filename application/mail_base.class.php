<?php
/**
 * SMTP mailer facade.
 *
 * Uses PHPMailer 7.1.1 (PHP 5.5+), while preserving the legacy public API
 * used throughout this application.
 */

require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class baseMailler
{
    private static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Creates a fresh SMTP client for each message so recipients cannot leak
     * from one request/message to the next.
     *
     * @return PHPMailer|null
     */
    private function createMailer()
    {
        if (!defined('MAIL_HOST') || !defined('MAIL_PORT') || !defined('MAIL_ACC') ||
            !defined('MAIL_PASS') || MAIL_HOST === '' || MAIL_ACC === '' || MAIL_PASS === '') {
            error_log('Email is not sent: SMTP configuration is incomplete.');
            return null;
        }

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->Port = (int) MAIL_PORT;
        $mail->SMTPAuth = defined('MAIL_AUTH') ? (bool) MAIL_AUTH : true;
        $mail->Username = MAIL_ACC;
        $mail->Password = MAIL_PASS;
        $mail->SMTPSecure = defined('MAIL_SECURE') ? MAIL_SECURE : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->Encoding = PHPMailer::ENCODING_BASE64;
        $mail->SMTPAutoTLS = true;
        $mail->Timeout = 15;

        return $mail;
    }

    private function deliver($from, $fromName, $to, $toName, $subject, $html)
    {
        $mail = $this->createMailer();
        if (!$mail || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Gmail and most authenticated SMTP providers reject a spoofed From.
        // MAIL_FROM permits a separately verified sender; otherwise use the
        // authenticated account and preserve a legacy address only as Reply-To.
        $replyTo = filter_var($from, FILTER_VALIDATE_EMAIL) ? $from : MAIL_ACC;
        $from = defined('MAIL_FROM') && filter_var(MAIL_FROM, FILTER_VALIDATE_EMAIL)
            ? MAIL_FROM
            : MAIL_ACC;

        try {
            $mail->setFrom($from, $fromName ?: 'Cổng thông tin việc làm');
            $mail->addAddress($to, $toName);
            $mail->addReplyTo($replyTo, $fromName ?: 'Cổng thông tin việc làm');
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $html;
            $mail->AltBody = trim(html_entity_decode(strip_tags(str_replace(array('<br>', '<br/>', '<br />'), "\n", $html)), ENT_QUOTES, 'UTF-8'));

            return $mail->send();
        } catch (Exception $exception) {
            error_log('Email is not sent: ' . $mail->ErrorInfo);
            return false;
        }
    }

    public function send($sendname, $from, $name, $to, $subject, $content)
    {
        return $this->deliver($from, $sendname, $to, $name, $subject, $content);
    }

    public function sendStudentPasswordEmail($name, $to, $username, $password, $subject)
    {
        $html = $this->messageTemplate(
            'Thông tin tài khoản đăng nhập',
            'Xin chào ' . $this->escape($name) . ',',
            '<p>Tài khoản của bạn đã được tạo. Vui lòng đăng nhập và đổi mật khẩu sau lần đăng nhập đầu tiên.</p>' .
            '<p><strong>Tên đăng nhập:</strong> ' . $this->escape($username) . '<br><strong>Mật khẩu:</strong> ' . $this->escape($password) . '</p>'
        );
        return $this->deliver(MAIL_ACC, 'Trường Cao đẳng Kon Tum', $to, $name, $subject, $html);
    }

    public function sendVerifyEmail($name, $to, $token, $subject)
    {
        $link = rtrim(XC_URL, '/') . '/verify_email/' . rawurlencode($token);
        $html = $this->messageTemplate(
            'Xác thực email',
            'Xin chào ' . $this->escape($name) . ',',
            '<p>Vui lòng xác thực email của bạn để hoàn tất đăng ký.</p>' . $this->button($link, 'Xác thực email ngay')
        );
        return $this->deliver(MAIL_ACC, 'Trường Cao đẳng Kon Tum', $to, $name, $subject, $html);
    }

    public function sendPasswordResetEmail($name, $to, $resetLink, $subject)
    {
        $safeLink = $this->escape($resetLink);
        $html = $this->messageTemplate(
            'Yêu cầu đổi mật khẩu',
            'Xin chào ' . $this->escape($name) . ',',
            '<p>Chúng tôi đã nhận được yêu cầu đổi mật khẩu. Liên kết có hiệu lực trong 5 phút.</p>' .
            $this->button($safeLink, 'Đổi mật khẩu ngay') . '<p>Nếu bạn không yêu cầu, hãy bỏ qua email này.</p>'
        );
        return $this->deliver(MAIL_ACC, 'Trường Cao đẳng Kon Tum', $to, $name, $subject, $html);
    }

    public function sendAdminTwoFactorCode($name, $to, $code, $subject)
    {
        $html = $this->messageTemplate(
            'Mã xác thực quản trị',
            'Xin chào ' . $this->escape($name) . ',',
            '<p>Mã xác thực hai lớp của bạn là:</p><p style="font-size:28px;font-weight:bold;letter-spacing:5px">' . $this->escape($code) . '</p><p>Mã có hiệu lực trong 2 phút.</p>'
        );
        return $this->deliver(MAIL_ACC, 'Cổng thông tin việc làm', $to, $name, $subject, $html);
    }

    // Legacy CRM entry points retained for backwards compatibility.
    public function newaccount($name, $to, $username, $activelink)
    {
        $body = $this->template('new_account');
        $body = str_replace(array('%%NAME%%', '%%USERNAME%%', '%%ACTIVE_LINK%%'), array($name, $username, $activelink), $body);
        return $this->deliver(MAIL_ACC, 'Seagull CRM', $to, $name, 'Kích hoạt tài khoản mới Seagull CRM', $body);
    }

    public function cmsn($name, $to)
    {
        return $this->deliver(MAIL_ACC, 'Seagull CRM', $to, $name, 'Seagull Hotel - Chúc mừng sinh nhật anh Duy Nhân', $this->template('cmsn'));
    }

    public function send2($sendname, $from, $name, $to, $subject, $content)
    {
        return $this->deliver($from, $sendname, $to, $name, $subject, $content ?: $this->template('return_notification_email'));
    }

    public function sendtask($sendname, $from, $name, $to, $subject, $content)
    {
        $body = $content ?: $this->template('email-markup');
        return $this->deliver($from, $sendname, $to, $name, $subject, str_replace('%%STAFF_NAME%%', $name, $body));
    }

    public function mail($name)
    {
        return $this->template($name);
    }

    public function sendersmtp($name, $to, $type, $content, $value)
    {
        $subjects = array(
            'newregister' => 'Thank for signup at Xiao Media Account Gateway!',
            'forgotpass' => 'Forgot password - Xiao Media Account Gateway!',
            'newpass' => 'Forgot password - Xiao Media Account Gateway!'
        );
        if (!isset($subjects[$type])) {
            return false;
        }
        return $this->deliver(MAIL_ACC, 'Xiao Passport Gateway', $to, $name, $subjects[$type], $content);
    }

    private function template($name)
    {
        $path = __SITE_PATH . '/template/emails/' . basename($name) . '.html';
        return is_file($path) ? file_get_contents($path) : '';
    }

    private function escape($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    private function button($url, $label)
    {
        return '<p style="text-align:center"><a href="' . $url . '" style="display:inline-block;padding:12px 22px;background:#0d4e96;color:#fff;text-decoration:none;border-radius:5px">' . $this->escape($label) . '</a></p>';
    }

    private function messageTemplate($title, $greeting, $content)
    {
        return '<!doctype html><html><body style="margin:0;background:#f4f7fb;font-family:Arial,sans-serif;color:#333"><div style="max-width:620px;margin:30px auto;background:#fff"><div style="padding:24px;background:#0d4e96;color:#fff"><h2 style="margin:0">' . $this->escape($title) . '</h2></div><div style="padding:30px;line-height:1.6"><h3>' . $greeting . '</h3>' . $content . '</div><div style="padding:16px;text-align:center;background:#f1f3f6;font-size:12px;color:#777">© ' . date('Y') . ' Cổng thông tin việc làm</div></div></body></html>';
    }
}
