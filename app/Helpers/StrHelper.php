<?php

use \Illuminate\Http\Request;

if (! function_exists('test'))
{
    function getUri(Request $request)
    {
        $url = 'https://' . ($request->url ?: 'aasumitro.id') ;
        $path = $request->path ?: 'notes';
        $param = extractParam($request->param);

        return $url . '/' . $path . '?' . $param;
    }
}

if (! function_exists('extractParam'))
{
    function extractParam($param)
    {
        if (is_null($param)) return '';

        $param = explode('.', $param);

        $extracted_param = '';

        foreach ($param as $key => $value)
        {
            $clean_up = str_replace(['[', ']'], '', $value);

            $extract = explode(',', $clean_up);

            $extracted_param .= $extract[0] . '=' . $extract[1];
            $extracted_param .= (next($param) == true) ? '&' : '';
        }

        return $extracted_param;
    }
}
