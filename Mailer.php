<?php

class Mailer
{
    private string $logFile;

    public function __construct(string $logFile = __DIR__ . '/logs/mails.log')
    {
        $this->logFile = $logFile;

        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
    }

    // En local, on n'a pas de serveur SMTP : on "envoie" le mail en l'écrivant dans un fichier log.
    public function send(string $to, string $subject, string $body): void
    {
        $entry = sprintf(
            "[%s] À : %s\nSujet : %s\n%s\n%s\n\n",
            date('Y-m-d H:i:s'),
            $to,
            $subject,
            $body,
            str_repeat('-', 60)
        );

        file_put_contents($this->logFile, $entry, FILE_APPEND);
    }
}
