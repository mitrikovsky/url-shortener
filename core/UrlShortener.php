<?php

namespace core;

use PDO;
use PDOException;

class UrlShortener
{
    private $pdo;

    /**
     * UrlShortener constructor.
     */
    public function __construct()
    {
        // Connect to DB
        try {
            $this->pdo = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' .
                DB_DATABASE, DB_USERNAME, DB_PASSWORD);
        } catch (PDOException $e) {
            echo '<p>PDO Error: ' . $e->getMessage() . '</p>';
            exit;
        }
    }

    /**
     * Returns original URL by the short code
     *
     * @param string $code Unique short code of the URL
     * @return bool|string Original URL or false if not found
     */
    public function getUrlByCode($code)
    {
        $query = 'SELECT url FROM urls WHERE code = :code';

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['code' => $code]);
        $result = $stmt->fetch();

        return (empty($result)) ? false : $result['url'];
    }

    /**
     * Returns unique short code for the URL
     * Gets from DB or generates new and saves to DB if not exists
     *
     * @param string $url Original URL
     * @return string Short code for the url
     */
    public function getCodeByUrl($url)
    {
        if (!$code = $this->checkUrl($url)) {
            // if url not exists in DB generate new
            // and make collisions protection
            do {
                $code = $this->generateCode(6);
            } while ($this->codeExists($code));
            // save url and unique code to DB
            $this->save($code, $url);
        }

        return $code;
    }

    /**
     * Generates random alphanumeric code with given length
     *
     * @param int $length Length of the code
     * @return string Random code
     */
    private function generateCode($length)
    {
        $keys = array_merge(
            range(1, 9),
            range('a', 'z'),
            range('A', 'Z')
        );

        $code = '';
        $maxIndex = count($keys) - 1;
        for ($i = 0; $i < $length; $i++) {
            $code .= $keys[mt_rand(0, $maxIndex)];
        }

        return $code;
    }

    /**
     * Checks the URL for existence in DB
     * Returns short code if exists or false if not
     *
     * @param string $url URL for checking
     * @return bool|string Short code of the URL or false if not exists
     */
    private function checkUrl($url)
    {
        $query = 'SELECT code FROM urls WHERE url = :url LIMIT 1';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['url' => $url]);
        $result = $stmt->fetch();

        return (empty($result)) ? false : $result['code'];
    }

    /**
     * Checks the short code for existence in DB
     *
     * @param string $code Short code for checking
     * @return bool
     */
    private function codeExists($code)
    {
        $query = 'SELECT * FROM urls WHERE code = :code';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['code' => $code]);
        $result = $stmt->fetch();

        return !empty($result);
    }

    /**
     * Saves URL and its short code into DB
     *
     * @param string $code Short code of the URL
     * @param string $url Original URL
     */
    private function save($code, $url)
    {
        $query = 'INSERT INTO urls (code, url) VALUES (:code, :url)';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['code' => $code, 'url' => $url]);
    }
}
