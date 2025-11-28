# Email Configuration Guide for Franchise Application Notifications

This guide will help you configure Gmail SMTP to send email notifications when franchise applications are approved or rejected.

## Prerequisites

- A Gmail account
- 2-Step Verification enabled on your Gmail account

## Step 1: Enable 2-Step Verification

1. Go to your Google Account settings: https://myaccount.google.com/
2. Navigate to **Security**
3. Under "Signing in to Google", enable **2-Step Verification**
4. Follow the setup process

## Step 2: Generate an App Password

1. Go to your Google Account settings: https://myaccount.google.com/
2. Navigate to **Security**
3. Under "Signing in to Google", click on **2-Step Verification**
4. Scroll down and click on **App passwords**
5. Select **Mail** as the app and **Other (Custom name)** as the device
6. Enter "ChakaNoks SCMS" as the custom name
7. Click **Generate**
8. **Copy the 16-character password** (you'll need this in the next step)

## Step 3: Configure Email Settings

1. Open the file: `app/Config/Email.php`
2. Update the following settings:

```php
// Your Gmail address
public string $fromEmail  = 'your-email@gmail.com';

// Display name for emails
public string $fromName   = 'ChakaNoks SCMS';

// SMTP settings for Gmail
public string $protocol = 'smtp';
public string $SMTPHost = 'smtp.gmail.com';
public string $SMTPUser = 'your-email@gmail.com';  // Your Gmail address
public string $SMTPPass = 'your-16-char-app-password';  // The app password from Step 2
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls';
public string $mailType = 'html';
```

## Step 4: Test the Configuration

1. Log in as a Central Admin
2. Go to **Franchise Applications** in the sidebar
3. View any pending application
4. Click **Approve** or **Reject**
5. Check the applicant's email inbox for the notification

## Troubleshooting

### Emails Not Sending

1. **Check the logs**: Look in `writable/logs/` for error messages
2. **Verify App Password**: Make sure you're using the App Password, not your regular Gmail password
3. **Check SMTP Settings**: Ensure `SMTPPort` is 587 and `SMTPCrypto` is 'tls'
4. **Firewall Issues**: Make sure your server can connect to smtp.gmail.com on port 587

### Common Errors

- **"Failed to authenticate"**: Your App Password is incorrect
- **"Connection timeout"**: Firewall blocking port 587
- **"Invalid email address"**: Check the `fromEmail` and `SMTPUser` settings

## Alternative: Using Other Email Providers

### Outlook/Hotmail
```php
public string $SMTPHost = 'smtp-mail.outlook.com';
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls';
```

### Yahoo Mail
```php
public string $SMTPHost = 'smtp.mail.yahoo.com';
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls';
```

## Security Notes

- **Never commit** your App Password to version control
- Consider using environment variables for sensitive credentials
- Regularly rotate your App Passwords
- Keep your Gmail account secure with 2-Step Verification

## Support

If you continue to experience issues, check:
1. CodeIgniter logs: `writable/logs/`
2. Server error logs
3. Email service provider status

