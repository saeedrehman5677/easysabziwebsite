<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <title>{{ translate('Forgot Password') }}</title>

    <style>

        body {
            background-color: #FFFFFF;
            padding: 0;
            margin: 0;
        }
    </style>

</head>

<body style="background-color: #FFFFFF; padding: 0; margin: 0;">

<table border="0" cellpadding="0" cellspacing="10" height="100%" bgcolor="#FFFFFF" width="100%"
       style="max-width: 650px;" id="bodyTable">

    <tr>

        <td align="center" valign="top">

            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="emailContainer"
                   style="font-family:Arial; color: #333333;">

                <!-- Logo -->
                @php($logo=\App\Model\BusinessSetting::where(['key'=>'logo'])->first()->value)
                <tr>

                    <td align="left" valign="top" colspan="2"
                        style="border-bottom: 1px solid #CCCCCC; padding-bottom: 10px;">
                        <img alt="" border="0" src="{{url('/').'/storage/app/public/restaurant/'.$logo}}" title=""
                             class="sitelogo" width="60%" style="max-width:250px;"/>
                    </td>

                </tr>

                <!-- Title -->

                <tr>

                    <td align="left" valign="top" colspan="2"
                        style="border-bottom: 1px solid #CCCCCC; padding: 20px 0 10px 0;">
                        <span style="font-size: 18px; font-weight: normal;">{{ translate('FORGOT PASSWORD') }}</span>
                    </td>

                </tr>

                <!-- Messages -->

                <tr>

                    <td align="left" valign="top" colspan="2" style="padding-top: 10px;">

                        <span style="font-size: 12px; line-height: 1.5; color: #333333;">

                            {{ translate('We have sent you this email in response to your request to reset your password. After you reset your password, you will be able to login with your new password.') }}

                            <br/><br/>

                            {{ translate('To reset your password, please use the token below :') }}

                            <a href="javascript:"> <h3 style="font-weight: 1000">{{$token}}</h3> </a>

                            <br/><br/>

                            {{ translate('We recommend that you keep your password secure and not share it with anyone.If you feel your password has been compromised, you can change it by going to your app, My Account Page and clicking on the "Change Email Address or Password" link.') }}

                            <br/><br/>

                            {{ translate('If you need help, or you have any other questions, feel free to email us.') }}

                            <br/><br/>

                            {{ translate('From Customer Service') }}

                        </span>

                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>

</body>

</html>
