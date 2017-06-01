<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Pyament Failed</title>
    <link href="{{ url('/css/emails/styles.css') }}" media="all" rel="stylesheet" type="text/css" />
</head>

<body>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap">
                            <table  cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <img class="img-responsive" src="{{ url('/img/email-bg.jpg') }}"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <h3>Payment Failed :(</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        We were unable to process your invoice. Please update your payment details in the SuperGlue application to avoid any disruption to your membership. If you haven't yet received your invite to SuperGlue, please email <a href="mailto:superglue@littletokyotwo.com">superglue@littletokyotwo.com</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block aligncenter">
                                        <a href="{{ request()->root() }}" class="btn-primary">Login to SuperGlue</a>
                                    </td>
                                </tr>
                              </table>
                        </td>
                    </tr>
                </table>
                <div class="footer">
                    <table width="100%">
                        <tr>
                            <td class="aligncenter content-block"> © Superglue.lt2 © Little Tokyo Two<br> You are receiving this email because you applied for a membership with or are an existing member of Little Tokyo Two</td>
                        </tr>
                    </table>
                </div></div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>
