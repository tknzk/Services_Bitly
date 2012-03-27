<?php

/**
 * An interface for bit.ly
 *
 * @category    Services
 * @package     Services_Bitly
 * @author      tknzk <info@tknzk.com>
 * @copyright   Copyright (c) 2009-2010, tknzk.com All rights reserved.
 * @license     BSD License
 * @link        http://openpear.org/package/Services_Bitly
 * @link        http://bit.ly
 *
 */

require_once 'Services/Bitly/Exception.php';

class Services_Bitly
{
    const API_URL_BITLY = 'http://api.bit.ly';
    const API_URL_JMP   = 'http://api.j.mp';

    const DOMAIN_BITLY  = 'bit.ly';
    const DOMAIN_JMP    = 'j.mp';

    const FORMAT_JSON   = 'json';
    const FORMAT_XML    = 'xml';
    const FORMAT_TXT    = 'txt';

    const API_VERSION   = 'v3';

    const VERSION       = '0.3.1';


    /**
     * login
     *
     * @var string
     * @access private
     */
    private $login;

    /**
     * apiKey
     *
     * @var string
     * @access private
     */
    private $apiKey;

    /**
     * apiVersion
     *
     * @var string
     * @access private
     */
    private $apiVersion;

    /**
     * baseDamain
     *
     * @var string
     * @access private
     */
    private $baseDamain;

    /**
     * baseUrl
     *
     * @var string
     * @access private
     */
    private $baseUrl;

    /**
     * __construct
     *
     * @param string $login
     * @param string $apikey
     * @param string $domain
     * @param string $format
     * @access public
     * @return void
     */
    public function __construct($login, $apikey, $domain = self::DOMAIN_BITLY, $format = self::FORMAT_JSON)
    {
        $this->setLogin($login);
        $this->setApikey($apikey);
        $this->setFormat($format);
        $this->setApiVersion(self::API_VERSION);
        $this->setBaseDomain($domain);
    }

    /**
     * shorten url create
     *
     * @param string $longUrl
     * @access public
     * @return void
     */
    public function shorten($longUrl)
    {
        $queryParameters    = array(
                'login'     => $this->login,
                'apiKey'    => $this->apiKey,
                'uri'       => $longUrl,
                'format'    => $this->format,
                );

        $apiUrl = $this->baseUrl . '/shorten?' . http_build_query($queryParameters);

        $curl   = curl_init();
        curl_setopt($curl,  CURLOPT_URL,            $apiUrl);
        curl_setopt($curl,  CURLOPT_HEADER,         false);
        curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        if ($response === false) {
            throw new Services_Bitly_Exception(curl_error($curl), curl_errno($curl));
        }

        curl_close($curl);

        if ($this->format === self::FORMAT_JSON) {

            $json = json_decode($response, true);

            if ($json['status_code'] == '200' && $json['status_txt'] == "OK") {
                return $json['data']['url'];
            } else {
                throw new Services_Bitly_Exception($json['status_txt'], $json['status_code']);
            }
        }

        if ($this->format === self::FORMAT_XML) {

            $xml = simplexml_load_string($response);

            if ($xml->status_code == '200' && $xml->status_txt == 'OK') {
                return $xml->data->url;
            } else {
                throw new Services_Bitly_Exception($xml->status_txt, $xml->status_code);
            }
        }

        if ($this->format === self::FORMAT_TXT) {
            if ($response) {
                return $response;
            } else {
                throw new Services_Bitly_Exception('UNKNOWN ERROR. if more information: set fomat json or xml.');
            }
        }
    }

    /**
     * expand Long URL
     *
     * @param string $shortUrl
     * @access public
     * @return void
     */
    public function expand($shortUrl)
    {
        $queryParameters    = array(
                'login'     => $this->login,
                'apiKey'    => $this->apiKey,
                'shortUrl'  => trim($shortUrl),
                'format'    => $this->format,
                );

        $apiUrl = $this->baseUrl . '/expand?' . http_build_query($queryParameters);

        $curl   = curl_init();
        curl_setopt($curl,  CURLOPT_URL,            $apiUrl);
        curl_setopt($curl,  CURLOPT_HEADER,         false);
        curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        if ($response === false) {
            throw new Services_Bitly_Exception(curl_error($curl), curl_errno($curl));
        }

        curl_close($curl);

        if ($this->format === self::FORMAT_JSON) {

            $json = json_decode($response, true);

            if ($json['status_code'] == '200' && $json['status_txt'] === 'OK') {
                return $json['data']['expand'][0]['long_url'];
            } else {
                throw new Services_Bitly_Exception($json['status_txt'], $json['status_code']);
            }
        }

        if ($this->format === self::FORMAT_XML) {

            $xml = simplexml_load_string($response);

            if ($xml->status_code == '200' && $xml->status_txt == 'OK') {
                return $xml->data->entry->long_url;
            } else {
                throw new Services_Bitly_Exception($xml->statas_txt, $xml->status_code);
            }
        }

        if ($this->format === self::FORMAT_TXT) {
            if ($response) {
                return $response;
            } else {
                throw new Services_Bitly_Exception('UNKNOWN ERROR. if more information: set fomat json or xml.');
            }
        }
    }

    /**
     * setLogin
     *
     * @param string $login
     * @access private
     * @return void
     */
    private function setLogin($login)
    {
        $this->login = (string) $login;
    }

    /**
     * setApikey
     *
     * @param string $apikey
     * @access private
     * @return void
     */
    private function setApikey($apikey)
    {
        $this->apiKey = (string) $apikey;
    }

    /**
     * setApiVersion
     *
     * @param string $apiversion
     * @access private
     * @return void
     */
    private function setApiVersion($apiversion)
    {
        $this->apiVersion = (string) $apiversion;
    }

    /**
     * setFormat
     *
     * @param string $format
     * @access public
     * @return void
     */
    public function setFormat($format)
    {
        $this->format = (string) $format;
    }

    /**
     * setBaseDomain
     *
     * @param string $domain
     * @access public
     * @return void
     */
    public function setBaseDomain($domain = self::DOMAIN_BITLY)
    {
        $this->baseDomain   = (string) $domain;

        switch($domain) {
            case self::DOMAIN_BITLY:
                $this->baseUrl = self::API_URL_BITLY    . "/" . $this->apiVersion;
                break;
            case self::DOMAIN_JMP:
                $this->baseUrl = self::API_URL_JMP      . "/" . $this->apiVersion;
                break;
            default:
                $this->baseUrl = self::API_URL_BITLY    . "/" . $this->apiVersion;
        }
    }

}
