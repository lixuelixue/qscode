<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/26
 * Time: 11:44
 */

namespace Home\Controller;
use Think\Controller;

class RunController extends Controller
{
    public function run(){
        $this -> display();
    }

    public function map(){
        $this -> display();
    }

    public function video(){


        $this -> display();
    }

    public function fire(){
        $this -> display();
    }
    public function ptz(){
        if($_GET != "" && $_GET['brand'] != undefined){
            $ptz = $_GET['ptz'];
            $b =  $_GET['brand'];
        }
        if($b == "dahua"){
            $url = "http://192.168.1.108/onvif/ptz_service";
            $user = "admin";
            $pwd = "admin";
            $profiletoken = "MediaProfile000";
        }else if($b == "haikang"){
            $url = "http://192.168.1.64/onvif/PTZ";
            $user = "admin";
            $pwd = "admin123";
            $profiletoken = "Profile_1";
        }
        $x = 0;
        $y = 0;
        if($ptz != "1"){
            if($ptz == "23"){
                $x = -0.1;
                $y = 0;
            }else if($ptz == "21"){
                $x = 0;
                $y = 0.1;
            }else if($ptz == "24"){
                $x = 0.1;
                $y = 0;
            }else if($ptz == "22"){
                $x = 0;
                $y = -0.1;
            }else if($ptz == "25"){
                $x = -0.1;
                $y = 0.1;
            }else if($ptz == "26"){
                $x = 0.1;
                $y = 0.1;
            }else if($ptz == "27"){
                $x = -0.1;
                $y = -0.1;
            }else if($ptz == "28"){
                $x = 0.1;
                $y = -0.1;
            }
            $this->doptz($b,$user,$pwd,$url,$profiletoken,$x,$y);
            }
        else if($ptz == "1")
        $this->stopptz($b,$user,$pwd,$url,$profiletoken);
        }
    protected function _passwordDigest( $user, $pwd, $timestamp = "default", $nonce = "default"){
        if ($timestamp=='default') $timestamp=date('Y-m-d\TH:i:s\Z');
        if ($nonce=='default') $nonce=mt_rand();
        $REQ=array();
        $password= $pwd;
        $passdigest=base64_encode(sha1($nonce.$timestamp.$password,true));
        $REQ['USERNAME']=$user;
        $REQ['PASSDIGEST']=$passdigest;
        $REQ['NONCE']=base64_encode(pack('H*', $nonce));
        //$REQ['NONCE']=base64_encode($nonce);
        $REQ['TIMESTAMP']=$timestamp;
        return $REQ;
    }
    protected function stopptz($b,$user,$pwd,$url,$profiletoken){
        if($b == 'dahua'){
            $timestamp=time();
            $REQ=$this->_passwordDigest($user,$pwd,date('Y-m-d\TH:i:s\Z',$timestamp));
            $post_string='<?xml version="1.0" encoding="utf-8"?>
                <s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:tptz="http://www.onvif.org/ver20/ptz/wsdl" xmlns:tt="http://www.onvif.org/ver10/schema">
                  <s:Header>
                    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                      <wsse:UsernameToken>
                        <wsse:Username>%%USERNAME%%</wsse:Username>
                        <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">%%PASSWORD%%</wsse:Password>
                        <wsse:Nonce>%%NONCE%%</wsse:Nonce>
                        <wsu:Created>%%CREATED%%</wsu:Created>
                      </wsse:UsernameToken>
                    </wsse:Security>
                  </s:Header>
                  <s:Body>
                    <tptz:Stop>
                      <tptz:ProfileToken>%%PROFILETOKEN%%</tptz:ProfileToken>
                      <tptz:PanTilt>true</tptz:PanTilt>
                      <tptz:Zoom>true</tptz:Zoom>
                    </tptz:Stop>
                  </s:Body>
                </s:Envelope>';
            $post_string=str_replace(array("%%USERNAME%%",
                "%%PASSWORD%%",
                "%%NONCE%%",
                "%%CREATED%%",
                "%%PROFILETOKEN%%"),
                array($REQ['USERNAME'],
                    $REQ['PASSDIGEST'],
                    $REQ['NONCE'],
                    $REQ['TIMESTAMP'],
                    $profiletoken),
                $post_string);
        }else if($b == 'haikang'){
            $post_string='<?xml version="1.0" encoding="utf-8"?>
            <s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:tptz="http://www.onvif.org/ver20/ptz/wsdl" xmlns:tt="http://www.onvif.org/ver10/schema">
              <s:Header>
                <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                  <wsse:UsernameToken>
                    <wsse:Username>admin</wsse:Username>
                    <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">K1Q3oIzGo1xXCpli+JvtfhkJVx4=</wsse:Password>
                    <wsse:Nonce>HM8Yr+mld3eqfDzrOCXl0Q==</wsse:Nonce>
                    <wsu:Created>2017-06-01T07:28:19Z</wsu:Created>
                  </wsse:UsernameToken>
                </wsse:Security>
              </s:Header>
              <s:Body>
                <tptz:Stop>
                  <tptz:ProfileToken>Profile_1</tptz:ProfileToken>
                  <tptz:PanTilt>true</tptz:PanTilt>
                  <tptz:Zoom>true</tptz:Zoom>
                </tptz:Stop>
              </s:Body>
            </s:Envelope>';
        }
        return $this->_send_request($url,$post_string);


    }

