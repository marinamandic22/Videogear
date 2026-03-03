<?php

/*
 * This file is part of the 2amigos project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace api\components\web;

/**
 * Response formatter for represent data in tag <pre>
 *
 * It is used by [[Response]] to format response data.
 */
class HtmlResponseFormatter extends \yii\web\HtmlResponseFormatter
{
    /**
     * Formats the specified response.
     *
     * @param \yii\web\Response $response the response to be formatted.
     */
    public function format($response)
    {
        parent::format($response);
        if (!is_string($response->content)) {
            $response->content =
                "<PRE>"
                . var_export($response->content, true)
                . "</PRE>";
        }
    }
}
