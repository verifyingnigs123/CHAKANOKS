<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Lightweight PHPMailer wrapper configured for Gmail SMTP.
 */
class Mailer
{
    protected PHPMailer $mailer;

    public function __construct()
    {
        $this->loadDependencies();

        $this->mailer = new PHPMailer(true);
        $this->configure();
    }

    /**
    * Ensure PHPMailer classes are available when Composer autoload is absent.
    */
    protected function loadDependencies(): void
    {
        if (!class_exists(PHPMailer::class)) {
            require_once APPPATH . 'ThirdParty/PHPMailer/src/Exception.php';
            require_once APPPATH . 'ThirdParty/PHPMailer/src/PHPMailer.php';
            require_once APPPATH . 'ThirdParty/PHPMailer/src/SMTP.php';
        }
    }

    /**
     * Apply Gmail SMTP defaults.
     */
    protected function configure(): void
    {
        $this->mailer->isSMTP();
        $this->mailer->Host       = 'smtp.gmail.com';
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = 'carlvesteralbarina@gmail.com';
        $this->mailer->Password   = 'wqvaubsveuecvydy';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = 587;
        $this->mailer->CharSet    = 'UTF-8';

        $this->mailer->setFrom('carlvesteralbarina@gmail.com', 'ChakaNoks SCMS');
        $this->mailer->isHTML(true);
    }

    public function sendHtml(string $toEmail, string $subject, string $htmlBody, string $toName = ''): bool
    {
        try {
            $this->mailer->clearAllRecipients();
            $this->mailer->clearAttachments();

            $this->mailer->addAddress($toEmail, $toName ?: $toEmail);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $htmlBody;
            $this->mailer->AltBody = strip_tags($htmlBody);

            return $this->mailer->send();
        } catch (Exception $e) {
            log_message('error', 'PHPMailer send failed: ' . $e->getMessage());
            return false;
        }
    }
}

