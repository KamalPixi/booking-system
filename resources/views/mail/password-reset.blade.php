@extends('mail.layout')
@section('content')
<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
    <tr>
        <td align="center" valign="top">
            <table class="contenttable" border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#ffffff" style="border-width:1px; border-style: solid; border-collapse: separate; border-color:#ededed; margin-top:20px; font-family:Arial, Helvetica, sans-serif">
                <tr>
                    <td>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td width="100%" height="30">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td valign="top" align="center"><a href="#"><img alt="" src="{{ asset('assets/images/logo/logo.png') }}" style="padding-bottom: 0; display: inline !important;"></a></td>
                                </tr>
                                <tr>
                                    <td width="100%" height="10">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width="100%" height="20" style="font-size:16px; font-weight:600; color:#777; line-height:26px; padding-right:20px; text-align: center;">{{ $title }}</td>
                                </tr>
                                <tr>
                                    <td width="100%" height="10">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0px 20px;">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                                <tr>
                                    <td style="border:4px solid #eee; border-radius:4px; padding:25px 0px;">
                                        <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size:16px; font-weight:600; color:#777; line-height:26px; padding-right:20px; text-align: center;"><span style="font-size:13px;">Hi {{ $user->name }},</span><br>
                                                        Your new Password is: <span style="background-color:#eeeeee; padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;">{{ $new_password }}</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td style="font-size:14px; line-height:28px; text-align: center;">Thank you.</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            @include('mail.footer')
        </td>
    </tr>
</table>
@endsection
