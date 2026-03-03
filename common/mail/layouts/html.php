<?php

use yii\helpers\Url;

/* @var string $content */

?>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>StraightLine Trucking</title>
    <style>
        @media only screen and (max-width: 620px) {
            table {
                font-size: 11px !important;
            }

            table.body .header a {
                font-size: 12px !important;
            }

            table.body .content {
                padding: 0 !important;
            }

            table.body .container {
                padding: 0 !important;
                width: 100% !important;
            }

            table.body .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }

            table.body .btn table,
            table.body .btn a {
                width: 100% !important;
            }

            table.body td.empty-column {
                width: 0 !important;
                max-width: 0 !important;
            }

            table.body .title {
                font-size: 20px ! important;
            }

            table.body .subtitle {
                font-size: 14px ! important;
            }

            .w-auto {
                width: auto !important;
            }
        }
    </style>
</head>
<body style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.5; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body"
       style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f6f6f6; width: 100%;"
       width="100%" bgcolor="#f6f6f6">
    <tr>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top" class="empty-column">
            &nbsp;
        </td>
        <td class="container"
            style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; max-width: 700px; padding: 10px; width: 700px; margin: 0 auto;"
            width="580" valign="top">
            <div class="content" style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 700px;">

                <!-- START HEADER -->
                <div class="header" style="clear: both;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                           style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;"
                           width="100%">
                        <tr>
                            <td style="width: 50%; padding-right: 20px;" valign="center">
                                <a href="<?= Yii::$app->params['backendUrl'] ?>" style="outline:none" tabindex="-1"
                                   target="_blank">
                                    <img alt="Logo" src="<?= Url::to('/img/logo.png', true) ?>"
                                         style="text-decoration: none; -ms-interpolation-mode: bicubic; height: auto; border: none; width: 100%; max-width: 201px; display: block;"
                                         title="Image" width="201"/>
                                </a>
                            </td>
                            <td style="width: 50%; text-align: right;" valign="center">
                                <a href="mailto:<?= Yii::$app->params['contact']['email'] ?>">
                                    <?= Yii::$app->params['contact']['email'] ?>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- END HEADER -->

                <!-- START CENTERED WHITE CONTAINER -->
                <table role="presentation" class="main"
                       style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #ffffff; border-radius: 3px; width: 100%;"
                       width="100%">

                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td style="font-family: sans-serif; font-size: 13px; vertical-align: top; box-sizing: border-box; max-width: 700px;overflow: hidden;"
                            valign="top">
                            <?= $content ?>
                        </td>
                    </tr>
                    <!-- END MAIN CONTENT AREA -->

                </table>
                <!-- END CENTERED WHITE CONTAINER -->

                <!-- START FOOTER -->
                <div class="footer" style="clear: both; margin-top: 10px; text-align: center; width: 100%;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                           style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;"
                           width="100%">
                        <tr>
                            <td class="content-block"
                                style="font-family: sans-serif; vertical-align: top; padding-bottom: 20px; padding-top: 20px; color: #999999; font-size: 12px; text-align: center;"
                                valign="top" align="center">
                                Copyright © 2022. All rights reserved.
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- END FOOTER -->

            </div>
        </td>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top" class="empty-column">
            &nbsp;
        </td>
    </tr>
</table>
</body>
</html>
