<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>

<body style="font-family: 'Poppins', Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background: #ffffff; border-radius: 12px; overflow: hidden;">
                    <tr>
                        <td align="center" style="padding: 20px; background: #52b788;">
                            <h1 style="color: white; margin: 10px 0 0; font-size: 20px;">Letty's Birthing Home</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px; color: #333333; font-size: 16px; line-height: 1.5;">
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


                            <p>You have requested to reset your password. Click the button below to set a new password:
                            </p>
                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $resetUrl }}"
                                    style="background: #52b788; color: white; padding: 12px 20px; text-decoration: none; border-radius: 6px; font-weight: bold;">Reset
                                    Password</a>
                            </p>
                            <p>If you did not request this, you can ignore this email.</p>
                            <p style="color: #777; font-size: 14px;">This link will expire soon for security reasons.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #777;">
                            &copy; {{ date('Y') }} Letty's Birthing Home. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
