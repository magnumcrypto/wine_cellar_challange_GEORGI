<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $to, string $subject,  string $html, string $userName, string $userSurname): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from('example@example.com')
                ->to(new Address($to))
                ->subject($subject) //asunto
                ->htmlTemplate($html)
                ->locale('es')
                ->context([
                    'userName' => $userName,
                    'userSurname' => $userSurname,
                    'userEmail' => $to,
                    'expiration_date' => new \DateTime('+7 days')
                ]);

            $this->mailer->send($email);
        } catch (\Exception $e) {
            // Manejar el error
            throw new \Exception('There was a problem sending the email: ' . $e->getMessage());
        }
    }
}
