<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use App\Models\SubscribeMail;
use function dd;
use Pdp\Cache;
use Pdp\CurlHttpClient;
use Pdp\Manager;
use PhpImap\Mailbox;
use function date;
use function time;
use function var_dump;

class SyncSubscribeMail extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:subscribe-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '解析商家订阅邮件';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $manager = new Manager(new Cache(), new CurlHttpClient());
        $rules = $manager->getRules(); //$rules is a Pdp\Rules object


        $mailbox = new Mailbox('{imap.ym.163.com:993/imap/ssl}INBOX', // IMAP server and mailbox folder
            'subscribe@wantgains.com', // Username for the before configured mailbox
            '7Vsev4bBmv', // Password for the before configured username
            __DIR__, // Directory, where attachments will be saved (optional)
            'UTF-8' // Server encoding (optional)
        );
        $mailsIds = $mailbox->searchMailbox('ALL');
        if ($mailsIds) {
            foreach ($mailsIds as $mailsId) {
                $mail = $mailbox->getMail($mailsId);
                $domain = $rules->resolve($mail->senderHost);
                $merchantInfo = Merchant::where('domain', $domain->getRegistrableDomain())->first();
                $merchantId = $merchantInfo->id ?? 0;
                SubscribeMail::updateOrCreate(['mail_id' => $mailsId], [
                    'mail_id'     => $mailsId,
                    'content'     => $mail->textHtml,
                    'subject'     => $mail->subject,
                    'source'      => '163',
                    'merchant_id' => $merchantId,
                    'domain'      => $domain,
                    'updated_at'  => date('Y-m-d H:i:s', time()),
                    'send_at' => $mail->date
                ]);
                $this->cli->green("{$mailsId} success");
            }
            $mailbox->markMailsAsRead($mailsIds);
        }
    }
}
