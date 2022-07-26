<table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size:13px;color:#777777; font-family:Arial, Helvetica, sans-serif">
    <tbody>
        <tr>
            <td class="tablepadding" style="padding:20px 0; border-collapse:collapse">
                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size:13px;color:#777777; font-family:Arial, Helvetica, sans-serif">
                    <tbody>
                        <tr>
                            <td align="center" class="tablepadding" style="line-height:20px; padding:20px;">
                                {{env('APP_NAME')}}, {{ \App\Helpers\ConstantHelper::AGENT['address'] }}.
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table align="center">
                    <tr>
                        <td style="padding-right:10px; padding-bottom:9px;"><a href="#" target="_blank" style="text-decoration:none; outline:none;"><img src="{{ asset('assets/images/social/facebook.png') }} " width="32" height="32" alt=""></a></td>
                        <td style="padding-right:10px; padding-bottom:9px;"><a href="#" target="_blank" style="text-decoration:none; outline:none;"><img src="{{ asset('assets/images/social/twitter.png') }}" width="32" height="32" alt=""></a></td>
                    </tr>
                </table>
                <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size:13px;color:#777777; font-family:Arial, Helvetica, sans-serif">
                    <tbody>
                        <tr>
                            <td class="tablepadding" align="center" style="line-height:20px; padding-top:10px; padding-bottom:20px;">Copyright &copy; {{ date('Y') }} {{ env('APP_NAME') }}. All Rights Reserved.</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
