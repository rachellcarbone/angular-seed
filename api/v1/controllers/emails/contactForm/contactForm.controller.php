<?php namespace API;
require_once dirname(dirname(dirname(__FILE__))) . '/services/api.mailer.php';

use \Respect\Validation\Validator as v;


class ContactFormController {
    
    static function sendContactEmail($app) {
        $post = $app->request->post();
        self::validateParameters($app, $post);
        
        // Setup mailer for sending message
        $mail = ApiMailer::getPhpMailer();
        
        // Add Recipient - include name if it was sent
        if(!$recipientName || $recipientName === '') {
            $mail->addAddress($recipientEmail);
        } else {
            $mail->addAddress($recipientEmail, $recipientName);
        }

        // Retrieve template
        $emailTemplate = self::selectEmailTemplate($templateId);
        if (!$emailTemplate) {
            self::logMailError(LOG_ERR, "ERROR RETRIEVING EMAIL TEMPLATE, templateId <{$templateId}>");
            return array('error' => true, 'msg' => "Error generating email <{$templateId}>");
        }

        // If a from email is set
        if($emailTemplate->fromEmail) {
            $mail->setFrom($emailTemplate->fromEmail, $emailTemplate->fromName);
        }
        
        // If a reply to email is set
        if($emailTemplate->replyEmail) {
            $mail->setFrom($emailTemplate->replyEmail, $emailTemplate->replyName);
        }
        
        // Substitute parameters into template
        // Template substitution is for parms named !@{NUMBER}@!, i.e., !@0@!, !@1@!, etc
        // Subject Substituion
        $subject = $emailTemplate->subject;
        for($index = 0; $index < count($subjectParams); $index++) {
            $subject = str_replace("!@{$index}@!", $subjectParams[$index], $subject);
        }
        $mail->Subject = $subject;
        // Body Substitution
        $bodyHtml = $emailTemplate->bodyHtml;
        $bodyPlain = $emailTemplate->bodyPlain;
        for($index = 0; $index < count($bodyParams); $index++) {
            $bodyHtml = str_replace("!@{$index}@!", $bodyParams[$index], $bodyHtml);
            $bodyPlain = str_replace("!@{$index}@!", $bodyParams[$index], $bodyPlain);
        }
        
        $mail->Body = $bodyHtml;
        $mail->AltBody = $bodyPlain;
        
        if ($mail->send()) {
            // log the success
            self::logMailError(LOG_INFO, "EMAIL SUCCESS\n Template Id:<{$templateId}> Sender: <{$emailTemplate->replyEmail}, {$emailTemplate->replyName}> Recipient: <{$recipientEmail}, {$recipientName}> Subject: <{$subject}> Body: <{$bodyPlain}>");
            return (!$recipientName || $recipientName === '') ? array('error' => false, 'msg' => "Success! Email Sent to \"{$recipientEmail}\"") :
                    array('error' => false, 'msg' => "Success! Email Sent to \"{$recipientEmail}, {$recipientName}\"");
        } else {
            // log the error
            self::logMailError(LOG_ERR, "EMAIL FAILURE\nError <{$mail->ErrorInfo}>/n Template Id:<{$templateId}> Sender: <{$emailTemplate->replyEmail}, {$emailTemplate->replyName}> Recipient: <{$recipientEmail}, {$recipientName}> Subject: <{$subject}> Body: <{$bodyPlain}>");
            return (!$recipientName || $recipientName === '') ? array('error' => true, 'msg' => "Unknown Error: Error sending email to \"{$recipientEmail}, {$recipientName}\"") : 
                array('error' => true, 'msg' => "Unknown Error: Error sending email to \"{$recipientEmail}, {$recipientName}\"");
        }
        
        if($sent) {
            return $app->render(200, array('msg' => "Player invite sent to '{$post['email']}'."));
        } else {
            return $app->render(400, array('msg' => 'Could not send player invite.'));
        }
    }
    
    private static function validateParameters($app, $post) {
        if (v::key('email', v::email())->validate($post)) {
            return $app->render(400,  array('msg' => 'Invalid email. Check your parameters and try again.'));
        } else if (!v::key('name', v::stringType())->validate($post) ||
            !v::key('subject', v::stringType())->validate($post) ||
            !v::key('message', v::stringType())->validate($post)) {
            return $app->render(400,  array('msg' => 'Invalid subject or message. Check your parameters and try again.'));
        }
        return true;
    }
    
}
