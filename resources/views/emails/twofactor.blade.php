<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Verification Code</title>
</head>

<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="100%"
                    style="max-width: 600px; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td
                            style="background-color: #4e9d76; padding: 20px; color: #fff; text-align: center; font-size: 22px; font-weight: bold;">
                            Verify It's You
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px; color: #333; font-size: 16px; line-height: 1.6;">
                            <p>
                                Hi
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


                            <p>To complete your login, please use the verification code below. This step helps keep your
                                account secure.</p>

                            <div style="text-align: center; margin: 30px 0;">
                                <span
                                    style="
                                    display: inline-block;
                                    background-color: #e8f5f0;
                                    color: #4e9d76;
                                    font-size: 32px;
                                    font-weight: bold;
                                    letter-spacing: 6px;
                                    padding: 14px 28px;
                                    border-radius: 8px;
                                    border: 2px dashed #4e9d76;
                                ">
                                    {{ $user->two_factor_code }}
                                </span>
                            </div>

                            <p>This code will expire in <strong>5 minutes</strong>. Please enter it promptly to proceed.
                            </p>

                            <p>If you didn’t request this code, we recommend updating your password and contacting
                                support immediately to ensure your account's safety.</p>

                            <p style="margin-top: 40px;">Thank you,<br><strong>Letty’s Birthing Home Team</strong></p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f9f7fc; padding: 20px; text-align: center; font-size: 12px; color: #888;">
                            &copy; {{ date('Y') }} Letty’s Birthing Home. All rights reserved.<br>
                            Need help? <a href="mailto:support@lettybirthinghome.com"
                                style="color: #4e9d76; text-decoration: none;">Contact us</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