    protected function doptz($b,$user,$pwd,$url,$profiletoken,$x,$y){

        if($b == 'dahua'){
            $post_string='<s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope"><s:Header><Security s:mustUnderstand="1" xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd"><UsernameToken><Username>%%USERNAME%%</Username><Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">%%PASSWORD%%</Password><Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">%%NONCE%%</Nonce><Created xmlns="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">%%CREATED%%</Created></UsernameToken></Security></s:Header><s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="https://www.w3.org/2009/XMLSchema/XMLSchema.xsd"><ContinuousMove xmlns="http://www.onvif.org/ver20/ptz/wsdl"><ProfileToken>%%PROFILETOKEN%%</ProfileToken><Velocity><PanTilt x="%%VELOCITYPANTILTX%%" y="%%VELOCITYPANTILTY%%" space="http://www.onvif.org/ver10/tptz/PanTiltSpaces/VelocityGenericSpace" xmlns="http://www.onvif.org/ver10/schema"/></Velocity></ContinuousMove></s:Body></s:Envelope>';
            $timestamp=time();
            $REQ=$this->_passwordDigest($user,$pwd,date('Y-m-d\TH:i:s\Z',$timestamp));
            //var_dump($REQ);
            //var_dump($b);
            $post_string=str_replace(array("%%USERNAME%%",
                "%%PASSWORD%%",
                "%%NONCE%%",
                "%%CREATED%%",
                "%%PROFILETOKEN%%",
                "%%VELOCITYPANTILTX%%",
                "%%VELOCITYPANTILTY%%"),
                array($REQ['USERNAME'],
                    $REQ['PASSDIGEST'],
                    $REQ['NONCE'],
                    $REQ['TIMESTAMP'],
                    $profiletoken,
                    $x,
                    $y),
                $post_string);
        }else if($b == 'haikang'){
            $post_string='<?xml version="1.0" encoding="utf-8"?>
                        <s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:tptz="http://www.onvif.org/ver20/ptz/wsdl" xmlns:tt="http://www.onvif.org/ver10/schema">
                          <s:Header>
                            <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
                              <wsse:UsernameToken>
                                <wsse:Username>admin</wsse:Username>
                                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">xO69eooVZ3Y05qMD7ZL0Gf3tLqw=</wsse:Password>
                                <wsse:Nonce>sNGMRXECtb0JGd10ytUlWw==</wsse:Nonce>
                                <wsu:Created>2017-06-01T06:41:19Z</wsu:Created>
                              </wsse:UsernameToken>
                            </wsse:Security>
                          </s:Header>
                          <s:Body>
                            <tptz:ContinuousMove>
                              <tptz:ProfileToken>%%PROFILETOKEN%%</tptz:ProfileToken>
                              <tptz:Velocity>
                                <tt:PanTilt x="%%VELOCITYPANTILTX%%" y="%%VELOCITYPANTILTY%%" />
                                <tt:Zoom x="0" />
                              </tptz:Velocity>
                            </tptz:ContinuousMove>
                          </s:Body>
                        </s:Envelope>';
            $post_string=str_replace(array(
                "%%PROFILETOKEN%%",
                "%%VELOCITYPANTILTX%%",
                "%%VELOCITYPANTILTY%%"),
                array(
                    $profiletoken,
                    $x,
                    $y),
                $post_string);
        }

        return $this->_send_request($url,$post_string);
    }
    protected function _send_request($url,$post_string) {
        $soap_do = curl_init();

        curl_setopt($soap_do, CURLOPT_URL,            $url );
        if ($this->proxyhost!='' && $this->proxyport!='') {
            curl_setopt($soap_do, CURLOPT_PROXY, 	      $this->proxyhost);
            curl_setopt($soap_do, CURLOPT_PROXYPORT,    $this->proxyport);
            if ($this->proxyusername!='' && $this->proxypassword!='')
                curl_setopt($soap_do, CURLOPT_PROXYUSERPWD, $this->proxyusername.':'.$this->proxypassword);
        }
        curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($soap_do, CURLOPT_TIMEOUT,        10);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($soap_do, CURLOPT_POST,           true );
        curl_setopt($soap_do, CURLOPT_POSTFIELDS,    $post_string);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml; charset=utf-8', 'Content-Length: '.strlen($post_string) ));
        //curl_setopt($soap_do, CURLOPT_USERPWD, 'admin' . ":" . 'admin'); // HTTP authentication
        if ( ($result = curl_exec($soap_do)) === false) {
            $err = curl_error($soap_do);
            return array("Fault"=>$err);
        } else {
            $result=substr($result,strpos($result,"<s:Body>"));
            //$result=substr($result,0,strpos($result,"</s:Body>")+strlen("</s:Body>"));
            //@$xmldata=simplexml_load_string($result);
            //return $this->_object2array($xmldata);
            return $result;
        }

    }





}