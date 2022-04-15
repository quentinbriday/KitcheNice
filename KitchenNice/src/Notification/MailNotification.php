<?php


namespace App\Notification;


use App\Entity\Membre;
use Twig\Environment;

class MailNotification
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $environment;

    public function __construct(\Swift_Mailer $mailer, Environment $environment)
    {
        $this->mailer = $mailer;
        $this->environment = $environment;
    }

    /**
     * @param Membre $membre
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function notifyCreatedAccount(Membre $membre)
    {
        $message = (new \Swift_Message('KitcheNice : vérification du compte.'))
            ->setFrom('noreply@kitchenice.be')
            ->setTo($membre->getMail())
        ->setBody($this->environment->render('emails/account_verification.html.twig',
            [
                'membre' => $membre,
            ]), 'text/html');
        $this->mailer->send($message);
    }

    /**
     * @param Membre $membre
     * @param $password
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function notifyPasswordChanged(Membre $membre, $password)
    {
        $message = (new \Swift_Message('KitcheNice : modification du mot de passe.'))
            ->setFrom('noreply@kitchenice.be')
            ->setTo($membre->getMail())
            ->setBody($this->environment->render('emails/password_verification.html.twig',
                [
                    'membre' => $membre,
                    'password' => $password,
                ]), 'text/html');
        $this->mailer->send($message);
    }

    /**
     * @param Membre|null $membre
     * @param $password
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function notifyPasswordLost(?Membre $membre, $password)
    {
        $message = (new \Swift_Message('KitcheNice : restauration du mot de passe.'))
            ->setFrom('noreply@kitchenice.be')
            ->setTo($membre->getMail())
            ->setBody($this->environment->render('emails/password_lost.html.twig',
                [
                    'membre' => $membre,
                    'password' => $password,
                ]), 'text/html');
        $this->mailer->send($message);
    }
}