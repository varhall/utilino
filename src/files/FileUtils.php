<?php

namespace Varhall\Utilino\Files;

class FileUtils
{
    public static function fromUrl($url, $filename = NULL, $params = [])
    {
        if (empty($filename))
            $filename = preg_replace('/[?#].*$/', '', basename($url));

        if (!empty($params))
            $url .= '?' . http_build_query($params);

        $tmp = tmpfile();

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_URL             => $url,
            CURLOPT_USERAGENT       => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.91 Safari/537.36',
            CURLOPT_SSL_VERIFYPEER  => FALSE,
            CURLOPT_FILE            => $tmp,
            CURLOPT_FOLLOWLOCATION  => TRUE,
        ]);

        curl_exec($curl);
        curl_close($curl);

        return self::fromFile($tmp, $filename);
    }

    public static function fromBase64($base64, $filename = NULL)
    {
        $data = self::parseBase64($base64);

        if (!$data)
            throw new \Nette\InvalidArgumentException('Given string is not valid base64 file');

        return self::fromBinary(base64_decode($data['content']), $filename);
    }

    public static function fromBinary($data, $filename = NULL)
    {
        $tmp = tmpfile();
        fwrite($tmp, $data);

        return self::fromFile($tmp, $filename);
    }

    public static function fromFile($resource, $filename = NULL)
    {
        $resourceName = stream_get_meta_data($resource)['uri'];

        if (empty($filename))
            $filename = 'unknown_file';

        $_FILES['file_' . \Nette\Utils\Strings::webalize($filename)] = $resource;

        return new \Nette\Http\FileUpload([
            'name'      => $filename,
            'tmp_name'  => $resourceName,
            'type'      => finfo_file(finfo_open(FILEINFO_MIME_TYPE), $resourceName),      // automatically retrieved
            'size'      => filesize($resourceName),
            'error'     => 0
        ]);
    }

    protected static function parseBase64($data)
    {
        // validates Data URI scheme (https://en.wikipedia.org/wiki/Data_URI_scheme)
        if (!is_string($data) || !preg_match('/^data:.+;.+,.+$/i', substr($data, 0, 200)))
            return NULL;

        list($head, $content) = explode(',', $data, 2);

        $head = str_replace('data:', '', $head);
        list($type) = explode(';', $head);

        return [
            'type'      => $type,
            'content'   => $content
        ];
    }
}