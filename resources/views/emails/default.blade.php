<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
</head>

<body>
<table cellpadding="0" cellspacing="0" border="0" style='background-color: #F1F1F1' width="100%">
    <tr>
        <td width="15">
            &nbsp;
        </td>
        <td align="center" style="padding:15px 0;">
            <table cellpadding="0" align="center" cellspacing="0" border="0" style='border: 0;' width="700">
                <tr>
                    <td style="border: 1px solid #add157;" bgcolor="#ADD157">
                        <img src="{{ asset('/images/logo_email.png') }}" width="100">
                    </td>
                </tr>
                <tr style="text-align: center;">
                    <td style="border: 1px solid #add157;" bgcolor="#FFFFFF">
                        <p>
                            &nbsp;
                        </p>
                        @yield('content')
                        <p>
                            &nbsp;
                        </p>
                        <p style="text-align: left; margin: 5px;">
                            <font color="#565656" size="2">&nbsp;&nbsp;&nbsp;- <a href="{{ url('/') }}" target="_blank">allfor2.com</a></font>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
        <td width="15">
            &nbsp;
        </td>
    </tr>
</table>
</body>
</html>