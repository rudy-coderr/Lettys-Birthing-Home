<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Staff Account</title>
</head>

<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="100%"
                    style="max-width: 600px; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #4e9d76; padding: 20px; color: #fff; text-align: center; font-size: 22px; font-weight: bold;">
                            Welcome to Letty’s Birthing Home!
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px; color: #333; font-size: 16px; line-height: 1.6;">
                            <p>
                                Hello 
                                <strong>
                                    @if ($user->role === 'admin' && $user->admin)
                                        {{ $user->admin->first_name }} {{ $user->admin->last_name }}
                                    @elseif($user->role === 'staff' && $user->staff)
                                        {{ $user->staff->first_name }} {{ $user->staff->last_name }}
                                    @else
                                        {{ $user->email }}
                                    @endif
                                </strong>,
                            </p>

                            <p>Your staff account has been created successfully. Below are your login details:</p>

                            <div style="margin: 25px 0; padding: 20px; background-color: #e8f5f0; border: 1px dashed #4e9d76; border-radius: 8px;">
                                <p style="margin: 8px 0; font-size: 16px;">
                                    <strong>Email:</strong> {{ $email }}
                                </p>
                                <p style="margin: 8px 0; font-size: 16px;">
                                    <strong>Password:</strong> {{ $password }}
                                </p>
                            </div>

                            <p>⚠️ For your security, please log in immediately and change your password.</p>

                            <p style="margin-top: 40px;">Thank you,<br><strong>Letty’s Birthing Home Admin Team</strong></p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9f7fc; padding: 20px; text-align: center; font-size: 12px; color: #888;">
                            &copy; {{ date('Y') }} Letty’s Birthing Home. All rights reserved.<br>
                            Need help? <a href="mailto:support@lettybirthinghome.com" style="color: #4e9d76; text-decoration: none;">Contact us</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
