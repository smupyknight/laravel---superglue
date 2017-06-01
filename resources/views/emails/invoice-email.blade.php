<body>
<div text="#000000" style="background-color:#ffffff;width:100%!important">
    <div>
        <table style="font-size:11px;line-height:14px;color:#666666;background-image:url({{ url('img/bg-img.jpg') }});background-repeat:repeat;font-family:Helvetica,Arial,sans-serif" border="0" cellspacing="0" cellpadding="0" width="100%" align="center" bgcolor="#e4e4e4">
            <tbody>
                <tr>
                    <td>
                        <table style="font-size:11px;line-height:14px;color:#666666" border="0" cellspacing="0" cellpadding="0" width="648" align="center">
                            <tbody>
                                <tr>
                                    <td style="line-height:0;font-size:0" height="47">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="font-size:13px;line-height:18px;color:#666666;border-radius:0px;border:#ccc 1px solid" border="0" cellspacing="0" cellpadding="0" width="648" align="center" bgcolor="#ffffff">
                            <tbody>
                                <tr>
                                    <td>
                                        <table style="font-size:13px;line-height:18px;color:#666666" border="0" cellspacing="0" cellpadding="0" width="648" align="center">
                                            <tbody>
                                                <tr>
                                                    <td width="15px">&nbsp;</td>
                                                    <td>
                                                        {{-- <p style="font-size:10px;"><a href="#" target="_blank"style="color:#bbb;">View in your browser</a></p> --}}
                                                    </td>
                                                    <td width="15px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="15px">&nbsp;</td>
                                                    <td align="center" style="background-color:#fcdb32;">
                                                        <p style="margin: 25px 0;">
                                                            <a href="#" target="_blank" style="display: inline-block;"><img style="border:0px; vertical-align: middle;" src="{{ url('img/logo.png') }}" alt="logo-newpig.png" width="91" height="91"></a>
                                                        </p>
                                                    </td>
                                                    <td width="15px">&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table style="font-size:13px;line-height:18px;color:#666666" border="0" cellspacing="0" cellpadding="0" width="648" align="center">
                                            <tbody>
                                                <tr>
                                                    <td style="line-height:0;font-size:0" colspan="3" height="40">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="40">&nbsp;</td>
                                                    <td>
                                                        <h2 style="font-size: 24px; font-weight: 500; margin: 10px 0px;">Hi {{ ucwords($invoice->account->name) }},</h2>
                                                    </td>
                                                    <td width="40">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="40">&nbsp;</td>
                                                    <td>
                                                        <p>Great news, your membership renewal has been successfully processed. To view your invoice, please follow the link below.</p>
                                                    </td>
                                                    <td width="40">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="line-height:0;font-size:0" colspan="3" height="35">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size:0px;line-height:0px" width="40">&nbsp;</td>
                                                    <td style="font-size:0px;line-height:0px">
                                                        <table style="font-size:13px;line-height:18px;color:#666666" border="0" cellspacing="0" cellpadding="0" width="568" align="center">
                                                            <tbody>
                                                                <td width="264">
                                                                    <a href="{{ request()->root() }}/invoices/view/{{ $invoice->id }}" style="text-decoration:none;">
                                                                        <div style="background-color:#fcdb32; text-align:center; box-shadow: 2px 2px 2px #999;">
                                                                            <img src="{{ url('img/desktop.png') }}" style="height:28px; margin: 0 15px -9px 0;">
                                                                            <span style="font-size: 15px; display: inline-block; margin: 22px 0; color:#333;">View Invoice</span>
                                                                        </div>
                                                                    </a>
                                                                </td>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td style="font-size:0px;line-height:0px" width="40">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="line-height:0;font-size:0" colspan="3" height="30">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td width="40">&nbsp;</td>
                                                    <td style="font-size:14px; line-height:20px;" align="center"><p><a href="{{ request()->root() }}/account" style="color:#333;">Your Account</a></p></td>
                                                    <td width="40">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td style="line-height:0;font-size:0" colspan="3" height="20">&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="font-size:13px;line-height:18px;color:#666666" border="0" cellspacing="0" cellpadding="0" width="650" align="center">
                            <tbody>
                                <tr>
                                    <td style="line-height:0;font-size:0" colspan="3" height="20">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width="10">&nbsp;</td>
                                    <td style="text-align:center;">
                                        © Superglue.lt2 © Little Tokyo Two<br> You are receiving this email because you are a member of Little Tokyo Two <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="line-height:0;font-size:0" colspan="3" height="40">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
