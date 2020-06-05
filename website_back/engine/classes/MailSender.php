<?php

Class MailSender{

    private static $tpl_id;
    private static $tpl_html;

    const AMOUNT = 5; // მეილების რაოდენობა კოდის ყოველ გაშვებაზე

    /**
     * შაბლონი რომელიც უნდა გაეგზავნოს მომხმარებელს
     * @return Boolean 
     */    
    private static function getActiveTpl(){        
        if($tpl = MailSenderDB::getMailsToSendByActive()){
            self::$tpl_id = $tpl->id;
            self::$tpl_html = $tpl->html;
        }
        return $tpl;
    }

    /**
     * მეილების სია სადაც უნდა გაიგზავნოს შაბლონი
     * @return Array 
     */
    private static function mails(){
        $mails = MailSenderDB::getMailsList(self::$tpl_id, self::AMOUNT);

        return $mails;
    }

    /**
     * აგენერირებს sql კოდს mails_log ბაზისთვის
     * @return String
     */
    private static function generateValuesForLog(){
        
        $list = self::mails(self::$tpl_id);
        $values = null;

        foreach ($list as $email) {
            $values .= '('.$email->id.', '.self::$tpl_id.', 1),';
        }

        $values = rtrim($values, ',');

        return $values;
    }

    /**
     * ქმნის მასივს სადაც ინახება ინფორმაცია შაბლონის და იმეილების მისამართების შესახებ
     * @return Array
     */
    private static function generateMessage(){
        $msg = array();

        $msg['text'] = self::$tpl_html;

        foreach (self::mails() as $mail) {
            $msg['addressee'][] = $mail->email;
        }

        return $msg;
    }

    /**
     * ანახლებს mails_to_send ბაზის sent ველს
     */
    private static function finish(){
        if(!self::mails())
            MailSenderDB::updateMailsToSend(self::$tpl_id);
    }

    public static function init(){
        if(self::getActiveTpl() && $sql = self::generateValuesForLog()){

            Mail::sendMailSenderMessage( self::generateMessage() );

            MailSenderDB::updateMailsLog($sql);
            
            self::finish();
        }
    }
}

// MailSender::init();