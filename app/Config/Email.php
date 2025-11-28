<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * Email address to send from
     * For Gmail: Use your Gmail address (e.g., yourname@gmail.com)
     */
    public string $fromEmail  = '';

    /**
     * Name to display as sender
     */
    public string $fromName   = 'ChakaNoks SCMS';

    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     * For Gmail, use 'smtp'
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     * For Gmail: smtp.gmail.com
     */
    public string $SMTPHost = 'smtp.gmail.com';

    /**
     * SMTP Username
     * For Gmail: Your full Gmail address (e.g., yourname@gmail.com)
     */
    public string $SMTPUser = '';

    /**
     * SMTP Password
     * For Gmail: Use an App Password (not your regular password)
     * To generate: Google Account > Security > 2-Step Verification > App passwords
     */
    public string $SMTPPass = '';

    /**
     * SMTP Port
     * For Gmail with TLS: 587
     * For Gmail with SSL: 465
     */
    public int $SMTPPort = 587;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 5;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to 'ssl'.
     * For Gmail with port 587: use 'tls'
     * For Gmail with port 465: use 'ssl'
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     * Set to 'html' for formatted emails
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}
